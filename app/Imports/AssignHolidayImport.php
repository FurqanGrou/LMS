<?php

namespace App\Imports;

use App\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AssignHolidayImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $section = $row['alksm'] == 'بنات' ? 'female' : 'male';

        //check if this student is exists or not
        $exists_student = User::where('student_number', '=', $row['rkm_altalb'])
            ->where('section', '=', $section)
            ->first();

        if ( !empty(trim($row['tarykh_bday_alanttham'])) ){
            $date = intval(trim($row['tarykh_bday_alanttham']));
            $start_regular_date = Date::excelToDateTimeObject($date)->format('Y-m-d');
        }

        $date_from = "2022-05-01";
        $date_from_time = new \DateTime($date_from);
        $day   = substr($date_from, -2);
        $month = substr($date_from, 5, 2);
        $year  = substr($date_from, 0, 4);

        //if exists is true update current student data
        if($exists_student){

            $date = Carbon::createFromDate($year, $month, $day);

            $date_to  = new \DateTime($start_regular_date);
            $interval = $date_from_time->diff($date_to);
            $days     = $interval->format('%a');

            DB::table("reports")
                ->where('student_id', '=', $exists_student->id)
                ->whereBetween('created_at', [$date_from, $start_regular_date])
                ->delete();

            $data_to_insert = [];
            for($i = 1; $i <= $days+1; $i++)
            {
                if ( !str_contains($date->format('l'), 'Friday') && !str_contains($date->format('l'), 'Saturday') ){
                    $data_to_insert[] = [
                        'new_lesson' => '-',
                        'new_lesson_from' => '-',
                        'new_lesson_to' =>  '-',
                        'last_5_pages' =>  '-',
                        'daily_revision' =>  '-',
                        'daily_revision_from' =>  '-',
                        'daily_revision_to' => '-',
                        'number_pages' =>  '-',
                        'lesson_grade' => 'غ',
                        'last_5_pages_grade' => '-',
                        'daily_revision_grade' => '-',
                        'behavior_grade' => '-',
                        'notes_to_parent' => 'دوام 3 أيام',
                        'absence' => '-1',
                        'total' => 0,
                        'mail_status' => 0,
                        'class_number' => $exists_student->class_number,
                        'created_at' => $date->format('Y-m-d'),
                        'student_id' => $exists_student->id,
                        'date' => $date->format('l d-m-Y'),
                    ];

                    if ($exists_student->student_number == '5558'){
                        var_dump($date->format('l d-m-Y'));
                    }
                }
                $date->addDay();
            }

            $test = DB::table('reports')->insert($data_to_insert);
            $data_to_insert = [];
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
