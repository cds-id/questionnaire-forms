<?php

namespace App\Exports;

use App\Models\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ResponsesExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $responses = Response::with(['answers.question', 'questionnaire'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($responses as $response) {
            $sheets[] = new ResponseSheet($response);
        }

        // If no responses, create an empty sheet
        if (empty($sheets)) {
            $sheets[] = new EmptyResponseSheet();
        }

        return $sheets;
    }
}

class ResponseSheet implements FromCollection, WithHeadings, WithMapping
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function collection()
    {
        return [$this->response];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Respondent Name',
            'Questionnaire',
            'Questions and Answers',
            'Created At'
        ];
    }

    public function map($response): array
    {
        $answersText = $response->answers->map(function($answer) {
            return $answer->question->text . ': ' . $answer->answer;
        })->join("\n");

        return [
            $response->id,
            $response->respondent_name,
            $response->questionnaire->title ?? 'N/A',
            $answersText,
            $response->created_at->format('Y-m-d H:i:s')
        ];
    }
}

class EmptyResponseSheet implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithTitle, \Maatwebsite\Excel\Concerns\WithHeadings
{
    public function collection()
    {
        return collect([]);
    }

    public function title(): string
    {
        return 'No Responses';
    }

    public function headings(): array
    {
        return [
            'No responses found in the system'
        ];
    }
}
