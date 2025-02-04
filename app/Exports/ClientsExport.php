<?php

namespace App\Exports;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ClientsExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $formattedData = [];
        foreach ($this->data as $index => $row) {
            // Prepend the numbering (index + 1 for 1-based index)
            $formattedData[] = array_merge([$index + 1], $row);
        }
        return $formattedData;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Client',
            'NIK',
            'Hasil Pengecekan PEP',
            'Nama Nasabah',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jabatan',
            'Instansi',
            'Waktu Pengecekan',
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('C1:C1000')->getNumberFormat()->setFormatCode('@');
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => Color::COLOR_WHITE],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => '0070C0', // Warna latar belakang (misalnya, biru)
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 10,
            'C' => 20,
            'D' => 20,
            'E' => 35,
            'F' => 20,
            'G' => 20,
            'H' => 10,
            'I' => 10,
            'J' => 20            
        ];
    }
}
