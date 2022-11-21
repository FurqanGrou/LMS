<?php

namespace App\Exports;

use App\Attendance;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;

class AttendanceExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, ShouldQueue
{
    protected $date_from;
    protected $date_to;
    protected $type;

    function __construct($date_from, $date_to, $type) {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
        $this->type = $type;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $attendances = DB::table('attendances')
            ->select('attendances.created_at',
                DB::raw('(CASE
                        WHEN attendances.admin_id IS NULL THEN teachers.teacher_number
                        ELSE admins.employee_number
                        END) AS employee_number'),
                DB::raw('(CASE
                        WHEN attendances.admin_id IS NULL THEN teachers.last_4_id
                        ELSE admins.last_4_id
                        END) AS last_4_id'),
                DB::raw('(CASE
                        WHEN attendances.admin_id IS NULL THEN teachers.name
                        ELSE admins.name
                        END) AS employee_name'),
                DB::raw('(CASE
                        WHEN attendances.admin_id IS NULL and teachers.section = "male" THEN "بنين"
                        WHEN attendances.admin_id IS NULL and teachers.section = "female" THEN "بنات"
                        WHEN attendances.teacher_id IS NULL and admins.section = "male" THEN "بنين"
                        WHEN attendances.teacher_id IS NULL and admins.section = "female" THEN "بنات"
                        END) AS employee_section'),
                DB::raw('(CASE
                        WHEN attendances.type = "login" THEN "دخول"
                        WHEN attendances.type = "logout" THEN "خروج"
                        END) AS type'),
                DB::raw('(CASE
                        WHEN attendances.type = "login" THEN "1"
                        WHEN attendances.type = "logout" THEN "2"
                        END) AS flag'),
                'period',
                DB::raw('(CASE
                        WHEN attendances.admin_id IS NULL THEN "معلم"
                        ELSE "إداري"
                        END) AS job_title')
            )
            ->leftJoin('admins', 'admins.id', '=', 'attendances.admin_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'attendances.teacher_id');

        if($this->type != '-1'){
            $attendances->where('type', '=', $this->type);
        }

        return $attendances->get();
    }

    public function headings(): array
    {
        return [
            'التاريخ و الوقت',
            'رقم الهوية (آخر 4 أرقام)',
            'رقم الموظف',
            'الاسم',
            'القسم',
            'الحالة',
            'flag',
            'الفترة',
            'المسمى الوظيفي',
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
