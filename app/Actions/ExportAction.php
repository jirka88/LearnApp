<?php

namespace App\Actions;

use App\Enums\ToastifyStatus;
use App\Exports\LogExport;
use Maatwebsite\Excel\Facades\Excel;
use ReflectionClass;

class ExportAction
{
    public function handle($extension, $class)
    {
        try {
            $reflectionClass = new ReflectionClass($class);
        } catch (\ReflectionException $e) {
            return redirect()->back()->with(['status' => ToastifyStatus::ERROR, 'message' => 'Nastala nečekaná chyba!']);
        }
        $instance = $reflectionClass->newInstance();
        switch ($extension) {
            case 'pdf':
                $sheet = Excel::download($instance, 'x.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
                break;
            case 'xlsx':
                $sheet = Excel::download($instance, 'x.xlsx');
                break;
            case 'csv':
                $sheet = Excel::download($instance, 'x.csv', \Maatwebsite\Excel\Excel::CSV);
                break;
            case 'html':
                $sheet = Excel::download(new $instance, 'x.html', \Maatwebsite\Excel\Excel::HTML);
                break;
            case 'xml':
                $sheet = Excel::download(new $instance, 'x.xml', \Maatwebsite\Excel\Excel::XML);
                break;
        }
        return $sheet;
    }
}
