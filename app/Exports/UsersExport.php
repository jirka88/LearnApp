<?php

namespace App\Exports;

use App\Models\User;
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

class UsersExport implements WithHeadings, WithMapping, FromCollection, WithProperties, ShouldAutoSize, WithEvents, WithCustomCsvSettings, WithStyles
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
        return User::with(['licences', 'roles', 'accountTypes'])->get();

    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->firstname,
            $row->lastname,
            $row->slug,
            $row->email,
            $row->roles->role,
            $row->accountTypes->type,
            $row->licences->Licence,
            $row->active ? 'ANO' : 'NE',
            $row->canShare ? 'ANO' : 'NE',
            $row->created_at,
            $row->updated_at
        ];

    }

    public function headings(): array
    {
        $columns = Schema::getColumnListing('users');
        $excludeColumns = ['password', 'remember_token', 'image'];
        $filteredColumns = array_diff($columns, $excludeColumns);
        return [$filteredColumns];
    }

    public function properties(): array
    {
        return [
            'creator' => 'LearnApp',
            'title' => 'Uživatelé export',
            'description' => 'Výpis uživatelů z webové aplikace LearnApp',
        ];
    }
    public function getCsvSettings(): array
    {
        return [
            'output_encoding' => 'ISO-8859-1',
        ];
    }
}
