<?php

namespace App\Jobs;

use App\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HolidayRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $student_ids;
    protected $date_from;
    protected $date_to;
    protected $email_to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($student_ids, $date_from, $date_to, $email_to)
    {
        $this->student_ids = $student_ids;
        $this->date_from = $date_from;
        $this->date_to = $date_to;
        $this->email_to = $email_to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $day   = substr($this->date_from, -2);
        $month = substr($this->date_from, 5, 2);
        $year  = substr($this->date_from, 0, 4);

        $date_from = new \DateTime($this->date_from);
        $date_to  = new \DateTime($this->date_to);
        $interval = $date_from->diff($date_to);
        $days     = $interval->format('%a');

        $students = User::query();
        if ($this->student_ids[0] == '-1'){
            $students = $students->get();
        }else{
            $students = $students->whereIn('id', $this->student_ids)->get();
        }

        foreach($students as $student){
            $date = Carbon::createFromDate($year, $month, $day);

//            $last_report = Report::query()
//                ->where('student_id', '=', $student->id)
//                ->whereMonth('created_at', '=', $month)
//                ->whereYear('created_at', '=', $year)
//                ->latest()
//                ->first();

            for($i = 1; $i <= $days+1; $i++){
                $report = Report::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'date' => $date->format('l d-m-Y'),
                        'created_at' => $date->format('Y-m-d')
                    ],
                    [
                        'new_lesson' => '-',
                        'new_lesson_from' => '-',
                        'new_lesson_to' => '-',
                        'last_5_pages' => '-',
                        'daily_revision' => '-',
                        'daily_revision_from' => '-',
                        'daily_revision_to' => '-',
                        'number_pages' => '-',
                        'lesson_grade' => 'غ',
                        'last_5_pages_grade' => '-',
                        'daily_revision_grade' => '-',
                        'behavior_grade' => '-',
                        'notes_to_parent' => 'دوام 3 أيام',
                        'absence' => '-1',
                        'total' => 0,
                        'mail_status' => 0,
                        'class_number' => $student->class_number,
                    ]
                );
                $date->addDay();
            }
        }

        $data = [
            'students'  => $students->pluck('name')->toArray(),
            'date_from' => $this->date_from,
            'date_to'   => $this->date_to
        ];

        Mail::send('emails.admin.holidays_done', $data, function($message) {
            $message->to([$this->email_to])->bcc('lmsfurqan1@gmail.com')->subject(' إتمام عملية تعيين الاجازت للطلاب ');
        });

    }
}
