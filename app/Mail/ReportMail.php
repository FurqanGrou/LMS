<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($details, $pdf)
    {
        $this->details = $details;
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(is_null($this->details['grades'])){

            return $this->subject($this->details['subject'])
                        ->view('teachers.emails.new_report_mail')
                        ->attachData($this->pdf->output(), 'monthly-report.pdf', [
                            'mime' => 'application/pdf',
                        ]);
        }

        return $this->subject($this->details['subject'])
                    ->view('teachers.emails.report_mail')
                    ->attachData($this->pdf->output(), 'monthly-report.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

}
