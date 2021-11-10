<?php

namespace App\Imports;

use App\Lesson;
use App\LessonPage;
use App\NooraniaPage;
use App\Part;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LessonImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new NooraniaPage([
            'serial_title' => $row['serial_title'],
            'lesson_number' => $row['lesson_number'],
            'lesson_title' => $row['lesson_title'],
            'page_number' => $row['page_number'],
        ]);
    }
}
