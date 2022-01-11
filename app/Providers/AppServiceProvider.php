<?php

namespace App\Providers;

use App\MonthlyScoresFile;
use Carbon\Carbon;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setWeekendDays([
            Carbon::FRIDAY,
            Carbon::SATURDAY,
        ]);

        Queue::after(function (JobProcessed  $event) {
            if ($event->job->resolveName() == 'Maatwebsite\Excel\Jobs\StoreQueuedExport') {
                $exported_file = MonthlyScoresFile::query()->first();
                if (!is_null($exported_file)){
                    $data = ['link' => url('storage/' . $exported_file->name)];
                    Mail::send('emails.admin.monthly_scores_job_mail', $data, function($message) use ($exported_file) {
                        $message->to('lmsfurqan1@gmail.com')->subject(' رابط تنزيل ملف النتيجة الشهرية ' . $exported_file->name);
                    });
                    MonthlyScoresFile::query()->where('name', '=', $exported_file->name)->delete();
                }
            }
        });
    }
}
