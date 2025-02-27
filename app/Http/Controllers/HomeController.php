<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogUsage;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalCount = LogUsage::count();
        $totalFound = LogUsage::where('status', 'DATA FOUND')->count();
        $totalNotFound = LogUsage::where('status', 'NOT FOUND')->count();
        $totalOthers = LogUsage::whereNotIn('status', ['NOT FOUND', 'DATA FOUND'])->count();
        $latestLogUsage = LogUsage::orderBy('created_at', 'desc')->first();
        $latestDateUsage = $latestLogUsage->created_at;

        $year = now()->year;
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $count = LogUsage::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', 'DATA FOUND')
                ->count();

            $data[] = $count;
        }
        
        return view('home', 
        [
            'totalCount' => $totalCount,
            'totalFound' => $totalFound,
            'totalNotFound' => $totalNotFound,
            'totalOthers' => $totalOthers,
            'latestDateUsage' => Carbon::parse($latestDateUsage)->format('d-m-Y H:i:s'),
            'data' => $data,
            'year' => $year
        ]);
    }

    public function about()
    {
        return view('about');
    }
}
