<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = \App\User::query()->whereNotNull('class_number')->get()->toArray();

        $data_to_insert = [];

        foreach (array_chunk($users, 200) as $key => $result)
        {
            foreach ($result as $value){

                $date = Carbon::createFromDate(2022, 05, 1);
                for($i = 1; $i <= 5; $i++){

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
                        'class_number' => $value['class_number'],
                        'created_at' => $date->format('Y-m-d'),
                        'student_id' => $value['id'],
                        'date' => $date->format('l d-m-Y'),
                    ];

                    $date->addDay();
                }
            }

            DB::table('reports')->insert($data_to_insert);
            $data_to_insert = [];
        }

    }


}
