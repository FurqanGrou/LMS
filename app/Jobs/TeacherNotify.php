<?php

namespace App\Jobs;

use App\ClassesTeachers;
use App\Http\Controllers\Teachers\ReportController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TeacherNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function handle()
    {
        ReportController::notifyTeacherFildReport($this->class);
    }
}
