<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FileExport implements FromCollection, WithProperties, ShouldAutoSize, WithEvents, WithCustomCsvSettings, WithStyles, WithHeadings, WithMapping
{
    use Exportable;

    private $model;
    private $withoutColumn = ['image', 'password', 'remember_token', 'updated_at', 'subject_type', 'causer_type', 'subject_id'];

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet
                    ->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('1')->getFont()->setSize(10);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function collection()
    {
      return $this->model::all();
    }
    public function map($row): array
    {
        $rowColumn = $row->toArray();
        $rowFiltered = array_except($rowColumn, $this->withoutColumn);
        return $rowFiltered;
    }
    public function headings(): array
    {
        $columns = Schema::getColumnListing($this->model->getTable());
        $filteredColumns = array_diff($columns, $this->withoutColumn);
        return $filteredColumns;
    }

    public function properties(): array
    {
        return [
            'creator' => 'LearnApp',
            'title' => 'Export',
            'description' => 'Výpis dat z webové aplikace LearnApp',
        ];
    }
    public function getCsvSettings(): array
    {
        return [
            'output_encoding' => 'ISO-8859-1',
        ];
    }
    public function encoding(): string
    {
        return 'UTF-8';
    }
}
