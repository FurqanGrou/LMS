<?php

namespace App\Imports;

use App\Classes;
use App\ClassesTeachers;
use App\Teacher;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class UsersImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public $count = 0;
    public $students_count = 0;

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
        if(!is_null($row['rkm_altalb']) && !is_null($row['altalb'])){

            //change class number for all students
            if($this->students_count == 0){
                $this->students_count++;
                $section = ($row['alksm'] == 'بنات') ? 'female' : 'male';
                User::query()->where('section', '=', $section)->update(['class_number' => null]);
            }

            $section = $row['alksm'] == 'بنات' ? 'female' : 'male';
            //check if this student is exists or not
            $exists_student = User::where('student_number', '=', $row['rkm_altalb'])->where('section', '=', $section)->first();

            $father_email = str_replace(' ', '', $row['bryd_alab']);
            $mother_email = str_replace(' ', '', $row['bryd_alam']);
            $path = trim($row['almsar']);
            $name = trim($row['altalb']);

            //if exists is true update current student data
            if($exists_student){

                $exists_student->update([
                    'class_number'    => $row['rkm_alhlk'],
                    'name'    => $name,
                    'section'         => $section,
                    'login_time'      => $row['okt_aldkhol'],
                    'father_mail'     => $father_email,
                    'mother_mail'     => $mother_email,
                    'path'            => $path,
                    'status'          => $row['odaa_altalb'],
                ]);

            }else{

                //if exists is false insert new student data
                $user = User::create([
                    'student_number'  => $row['rkm_altalb'],
                    'name'            => $name,
                    'father_phone'    => $row['goal_alab'],
                    'mother_phone'    => $row['goal_alam'],
                    'father_mail'     => $father_email,
                    'mother_mail'     => $mother_email,
                    'language'        => $row['allgh'],
                    'status'          => $row['odaa_altalb'],
                    'section'         => $row['alksm'] == 'بنات' ? 'female' : 'male' ,
                    'login_time'      => $row['okt_aldkhol'],
                    'path'            => $path,
                    'password'        => \Hash::make('12345'),
                    'class_number'    => $row['rkm_alhlk'],
                ]);

            }

        }

//Teachers
        if(!is_null($row['bryd_almaalm']) && !is_null($row['rkm_almaalm']) && !is_null($row['almaalm'])){

            //check if this teacher is exists or not
            $email = str_replace(' ', '', $row['bryd_almaalm']);
            $exists_teacher = Teacher::where('email', '=', $email)->first();
            $teacher_name = trim($row['almaalm']);
            //if exists is true update current teacher data
            if($exists_teacher){

                $exists_teacher->update([
                    'name'      => $teacher_name,
                    'section'   => $row['alksm'] == 'بنات' ? 'female' : 'male',
                    'last_4_id'         => $row['akhr_4_arkam_mn_alhoy_llmaalm'] ?? '00',
                ]);

            }else{

                //if exists is false insert new teacher data
                $teacher = Teacher::create([
                    'teacher_number'    => $row['rkm_almaalm'],
                    'name'      => $teacher_name,
                    'email'             => $email,
                    'password'          => \Hash::make('12345'),
                    'section'           => $row['alksm'] == 'بنات' ? 'female' : 'male',
                    'last_4_id'         => $row['akhr_4_arkam_mn_alhoy_llmaalm'] ?? '00',
                ]);

            }

        }

//Supervisors
        if(!is_null($row['bryd_almshrf']) && !is_null($row['rkm_almshrf']) && !is_null($row['asm_almshrf'])){

            //check if this supervisor is exists or not
            $email = str_replace(' ', '', $row['bryd_almshrf']);
            $exists_supervisor = Teacher::where('email', '=', $email)->first();
            $supervisor_name = trim($row['asm_almshrf']);
            //if exists is true update current supervisor data
            if($exists_supervisor){

                $exists_supervisor->update([
                    'name'      => $supervisor_name,
                    'section'   => $row['alksm'] == 'بنات' ? 'female' : 'male',
                    'last_4_id'         => '00',
                ]);

            }else{

                //if exists is false insert new supervisor data
                $supervisor = Teacher::create([
                    'teacher_number'    => $row['rkm_almshrf'],
                    'name'      => $supervisor_name,
                    'email'             => $email,
                    'password'          => \Hash::make('12345'),
                    'section'           => $row['alksm'] == 'بنات' ? 'female' : 'male',
                    'last_4_id'         => '00',
                ]);

            }

        }

//Classes
        if(!is_null($row['rkm_alhlk']) && is_numeric($row['rkm_alhlk'])){

            //check if this class is exists or not
            $exists_class = Classes::where('class_number', '=', $row['rkm_alhlk'])->first();

            //if exists is true update current class data
            if($exists_class){

                $exists_class->update([
                    'title'     => $row['alhlk'],
                    'zoom_link' => $row['alrabt'],
                    'path'      => $row['almsar'],
                    'period'    => $row['alftr'],
                ]);

            }else{

                //if exists is false insert new class data
                $class = Classes::create([
                    'class_number' => $row['rkm_alhlk'],
                    'title'     => $row['alhlk'],
                    'zoom_link' => $row['alrabt'],
                    'path'      => $row['almsar'],
                    'period'    => $row['alftr'],
                ]);

            }

        }

//Classes_Teachers
        if($this->count == 0){
            $section = ($row['alksm'] == 'بنات') ? 'female' : 'male';
            ClassesTeachers::getQuery()->where('type', '=', $section)->delete();
        }

        ++$this->count;

        if(!is_null($row['rkm_alhlk']) && is_numeric($row['rkm_alhlk'])){
            $type = ($row['alksm'] == 'بنات') ? 'female' : 'male';

            //check if this class_teacher is exists or not
            $teacher_email    = str_replace(' ', '', $row['bryd_almaalm']);
            $supervisor_email = str_replace(' ', '', $row['bryd_almshrf']);

            $exists_class_teacher = ClassesTeachers::where('class_number', '=', $row['rkm_alhlk'])
                ->where('teacher_email', '=', $teacher_email)
                ->where('role', '=', 'main')
                ->first();

            //if exists is true update current class_teacher data (teacher class)
            if(!$exists_class_teacher){

                //if exists is false insert new class_teacher data
                $class_teacher = ClassesTeachers::create([
                    'class_number'  => $row['rkm_alhlk'],
                    'teacher_email' => $teacher_email,
                    'type'          => $type,
                    'role'          => 'main',
                ]);

            }

            $exists_class_supervisor = ClassesTeachers::where('class_number', '=', $row['rkm_alhlk'])
                ->where('teacher_email', '=', $supervisor_email)
                ->where('role', '=', 'supervisor')
                ->first();

            //if exists is true update current class_teacher data (class supervisor)
            if(!$exists_class_supervisor){

                //if exists is false insert new class_teacher data
                $class_teacher = ClassesTeachers::create([
                    'class_number'  => $row['rkm_alhlk'],
                    'teacher_email' => $supervisor_email,
                    'type'          => $type,
                    'role'          => 'supervisor',
                ]);

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
