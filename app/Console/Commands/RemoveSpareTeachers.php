<?php

namespace App\Console\Commands;

use App\AttendanceAbsenceRequests;
use App\ClassesTeachers;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveSpareTeachers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:spareTeachers';

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
        $today = Carbon::today()->format('Y-m-d');

        $attendance_absence_requests = AttendanceAbsenceRequests::query()->where([
            ['status', '=', 'processing'],
            ['available_to_date', '<=', $today]
        ])->pluck('class_numbers')->toArray();

        $class_numbers = [];
        foreach ($attendance_absence_requests as $key => $class_number){
            $class_numbers = array_merge($class_numbers, json_decode($class_number));
        }

        $classes_teachers = ClassesTeachers::query()
            ->whereIn('class_number', $class_numbers)
            ->where('role', '=', 'spare')
            ->get();

        \Log::info($classes_teachers);
    }
}
