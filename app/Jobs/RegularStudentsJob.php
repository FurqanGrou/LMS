<?php

namespace App\Jobs;

use App\Exports\RegularStudentsReport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegularStudentsJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($request){
        $this->data = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new RegularStudentsReport($this->data))->queue('public/regular-students-reports/' . $this->data['file_name'])->onQueue('export-regular-students')->allOnQueue('export-regular-students');
    }
}
