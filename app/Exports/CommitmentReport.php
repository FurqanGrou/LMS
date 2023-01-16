<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommitmentReport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, ShouldQueue
{

    protected $date_from;
    protected $date_to;
    protected $students;
    protected $commitment_type;

    public function __construct($request)
    {
        $this->date_from = $request['date_from'];
        $this->date_to = $request['date_to'];
        $this->students = $request['students'];
        $this->commitment_type = $request['commitment_type'];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $reports = DB::table('reports')
            ->select(
                DB::raw('DATE(reports.created_at)'),
                'users.student_number as student_number',
                'users.name as student_name',
                DB::raw('(CASE
                        WHEN users.section = "male" THEN "بنين"
                        ELSE "بنات"
                        END) AS user_section'),
                DB::raw('IF(reports.sitting_status = 1, "غير ملتزم", "ملتزم")'),
                DB::raw('IF(reports.camera_status = 1, "غير ملتزم", "ملتزم")'),
                'reports.entry_time as reports_entry_time',
                'reports.exit_time as reports_exit_time',
                'users.login_time as users_login_time',
                'users.exit_time as users_exit_time'
            )
            ->join('users', 'users.id', '=', 'reports.student_id')
            ->whereBetween('reports.created_at', [$this->date_from, $this->date_to]);

        if (in_array('camera', $this->commitment_type)){
            $reports->where('reports.camera_status', '=', '1');
        }

        if (in_array('sitting', $this->commitment_type)){
            $reports->where('reports.sitting_status', '=', '1');
        }

//        if (in_array('login_exit', $this->commitment_type)){
//            $reports->where('reports.entry_time', '<=', '1')
//                    ->where('reports.exit_time', '<=', '1');
//        }

        return $reports->get();
    }

    public function headings(): array
    {
        return [
            'التاريخ',
            'الرقم التسلسلي',
            'الاسم',
            'القسم',
            'الجلسة الصحيحة',
            'الكاميرا',
            'وقت الدخول',
            'وقت الخروج',
            'وقت الدخول الإفتراضي',
            'وقت الخروج الإفتراضي',
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
