@extends('teachers.layouts.master')

@section('content')

    @include('teachers.partials.errors')
    @include('teachers.partials.success')

    <form class="form" method="POST" action="{{ route('teachers.request_services.store') }}">

        @csrf
        <input type="hidden" name="form_title" value="طلب اختبار">
        <input type="hidden" name="service[submitted_by]" value="{{ auth()->guard('teacher_web')->user()->name }}">
        <input type="hidden" name="service[number_mistakes_memo]">
        <input type="hidden" name="service[number_alerts]">
        <input type="hidden" name="service[number_tajweed]">

        <div class="form-body">
            <h4 class="form-section"><i class="ft-lock"></i>طلب الخدمة - طلب اختبار</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="student_name" class="w-100">اسم الطالب</label>
                        <select name="service[student_id]" class="form-control select2 w-100" id="student_name">
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="chapter_name">اسم الجزء</label>
                        <input type="text" id="chapter_name" class="form-control" placeholder="اسم الجزء" name="service[chapter_name]">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="start_date">تاريخ البداية</label>
                        <input type="date" id="start_date" class="form-control" placeholder="تاريخ البداية" name="service[start_date]">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="end_date">تاريخ النهاية</label>
                        <input type="date" id="end_date" class="form-control" placeholder="تاريخ النهاية" name="service[end_date]">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">

                        <label for="teacher_name" class="w-100">اسم المعلم</label>
                        <select name="service[teacher_name]" class="form-control select2 w-100" id="teacher_name">
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->name }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> تحديث
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection
