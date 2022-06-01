<?php

use App\DropoutStudent;
use Illuminate\Database\Seeder;

class DropoutStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = \App\User::query()->get();
        foreach ($students as $student){
            $student_reports = $student->reports()->orderBy('created_at', 'desc')->take(5)->get()->where('absence', '=', '-5');

            if ($student_reports->count() >= 5){
                foreach ($student_reports as $report){
                    DropoutStudent::query()->updateOrCreate([
                        'report_id' => $report->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'report_id' => $report->id,
                        'student_id' => $student->id,
                    ]);
                }
            }else{
                $student->dropoutStudents()->update([
                    'status' => 1
                ]);
            }

        }

    }
}
