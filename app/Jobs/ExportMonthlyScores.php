<?php

namespace App\Jobs;

use App\Exports\MonthlyScoresExport;
use App\Mail\ReportMail;
use App\MonthlyScoresFile;
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
    protected $email_to;

    function __construct($month_year, $mail_status, $file_name, $email_to) {
        $this->month_year = $month_year;
        $this->mail_status = $mail_status;
        $this->file_name = $file_name;
        $this->email_to = $email_to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        (new MonthlyScoresExport($this->month_year, $this->mail_status))->queue($this->file_name, 'public')->onQueue('ExportMonthlyScores')->allOnQueue('ExportMonthlyScores');

        MonthlyScoresFile::query()->create([
            'name' => $this->file_name,
            'email_to' => $this->email_to,
        ]);
    }
}
