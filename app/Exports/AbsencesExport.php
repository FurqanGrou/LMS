<?php

namespace App\Exports;

use App\Report;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;

class AbsencesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, ShouldQueue
{
    protected $date_from;
    protected $date_to;
    protected $absence_status;

    function __construct($date_from, $date_to, $absence_status) {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
        $this->absence_status = $absence_status;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $reports = DB::table('reports')
                    ->select('reports.date',
                        'users.student_number as student_number',
                        DB::raw('(CASE
                        WHEN users.section = "male" THEN "1"
                        ELSE "2"
                        END) AS user_section'),
                        DB::raw('(CASE
                        WHEN reports.absence = "-2" THEN "-2"
                        ELSE "-5"
                        END) AS absence')
                    )
                    ->join('users', 'users.id', '=', 'reports.student_id')
                    ->whereBetween('reports.created_at', [$this->date_from, $this->date_to]);

        if($this->absence_status == -1){
            $reports->where('reports.absence', '!=', '0');
        }else{
            $reports->where('reports.absence', '=', $this->absence_status);
        }

        return $reports->get();
    }

    public function headings(): array
    {
        return [
            'date',
            'student_number',
            'section',
            'absenteeism_type',
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
