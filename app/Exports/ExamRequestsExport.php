<?php

namespace App\Exports;

use App\ExamRequest;
use App\Report;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExamRequestsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, ShouldQueue
{
    protected $date_from;
    protected $date_to;

    function __construct($date_from, $date_to) {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $exam_requests = DB::table('exam_requests')
            ->select('exam_requests.created_at',
                'users.name as student_name',
                'users.student_number as student_number',
                DB::raw('(CASE
                        WHEN users.section = "male" THEN "بنين"
                        ELSE "بنات"
                        END) AS user_section'),
                'users.path',
                'users.class_number',
                'classes.title',
                'users.login_time',
                DB::raw('(CASE
                        WHEN classes.period = 1 THEN "الفترة الصباحية"
                        WHEN classes.period = 2 THEN "الفترة المسائية الأولى"
                        WHEN classes.period = 3 THEN "الفترة المسائية الثانية"
                        WHEN classes.period = 4 THEN "الفترة المسائية الثالثة"
                        WHEN classes.period = 5 THEN "الفترة المسائية الرابعة"
                        END)'),
                'exam_requests.start_date',
                'exam_requests.end_date',
                'exam_requests.teacher_name',
                'teachers.name',
                'parts.name as part_name',
                'parts.number as part_number'
            )
            ->join('users', 'users.id', '=', 'exam_requests.user_id')
            ->join('parts', 'parts.id', '=', 'exam_requests.chapter_id')
            ->join('classes', 'classes.class_number', '=', 'users.class_number')
            ->join('teachers', 'teachers.id', '=', 'exam_requests.teacher_id');
//            ->whereBetween('exam_requests.created_at', [$this->date_from, $this->date_to]);

        return $exam_requests->get();
    }

    public function headings(): array
    {
        return [
            'اليوم والتاريخ',
            'اسم الطالب',
            'رقم الطالب',
            'القسم',
            'المسار',
            'رقم الحلقة',
            'اسم الحلقة',
            'وقت الدخول',
            'الفترة',
            'تاريخ بداية الاختبار',
            'تاريخ نهاية الاختبار',
            'اسم المعلم',
            'اسم مقدم الطلب',
            'اسم الجزء',
            'رقم الجزء',
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
