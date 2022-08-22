<?php

namespace App\Imports;

use App\TopTrackerEmployee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TopTrackerImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        /*
         *
         *
         *
         *    "alhoy"
         *    "rkm_almothf" => 1
              "alasm" => "عطية محمد شفيق قاري"
              "alksm" => "بنات"
              "alothyf" => "موظف"
        */

        $nationality_no = trim($row['alhoy']);
        $employee_no = trim($row['rkm_almothf']);
        $name = trim($row['alasm']);
        $section = trim($row['alksm']);
        $type = trim($row['alothyf']);

        if ($type == 'معلم'){
            $type = '1';
        }elseif($type == 'موظف'){
            $type = '2';
        }elseif($type == 'غير منتظم'){
            $type = '0';
        }

        if (!empty($nationality_no) && !empty($employee_no) && !empty($name) && !empty($section) && !empty($type))
        {
            TopTrackerEmployee::query()->updateOrCreate([
                    'nationality_no' => $nationality_no,
                    'employee_no' => $employee_no,
                    'name'        => $name,
                ],
                [
                    'section' => ($section == 'بنات' ? '2' : '1'),
                    'type'    => $type,
                ]);
        }
    }

    public function batchSize(): int
    {
        return 300;
    }

    public function chunkSize(): int
    {
        return 300;
    }


}
