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
         * alhoy
         *
         *
         *   "rkm_almothf" => 1
  "alasm" => "عطية محمد شفيق قاري"
  "alksm" => "بنات"
  "alothyf" => "موظف"
  "altaalym_alhdory" => null
*/
        dd($row);

        TopTrackerEmployee::query()->updateOrCreate([
        ],
        [

        ]);
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
