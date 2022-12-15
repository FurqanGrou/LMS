<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Lesson;
use App\PlanForcast;
use App\QuranLine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentPlan extends Controller
{
    public $current;
    public $avg_memories;

    public function __construct()
    {
        $this->current = Carbon::today();
        $this->avg_memories = 7;
    }

    public function lessons()
    {
        $search = request()->q;

        if($search == ''){
            $lessons = Lesson::select('id', 'name')->limit(10)->get();
        }else{
            $lessons = Lesson::orderby('name', 'asc')->select('id', 'name')->where('name', 'like', '%' . $search . '%')->limit(10)->get();
        }

        $response = [];

        if (\request()->type == 'calc'){
            foreach($lessons as $lesson){
                $response[] = [
                    "id" => $lesson->name,
                    "text" => $lesson->name
                ];
            }
        }else{
            foreach($lessons as $lesson){
                $response[] = [
                    "id" => $lesson->id,
                    "text" => $lesson->name
                ];
            }
        }

        return response()->json($response);
    }

    public function ayat()
    {

        $ayat_count = Lesson::query()->findOrFail(request()->lesson_id);

        return response()->json($ayat_count);
    }

    public function update($data)
    {
        $values = PlanForcast::query()
            ->updateOrCreate([
                'user_id'    => $data['student_id'],
                'month_year' => $data['month_year'],
            ],[
                'month_lesson'    => $data['month_lesson'],
                'month_aya'       => $data['month_aya'],
                'semester_lesson' => $data['semester_lesson'],
                'semester_aya'    => $data['semester_aya'],
                'year_lesson'     => $data['year_lesson'],
                'year_aya'        => $data['year_aya'],
                'seal_quran_date' => $data['seal_quran_date'],
            ]);

        return response()->json($values);
    }

    public function calc(Request $request)
    {
        $lesson     = Lesson::query()->where('id', '=', $request->lesson_id)->first();
        $quran_line = QuranLine::query()
                ->where('lesson', '=', $lesson->name)
                ->where('aya_num', '=', $request->aya_num)
                ->first();

        // معدل الانجاز في نهاية الشهر
        $month_result = $this->getResultOfMonth($quran_line);

        // معدل الانجاز في نهاية الفصل
        $semester_result = $this->getResultOfSemester($quran_line);

        // معدل الانجاز في نهاية العام
        $year_result = $this->getResultOfYear($quran_line);

        // موعد حفظ المصحف كامل
        $final_date = $this->getResultOfQuran($quran_line);

        $data = [
            'student_id'      => $request->student_id,
            'month_year'      => $request->month_year,
            'month_lesson'    => $month_result->lesson,
            'month_aya'       => $month_result->aya_num,
            'semester_lesson' => $month_result->lesson,
            'semester_aya'    => $month_result->aya_num,
            'year_lesson'     => $month_result->lesson,
            'year_aya'        => $month_result->aya_num,
            'seal_quran_date' => $final_date,
        ];

        $this->update($data);

        return [
            'month' => $month_result,
            'semester' => $semester_result,
            'year' => $year_result,
            'final_date' => $final_date,
        ];
    }

    public function getResultOfMonth($quran_line)
    {
        // معدل الانجاز في نهاية الشهر

        //نحتاج نعرف ماهو الشهر الحالي

        //نحتاج نعرف الايام المتبقية من الشهر الحالي
        $next_month = Carbon::createFromFormat('m/d/Y', $this->current->month."/01/".$this->current->year)->addMonth()->format('m/d/Y');

        $remaining_days = $this->current->diffInDaysFiltered(function(Carbon $date) {
            return !$date->isFriday() && !$date->isSaturday();
        }, $next_month);

        //معدل الحفظ هو 8 سطور في اليوم الواحد $avg_memories

        // نقوم بعمل ضرب لعدد الايام المتبقية ب8
        $total_lines = $remaining_days*$this->avg_memories;

        // نستمر في عملية جمع عدد السطور الى ان لا يزيد المجموع عن القيمة أعلاه
        $quran_lines = QuranLine::query()
            ->where('id', '>=', $quran_line->id)
            ->get();

        $quran_lines_sum = 0;
        $result = null;
        foreach ($quran_lines as $line){
            if ($quran_lines_sum < $total_lines){
                $quran_lines_sum+=$line->aya_length;
                $result = $line;
            }else{
                break;
            }
        }

        return $result;
    }

    public function getResultOfSemester($quran_line)
    {

        //نحتاج نعرف ما هو الشهر الحالي

        // نحتاج نعرف ما هو الشهر الاخير في هذا الفصل
        $last_semester_month = $this->getSemesterName();

        if ($last_semester_month == $this->current->month){
            return $this->getResultOfMonth($quran_line);
        }

        //نحتاج نعرف الايام المتبقية من الفصل الحالي
        $last_day_in_this_month = Carbon::createFromDate($this->current->year, $last_semester_month)->lastOfMonth();

        $remaining_days = $this->current->diffInDaysFiltered(function(Carbon $date) {
            return !$date->isFriday() && !$date->isSaturday();
        }, $last_day_in_this_month);

        //معدل الحفظ هو 8 سطور في اليوم الواحد $avg_memories

        // نقوم بعمل ضرب لعدد الايام المتبقية ب8
        $total_lines = $remaining_days*$this->avg_memories;

        // نستمر في عملية جمع عدد السطور الى ان لا يزيد المجموع عن القيمة أعلاه
        $quran_lines = QuranLine::query()
            ->where('id', '>=', $quran_line->id)
            ->get();

        $quran_lines_sum = 0;
        $result = null;
        foreach ($quran_lines as $line){
            if ($quran_lines_sum < $total_lines){
                $quran_lines_sum+=$line->aya_length;
                $result = $line;
            }else{
                break;
            }
        }

        return $result;
    }

    public function getResultOfYear($quran_line)
    {
        // معدل الانجاز في نهاية السنة

        //نحتاج نعرف ماهو الشهر الحالي current

        //نحتاج نعرف الايام المتبقية من السنة الحالبة
        $last_day_in_this_year = Carbon::createFromDate($this->current->year, "12")->lastOfMonth();

        $remaining_days = $this->current->diffInDaysFiltered(function(Carbon $date) {
            return !$date->isFriday() && !$date->isSaturday();
        }, $last_day_in_this_year);

        //معدل الحفظ هو 8 سطور في اليوم الواحد $avg_memories

        // نقوم بعمل ضرب لعدد الايام المتبقية ب8
        $total_lines = $remaining_days*$this->avg_memories;

        // نستمر في عملية جمع عدد السطور الى ان لا يزيد المجموع عن القيمة أعلاه
        $quran_lines = QuranLine::query()
            ->where('id', '>=', $quran_line->id)
            ->get();

        $quran_lines_sum = 0;
        $result = null;
        foreach ($quran_lines as $line){
            if ($quran_lines_sum < $total_lines){
                $quran_lines_sum+=$line->aya_length;
                $result = $line;
            }else{
                break;
            }
        }

        return $result;
    }

    public function getResultOfQuran($quran_line)
    {
        $quran_lines = QuranLine::query()
            ->where('id', '>=', $quran_line->id)
            ->sum('aya_length');

        $number_days = ceil($quran_lines/$this->avg_memories);

        $final_date = Carbon::today()->addDays($number_days);

        $days = $this->current->diffInDaysFiltered(function (Carbon $date){
            return $date->isSaturday() || $date->isFriday();
        }, $final_date);

        return $final_date->addDays($days)->format('d/m/Y');
    }

    public function getSemesterName()
    {
        $first_semester  = ['01', '02', '03', '04'];
        $second_semester = ['05', '06', '07', '08'];
        $third_semester  = ['09', '10', '11', '12'];

        if (in_array($this->current, $first_semester)){
            return '04';
        }

        if (in_array($this->current, $second_semester)){
            return '08';
        }

        if (in_array($this->current, $third_semester)){
            return '12';
        }

    }
}
