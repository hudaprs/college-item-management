<?php

namespace App\Exports;

use App\Level;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LevelsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Level::all();
    }

    public function headings() : array
    {
        return [
            'id',
            'name',
            'created_at',
            'updated_at'
        ];
    }
}
