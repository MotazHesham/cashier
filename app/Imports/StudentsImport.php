<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StudentsImport implements ToModel
{

    use Importable;

    public function model(array $rows)
    {
        return $rows;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
