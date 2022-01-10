<?php

namespace App\Jobs;

use App\Exports\MonthlyScoresExport;
use App\Mail\ReportMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class ExportMonthlyScores implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $month_year;
    protected $mail_status;
    protected $file_name;

    function __construct($month_year, $mail_status, $file_name) {
        $this->month_year = $month_year;
        $this->mail_status = $mail_status;
        $this->file_name = $file_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        (new MonthlyScoresExport($this->month_year, $this->mail_status))->queue($this->file_name);

        $data = ['link' => asset('storage/' . $this->file_name)];

        Mail::send('emails.admin.monthly_scores_job_mail', $data, function($message) {
            $message->to('lmsfurqan1@gmail.com')->subject('رابط تنزيل ملف النتيجة الشهرية');
        });


    }
}
