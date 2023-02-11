<?php

namespace App\Exports;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class RegularStudentsReport implements WithHeadings, WithMapping, WithStyles, FromQuery, ShouldAutoSize, ShouldQueue, WithStrictNullComparison
{
    use Exportable;

    protected $date_from;
    protected $date_to;
    protected $total_study_days;

    protected $parse_from;
    protected $parse_to;

    public function __construct($request)
    {
        $this->date_from = $request['date_from'];
        $this->date_to = $request['date_to'];

        $this->parse_from = Carbon::createFromFormat('Y-m-d', $this->date_from);
        $this->parse_to = Carbon::createFromFormat('Y-m-d', $this->date_to);
        $this->total_study_days = $this->parse_to->diffInDays($this->parse_from)+1;
    }

    public function query()
    {
        return User::query();
    }

    public function headings(): array
    {

        return [
            'تاريخ إصدار التقرير',
            'الرقم التسلسلي',
            'الطالب',
            'القسم',
            'المسار',
            'الفصل الدراسي',
            'تاريخ الإنتظام', // اول تاريخ في السيستم
            'تاريخ الإنقطاع', // من برنامج الفرقان
            'أيام الدراسة الفعلية لكل طالب', // هي الايام التي كان الطالب فيها منتظم بين التاريخين
            // if(H between from and to) I = J - K else I = J
            'مجموع أيام الدراسة',
            'مجموع أيام اخر انقطاع',
            // if(H between from and to) K = (date for first report after H) - H else 0
        ];
    }

    public function map($user): array
    {
        $current_date = Carbon::now()->toDate()->format('Y-m-d');
        $actual_study_days = $this->total_study_days;
        $dropout_date = Carbon::parse($user->dropout_date);
        $dropout_days = 0;

        // if(H between from and to) K = (date for first report after H) - H else 0
        if($dropout_date->between($this->parse_from, $this->parse_to)){
            $last_dropout_date = $user->reports()->whereDate('created_at', '>', $dropout_date)->whereIn('absence', ['0', '-1'])->first()->created_at ?? null;
            if ($last_dropout_date){
                $dropout_days = $dropout_date->diffInDays($last_dropout_date);
                $actual_study_days = $this->total_study_days - $dropout_days;
            }
        }

        return [
            $current_date,
            $user->student_number,
            $user->name,
            $user->section,
            $user->path,
            'الفصل الدراسي الثاني',
            @$user->reports()->first()->created_at ?? '-',
            $user->dropout_date ?? '-',
            $actual_study_days,
            $this->total_study_days,
            $dropout_days,
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
