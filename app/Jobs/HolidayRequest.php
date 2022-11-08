<?php

namespace App\Jobs;

use App\Events\ReportUpdated;
use App\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use App\Http\Controllers\Dashboard\ReportController;

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
     * @param $student_ids
     * @param $date_from
     * @param $date_to
     * @param $email_to
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

        $sql = '';
        foreach($students as $student){
            $date = Carbon::createFromDate($year, $month, $day);

            for($i = 1; $i <= $days+1; $i++){
                if(str_contains($date->format('l') ,'Friday')){
                    $date->addDays(2);
                    continue;
                }

                $sql .= "INSERT INTO reports (notes_to_parent, absence, date, student_id, created_at, class_number) VALUES ('دوام 3 أيام', '-1',";
                $sql .= " '" . $date->format('l d-m-Y') . "', " . $student->id . ", '" . $date->format('Y-m-d') . "', " . $student->class_number .") ON DUPLICATE KEY UPDATE notes_to_parent='دوام 3 أيام', absence='-1', created_at='" . $date->format('Y-m-d') . "', class_number=" . $student->class_number . "; ";

                $date->addDay();
            }

        }

        DB::unprepared($sql);

        foreach ($students as $student){
            $report_event_data = ['created_at' => $year . '-' . $month, 'student_id' => $student->id, 'class_number' => $student->class_number];
            $reportController = new ReportController;
            $request = new \Illuminate\Http\Request($report_event_data);
            $reportController->fireUpdateMonthlyScoresEvent($request);
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
