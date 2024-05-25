<?php

namespace App\Exports;

use App\Models\Chapter;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChapterExport implements FromArray, WithMapping, WithProperties, WithTitle
{
    private $chapter;

    public function __construct(Chapter $chapter)
    {
        $this->chapter = $chapter;
    }


    public function array(): array
    {
        return [$this->chapter->toArray()];
    }
    public function map($row): array
    {
        return [
            $row['name'],
            $row['perex'],
            $row['context']
        ];

    }

    public function headings(): array
    {
        $columns = Schema::getColumnListing('chapter');
        $excludeColumns = ['partition_id', 'id', 'slug', 'created_at', 'updated_at', 'name'];
        $filteredColumns = array_diff($columns, $excludeColumns);
        return $filteredColumns;
    }

    public function properties(): array
    {
        return [
            'creator' => 'LearnApp',
        ];
    }
    public function title(): string
    {
        return 'Kapitola' . $this->chapter->name;
    }
}
