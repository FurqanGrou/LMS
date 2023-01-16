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

class MonthlyScoresExport implements WithHeadings, WithStyles, ShouldAutoSize, ShouldQueue, WithStrictNullComparison, FromQuery
{
    use Exportable;

    protected $month_year;
    protected $mail_status;
    protected $study_type;

    function __construct($month_year, $mail_status, $study_type) {
        $this->month_year  = $month_year;
        $this->mail_status = $mail_status;
        $this->study_type = $study_type;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {

        $study_type = $this->study_type;
//        Log::info($this->study_type);
//
//        Log::info($study_type);

        $monthly_scores = DB::table('monthly_scores')
            ->select([
                DB::raw('substr(month_year, -2)'),
                'users.name as student_name',
                'users.student_number',
//                DB::raw('(CASE
//                                        WHEN users.section = "male" THEN "بنين"
//                                        ELSE "بنات"
//                                        END) as student_section'),
                DB::raw('(CASE
                                        WHEN users.study_type = "0" and users.section = "male" THEN "بنين اونلاين"
                                        WHEN users.study_type = "0" and users.section = "female" THEN "بنات اونلاين"
                                        WHEN users.study_type = "1" and users.section = "male" THEN "بنين حضوري"
                                        WHEN users.study_type = "1" and users.section = "female" THEN "بنات حضوري"
                                        END) as study_type
                                        '),
                DB::raw('(CASE
                                        WHEN classes.period = 1 THEN "الفترة الصباحية"
                                        WHEN classes.period = 2 THEN "الفترة المسائية الأولى"
                                        WHEN classes.period = 3 THEN "الفترة المسائية الثانية"
                                        WHEN classes.period = 4 THEN "الفترة المسائية الثالثة"
                                        WHEN classes.period = 5 THEN "الفترة المسائية الرابعة"
                                        END)'),
                'teachers.name as teacher_name',
                'teachers.teacher_number as teacher_number',
                'new_lessons_not_listened',
                'last_five_pages_not_listened',
                'daily_revision_not_listened',
                'absence_excuse_days',
                'absence_unexcused_days',
                'avg',
                'monthly_scores.path',
                'monthly_scores.class_number',
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
                                        END) AS page_number'),
                DB::raw('substr(month_year, 1, 4)')
            ])
            ->join('users', 'users.id', '=', 'monthly_scores.user_id')
            ->join('classes', 'classes.class_number', '=', 'monthly_scores.class_number')
            ->join('classes_teachers', 'classes_teachers.class_number', '=', 'monthly_scores.class_number')
            ->join('teachers', 'teachers.email', '=', 'classes_teachers.teacher_email')
            ->leftJoin('lesson_pages', 'monthly_scores.lesson_page_id', '=', 'lesson_pages.id')
            ->leftJoin('noorania_pages', 'monthly_scores.noorania_page_id', '=', 'noorania_pages.id')
            ->where('month_year', '=', $this->month_year)
            ->where('classes_teachers.role', '=', 'main');

            if ($study_type == 'iksab'){
                $monthly_scores->where('users.study_type', '=', '1');
            }elseif($study_type == 'furqan_group'){
                $monthly_scores->where('users.study_type', '=', '0');
            }

        if($this->mail_status != '-1'){
            $monthly_scores->where('monthly_scores.mail_status', '=', $this->mail_status);
        }

        return $monthly_scores->orderBy('monthly_scores.id');
    }

    public function headings(): array
    {
        return [
            'الشهر',
            'اسم الطالب',
            'رقم الطالب',
            'القسم',
            'الفترة',
            'اسم المعلم',
            'رقم المعلم',
            'عدد مرات عدم تسميع الدرس الجديد',
            'عدد مرات عدم تسميع اخر 5 صفحات',
            'عدد مرات عدم تسميع المراجعة اليومية',
            'عدد مرات الغياب بعذر',
            'عدد مرات الغياب بدون عذر',
            'النتيجة',
            'المسار',
            'رقم الحلقة',
            'التقدير',
            'عنوان الدرس',
            'رقم الصفحة',
            'السنة',
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
