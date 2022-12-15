<?php

namespace App\Imports;

use App\QuranLine as QuranLineModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuranLine implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (isset($row['serial_number']) && isset($row['aya']) && isset($row['aya_normal'])){
            return new QuranLineModel([
                'serial_number' => $row['serial_number'],
                'aya'   => $row['aya'],
                'aya_normal'   => $row['aya_normal'],
                'lesson'   => $row['lesson'],
                'page'   => $row['page'],
                'part'   => $row['part'],
                'aya_num'   => $row['aya_num'],
                'aya_length'   => $row['aya_length'],
            ]);
        }
    }
}
