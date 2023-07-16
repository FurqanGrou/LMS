<?php

namespace App\Exports;

use App\MonthlyScore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromQuery;

class OneTimeExport implements WithHeadings, WithStyles, ShouldAutoSize, ShouldQueue, WithStrictNullComparison, FromQuery
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {

        $students = [
            1260,
            1354,
            2081,
            2088,
            2169,
            2342,
            2525,
            2667,
            2691,
            2699,
            2767,
            2768,
            2956,
            3189,
            3232,
            3509,
            3725,
            3888,
            3908,
            3909,
            3912,
            3917,
            3918,
            3919,
            3920,
            3922,
            3925,
            4082,
            4134,
            4235,
            4240,
            4247,
            4384,
            4390,
            4397,
            4441,
            4450,
            4497,
            4522,
            4604,
            4620,
            4634,
            4678,
            4690,
            4696
        ];

        $monthly_scores = DB::table('monthly_scores')
            ->select([
                DB::raw('substr(month_year, -2)'),
                'users.student_number',
                'users.name as student_name',
                'new_lessons_not_listened',
                'last_five_pages_not_listened',
                'daily_revision_not_listened',
                'absence_excuse_days',
                'absence_unexcused_days',
                'avg',
                DB::raw('(CASE
                                        WHEN avg >= 90 THEN "Excellent - ممتاز"
                                        WHEN avg >= 80 THEN "Very Good - جيد جداً"
                                        WHEN avg >= 70 THEN "Good - جيد"
                                        WHEN avg >= 60 THEN "Pass - مقبول"
                                        WHEN avg < 60 THEN "Low - ضعيف"
                                        ELSE "ضعيف"
                                        END) AS rate'),
                DB::raw('(CASE
                                        WHEN lesson_pages.lesson_title IS NULL THEN noorania_pages.lesson_title
                                        ELSE lesson_pages.lesson_title
                                        END) AS lesson_title'),
                DB::raw('(CASE
                                        WHEN lesson_pages.page_number IS NULL THEN noorania_pages.page_number
                                        ELSE lesson_pages.page_number
                                        END) AS page_number')
            ])
            ->join('users', 'users.id', '=', 'monthly_scores.user_id')
            ->leftJoin('lesson_pages', 'monthly_scores.lesson_page_id', '=', 'lesson_pages.id')
            ->leftJoin('noorania_pages', 'monthly_scores.noorania_page_id', '=', 'noorania_pages.id')
            ->where('month_year', '=', '2023-02')
            ->where('users.study_type', '=', '0')
            ->where('users.section', '=', 'female')
            ->whereIn('users.student_number', $students);

        return $monthly_scores->orderBy('users.student_number');
    }

    public function headings(): array
    {
        return [
            'الشهر',
            'رقم الطالب',
            'اسم الطالب',
            'الدرس الجديد',
            'اخر 5 صفحات',
            'المراجعة اليومية',
            'الغياب بعذر',
            'الغياب بدون عذر',
            'النسبة',
            'التقدير',
            'عنوان الدرس',
            'رقم الصفحة',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
        ];
    }

}
