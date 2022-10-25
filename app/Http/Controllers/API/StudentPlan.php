<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Lesson;
use App\MonthlyScore;
use Illuminate\Http\Request;

class StudentPlan extends Controller
{
    public function lessons()
    {
        $search = request()->q;

        if($search == ''){
            $lessons = Lesson::select('id', 'name')->limit(10)->get();
        }else{
            $lessons = Lesson::orderby('name', 'asc')->select('id', 'name')->where('name', 'like', '%' . $search . '%')->limit(10)->get();
        }

        $response = [];
        foreach($lessons as $lesson){
            $response[] = [
                "id" => $lesson->id,
                "text" => $lesson->name
            ];
        }

        return response()->json($response);
    }

    public function ayat()
    {

        $ayat_count = Lesson::query()->findOrFail(request()->lesson_id);

        return response()->json($ayat_count);
    }

    public function update(Request $request)
    {
        $values = MonthlyScore::query()
            ->updateOrCreate([
                'user_id' => $request->student_id,
                'month_year' => $request->month_year,
            ],[
                'final_month_lesson_id'    => $request->final_month_lesson_id,
                'final_month_aya_id'       => $request->final_month_aya_id,
                'final_semester_lesson_id' => $request->final_semester_lesson_id,
                'final_semester_aya_id'    => $request->final_semester_aya_id,
                'final_year_lesson_id'     => $request->final_year_lesson_id,
                'final_year_aya_id'        => $request->final_year_aya_id,
                'seal_quran_date'          => $request->seal_quran_date,
            ]);

        return response()->json($values);
    }

}
