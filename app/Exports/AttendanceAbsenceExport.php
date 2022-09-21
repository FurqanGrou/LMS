<?php

namespace App\Exports;

use App\AttendanceAbsenceRequests;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceAbsenceExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, ShouldQueue
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $appliedRequests = DB::table('attendance_absence_requests')
            ->select([
                'attendance_absence_requests.created_at as request_created_at',
                'attendance_absence_requests.date_excuse',
                'attendance_absence_requests.reason_excuse',
                'teachers.name',
                DB::raw('(CASE
                                        WHEN attendance_absence_requests.request_type = "absence" THEN "اذن غياب"
                                        WHEN attendance_absence_requests.request_type = "delay" THEN "اذن تأخير"
                                        WHEN attendance_absence_requests.request_type = "exit" THEN "اذن خروج"
                                        END)'),
                DB::raw('(CASE
                                        WHEN attendance_absence_requests.status = "pending" THEN "جديد"
                                        WHEN attendance_absence_requests.status = "processing" THEN "تمت المعالجة"
                                        WHEN attendance_absence_requests.status = "completed" THEN "انتهى"
                                        WHEN attendance_absence_requests.status = "canceled" THEN "ملغي"
                                        END)'),
            ])
            ->join('teachers', 'teachers.id', '=', 'attendance_absence_requests.teacher_id')
            ->orderByDesc('attendance_absence_requests.id')
            ->get();

        return $appliedRequests;
    }

    public function headings(): array
    {
        return [
            'تاريخ ووقت تقديم الطلب',
            'تاريخ العذر',
            'السبب - العذر',
            'مقدم الطلب',
            'نوع الاذن',
            'حالة الطلب',
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
