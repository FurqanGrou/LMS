<?php

namespace App\Imports;

use App\Lesson;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LessonsAyatImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        /*
         * asm_alsor
         * aadd_ayatha
         * */

        $lesson_name = trim($row['asm_alsor']);
        $ayat_count  = trim($row['aadd_ayatha']);

        $lesson = Lesson::query()->where('id', '=', $row['m'])->first();
        if (!$lesson){
            dd($row['m']);
        }
        $lesson->update([
            'ayat_count' => $ayat_count,
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
