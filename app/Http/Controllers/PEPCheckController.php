<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PEPCheck;
use App\Models\LogActivity;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\ClientsExport;
use App\Exports\ListExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Illuminate\Support\Facades\Session;


class PEPCheckController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->pencarian;
        $PEPList = PEPCheck::orderBy('id', 'desc')
                    ->where('name', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('nik', 'LIKE', '%'.$keyword.'%')
                    ->paginate(10);
        return view('PEPCheck/index',
         [
            'pepList' => $PEPList
         ],
         compact('keyword'));
    }

    public function create() 
    {
        return view('PEPCheck/create');
    }

    public function show($id)
    {
        $detailPEP = PEPCheck::findOrFail($id);

        return view('PEPCheck/detail',[
            'pepDetail' => $detailPEP
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');

        // Membaca file Excel
        $data = Excel::toArray(new class implements ToArray {
            public function array(array $array)
            {
                return $array; // Mengembalikan semua baris sebagai array
            }
        }, $file);

        // Mengambil data dari kolom C (indeks 2) mulai dari C2
        $results = [];
        foreach ($data[0] as $key => $row) {
            if ($key >= 1) { // Mulai dari baris ke 2
                $results []= [
                    'nik' => $row[2], // Ambil kolom C
                    'client_id' => $row[1]
                ];
            }
        }
        // Menghapus elemen kosong
        $results = array_filter($results, function($item) {
            return !is_null($item['nik']) && !is_null($item['client_id']);
        });

        $data_length = count($results);
        if($data_length > env('PEP_EXCEL_LIMIT'))
        {
            Session::flash('statusUpload','failed');
            Session::flash('messageUpload','Jumlah data melebihi daily limit '.env('PEP_EXCEL_LIMIT'));
            
            return redirect('/pepCheck/create');
        }

        $export_data =  $this->checkPEPBulk($results);        

        return Excel::download(new ClientsExport($export_data), 'Check-Results-'.Carbon::now()->format('Y-m-d').'.xlsx');

        // call url
        // $PEPList = PEPCheck::orderBy('id', 'desc')->paginate(10);

        // return view('PEPCheck/index' , [
        //     'pepList' => $PEPList
        // ]);
    }

    public function getToken(){
        $response_token =  Http::withHeaders([
                            'client_id' => env('PEP_CLIENT_ID')
                        ])->withBasicAuth(env('PEP_USERNAME'), env('PEP_PASSWORD'))
                        ->post(env('PEP_AUTH_URL'));

        return $response_token['access_token'];
    }

    public function checkSingleNik($token, $nik){
        $responseGet = $client->get(env('PEP_API_URL').$nik, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        // Jika token tidak valid, dapatkan token baru dan coba lagi
        if ($responseGet['status'] == 401 || $responseGet['message'] == 'Token Undefined') {
            $token = getToken(); // Dapatkan token baru
            $result = checkSinglePep($token, $nik); // Coba lagi dengan token baru
            handleResult($result, $nik, $new_resultArray); // Tangani hasil dengan token baru
        }

    }

    public function checkPEPBulk($array) {
        $user = Auth::user();
        $userFirstAndLastName = $user->first_name.' '.$user->last_name;

        $token = $this->getToken();
        
        $client = new Client();

        $export_data = [];
        set_time_limit(900);

        foreach($array as $v) {
            try {
                $this->checkSingleNik($token, $v['nik']);

                if ($responseGet->getStatusCode() === 200) { // PEP Found
                    $data = json_decode($responseGet->getBody(), true);
                    
                    //insert into db
                    PEPCheck::create([
                        'nik' => $data['data']['NIK'],
                        'name' => $data['data']['NAMA_LGKP'],
                        'jabatan' => $data['data']['NAMA_JABATAN'],
                        'instansi' => $data['data']['NAMA_LEMBAGA'],
                        'tanggal_lahir' => $data['data']['TGL_LHR'],
                        'tempat_lahir' => $data['data']['TMPT_LHR'],
                        'kabupaten' => '-',
                        'provinsi' => '-',
                        'check_by' => $userFirstAndLastName,
                        'client_code' => $v['client_id']
                    ]);

                    //insert log db
                    LogActivity::create([
                        'type' => 'PEP',
                        'status' => 'DATA FOUND',
                        'request' => $v['nik'],
                        'response' => json_encode($data),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'url' => env('PEP_API_URL').$v['nik'],
                        'method' => 'POST',
                        'request_by' => $userFirstAndLastName
                    ]);

                    //create array
                    $new_result = [
                        $v['client_id'], $v['nik'].' ', $data['message'], $data['data']['NAMA_LGKP'],
                        $data['data']['TMPT_LHR'], $data['data']['TGL_LHR'],$data['data']['NAMA_JABATAN'],
                        $data['data']['NAMA_LEMBAGA'], Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }                
            }
            catch (RequestException $e) {
                // Menangani error
                if ($e->hasResponse()) {
                    $errorResponse = $e->getResponse();
                    $statusCode = $errorResponse->getStatusCode();
                    $errorBody = json_decode($errorResponse->getBody(), true);
                    
                    if ($statusCode !== 200) { // PEP Notfound
                        //insert log db
                        LogActivity::create([
                            'type' => 'PEP',
                            'status' => 'NOT FOUND',
                            'request' => $v['nik'],
                            'response' => json_encode($errorBody),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'url' => env('PEP_API_URL').$v['nik'],
                            'method' => 'POST',
                            'request_by' => $userFirstAndLastName
                        ]);

                        //create array
                        $new_result = [
                            $v['client_id'], $v['nik'].' ', $errorBody['message'], '',
                            '', '','','', Carbon::now()->format('Y-m-d H:i:s')
                        ];                     
                    }
                }
            }
            //push result into array
            array_push($export_data, $new_result);
        }
        
        /*$dummyClient = new Client();
        
        for($i = 0 ; $i< 1800 ; $i++){
            $response = $client->get('http://jsonplaceholder.typicode.com/todos/1');
            $data = json_decode($response->getBody(), true);

            $new_result = [
                $data['userId'], $data['id'],$data['title']
            ];
            array_push($export_data, $new_result);
        }*/
        return $export_data;
    }

    public function download(Request $request) {
        $keyword = $request->input('pencarian');
        
        $PEPList = PEPCheck::orderBy('id', 'desc')
                    ->where(function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', '%'.$keyword.'%')
                              ->orWhere('nik', 'LIKE', '%'.$keyword.'%');
                    })->get();

        $export_data = [];
        foreach($PEPList as $v){
            $new_result = [
                $v['client_code'], 
                $v['nik'].' ', 
                'Data Found',
                $v['name'],
                $v['tempat_lahir'], 
                $v['tanggal_lahir'],
                $v['jabatan'],
                $v['instansi'], 
                $v['created_at']->format('Y-m-d H:i:s')
            ];
            array_push($export_data, $new_result);
        }

        return Excel::download(new ClientsExport($export_data), 'PEP-List-'.Carbon::now()->format('Y-m-d').'.xlsx');
    }

    public function delete($id)
    {
        $delete = PEPCheck::findOrFail($id)->delete();
        if($delete){
            return response()->json(['message' => 'Delete success'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete'], 500);
        }
    }

    

    
}
