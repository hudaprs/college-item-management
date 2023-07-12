<?php

namespace App\Imports;

use App\Level;
use Maatwebsite\Excel\Concerns\ToModel;

class LevelsImport implements ToModel
{
    public function model(array $row)
    {
        return new Level([
            'name' => $row[1]
        ]);
    }
}
