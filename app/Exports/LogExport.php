<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Spatie\Activitylog\Models\Activity;

class LogExport implements WithHeadings, WithMapping, FromCollection, WithProperties, WithEvents, WithCustomCsvSettings, WithStyles, ShouldAutoSize
{
    use Exportable;

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
        return Activity::all();

    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->description,
            $row->event,
            $row->causer->email,
            $row->created_at
        ];

    }

    public function headings(): array
    {
        $columns = ['id', 'Událost', 'Event', 'Způsobil', 'Kdy'];
        return [$columns];
    }

    public function properties(): array
    {
        return [
            'creator' => 'LearnApp',
            'title' => 'Logy export',
            'description' => 'Výpis logů z webové aplikace LearnApp',
        ];
    }
    public function getCsvSettings(): array
    {
        return [
            'output_encoding' => 'ISO-8859-1',
        ];
    }
}
