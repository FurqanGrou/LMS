<?php

namespace App\Imports;

use App\Lesson;
use App\LessonPage;
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
        $part = Part::query()->where('name', '=', $row['part_title'])->first();
        return new LessonPage([
            'part_id' => $part->id,
            'lesson_number' => $row['lesson_number'],
            'lesson_title' => $row['lesson_title'],
            'start_page_number' => $row['start_page_number'],
            'end_page_number' => $row['end_page_number'],
            'page_number' => $row['page_number'],
        ]);
    }
}
