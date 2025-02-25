<?php

namespace App\Exports;

use App\Models\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class ResponseSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function collection()
    {
        // Create a collection with a single item to ensure we have data to show
        return collect([$this->response]);
    }

    public function title(): string
    {
        return 'Response #' . $this->response->id;
    }

    public function headings(): array
    {
        return [
            'Question',
            'Answer'
        ];
    }

    public function map($response): array
    {
        $rows = [];
        foreach ($response->answers as $answer) {
            $rows[] = [
                $answer->question->title ?? 'Unknown Question',
                $answer->value ?? 'No Answer'
            ];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // Add response metadata at the top
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', 'Response Details');
        $sheet->mergeCells('A2:B2');
        $sheet->setCellValue('A2', 'IP Address: ' . $this->response->ip_address);
        $sheet->mergeCells('A3:B3');
        $sheet->setCellValue('A3', 'Submitted: ' . $this->response->created_at->format('Y-m-d H:i:s'));
        
        // Start the actual data from row 5
        $sheet->fromArray($this->headings(), null, 'A5');
        
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['size' => 12]],
            3 => ['font' => ['size' => 12]],
            5 => ['font' => ['bold' => true]],
            'A' => ['width' => 40],
            'B' => ['width' => 40],
        ];
    }
}
