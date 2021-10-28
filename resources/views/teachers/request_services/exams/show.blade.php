@extends('teachers.layouts.master')

@section('content')

@include('teachers.partials.errors')
@include('teachers.partials.success')

<form class="form" method="POST" >

    <div class="form-body">
        <h4 class="form-section"><i class="ft-user"></i> طلب الخدمة - طلب اختبار</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="student_id" class="w-100">اسم الطالب</label>
                    <input type="text" class="form-control" value="{{ \App\User::find($values['student_id'])->name }}" disabled >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="chapter_name" class="w-100">اسم الجزء</label>
                    <input type="text" class="form-control" value="{{ $values['chapter_name'] }}" disabled >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="chapter_name" class="w-100">تاريخ البداية</label>
                    <input type="text" class="form-control" value="{{ $values['start_date'] }}" disabled >
                </div>
            </div>

            <label for="end_date" class="w-100">تاريخ النهاية</label>
            <input type="text" value="{{ $values['end_date'] }}" disabled >

        </div>
    </div>


    <div class="form-body">
        <h4 class="form-section"><i class="ft-lock"></i>طلب الخدمة - طلب اختبار</h4>

        <div class="row">
            <div class="col-md-12">

                {{-- student_id--}}
                <div class="form-group">
                    <label for="student_id" class="w-100">اسم الطالب</label>
                    <input type="text" value="{{ \App\User::find($values['student_id'])->name }}" disabled >
                </div>

                {{-- student_id--}}
                <div class="form-group">
                    <label for="chapter_name" class="w-100">اسم الجزء</label>
                    <input type="text" value="{{ $values['chapter_name'] }}" disabled >
                </div>

                {{-- start_date--}}
                <div class="form-group">
                    <label for="start_date" class="w-100">تاريخ البداية</label>
                    <input type="text" value="{{ $values['start_date'] }}" disabled >
                </div>

                {{-- end_date--}}
                <div class="form-group">
                    <label for="end_date" class="w-100">تاريخ النهاية</label>
                    <input type="text" value="{{ $values['end_date'] }}" disabled >
                </div>

                {{-- end_date--}}
                <div class="form-group">
                    <label for="teacher_name" class="w-100">اسم المعلم</label>
                    <input type="text" value="{{ $values['teacher_name'] }}" disabled >
                </div>

            </div>
        </div>
    </div>

</form>

@endsection
