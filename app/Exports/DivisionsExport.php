<?php

namespace App\Exports;

use App\Division;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DivisionsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Division::all();
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
