<?php

namespace App\Exports;

use App\Attendance;
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
        $attendances = Attendance::query()
                    ->select(['created_at', 'last_4_id', 'employee_number', 'full_name', 'section', 'type', 'period'])
                    ->whereBetween('created_at', [$this->date_from, $this->date_to]);

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
            'الفترة',
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
