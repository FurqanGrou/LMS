<?php

namespace App\Console\Commands;

use App\Http\Controllers\Teachers\ReportController;
use Illuminate\Console\Command;

class sendTeacherNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teacher:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ReportController::checkReportNotSent();
    }
}
