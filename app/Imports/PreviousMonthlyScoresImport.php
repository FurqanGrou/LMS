<?php

namespace App\Imports;

use App\Classes;
use App\ClassesTeachers;
use App\Teacher;
use App\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class PreviousMonthlyScoresImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{

    public $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        /*
         * rkm_altalb = رقم الطالب
         * altalb = اسم الطالب
         * alrabt = رابط الحلقة
         * okt_aldkhol = وقت الدخول
         * almsar = مسار الحلقة
         * rkm_alhlk = رقم الحلقة
         * alhlk = اسم الحلقة
         * rkm_almaalm = رقم المعلم
         * almaalm = اسم المعلم
         * alftr = الفترة
         * odaa_altalb = حالة الطالب
         * alksm = القسم
         * goal_alab = هاتف الأب
         * goal_alam = هاتف الأم
         * bryd_alab = بريد الأب
         * bryd_alam = بريد الأم
         * bryd_almaalm = بريد المعلم
         * alaamr = العمر
         * allgh = اللغة
         * dol_alakam = دولة الاقامة
         * tarykh_almylad = تاريخ الميلاد
         * rabt_aldlyl_alsnoy = رابط الدليل السنوي
         * rkm_almaalm_almsaaad = رقم المعلم المساعد
         * asm_almaalm_almsaaad = اسم المعلم المساعد
         * bryd_almaalm_almsaaad = بريد المعلم المساعد
         * rkm_almshrf = رقم المشرف
         * asm_almshrf = اسم المشرف
         * bryd_almshrf = بريد المشرف
         *
         *
         * */

//Students
        if (!is_null($row['rkm_altalb']) && !is_null($row['altalb'])) {

            $section = $row['alksm'] == 'بنات' ? 'female' : 'male';
            $class_number = trim($row['rkm_alhlk']);
            $month = $this->month;

            //check if this student is exists or not
            $exists_student = User::where('student_number', '=', $row['rkm_altalb'])->where('section', '=', $section)->first();

            //if exists is true update current monthly scores data
            if ($exists_student) {
                DB::table('monthly_scores')->updateOrInsert(
                    [
                        'user_id' => $exists_student->id,
                        'month_year' => "2021-" . $month,
                    ],
                    [
                        'class_number' => $class_number,
                    ]
                );
            }

        }

    }

    public function batchSize(): int
    {
        return 300;
    }

    public function chunkSize(): int
    {
        return 300;
    }

}
