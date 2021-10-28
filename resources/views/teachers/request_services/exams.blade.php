@extends('teachers.layouts.master')

@section('content')

@include('teachers.partials.errors')
@include('teachers.partials.success')

<form class="form" method="POST" action="{{ route('teachers.request_services.store') }}">

    @csrf
    <input type="hidden" name="form_title" value="طلب اختبار">
    <input type="hidden" name="submitted_by" value="{{ auth()->guard('teacher_web')->user()->name }}">

    <input type="hidden" name="number_mistakes_1" value="">
    <input type="hidden" name="number_mistakes_2" value="">
    <input type="hidden" name="number_mistakes_3" value="">

    <div class="form-body">
        <h4 class="form-section"><i class="ft-lock"></i>طلب الخدمة - طلب اختبار</h4>

        <div class="row">
            <div class="col-md-12">

                {{-- student_id--}}
                <div class="form-group">
                    <label for="student_id" class="w-100">اسم الطالب</label>
                    <select name="student_id" class="form-control select2 w-100" id="student_id">
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- chapter_name--}}
                <div class="form-group">
                    <label for="chapter_name" class="w-100">اسم الجزء</label>
                    <select name="chapter_name" class="form-control select2 w-100" id="chapter_name">
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter->name }}">{{ $chapter->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- start_date--}}
                <div class="form-group">
                    <label for="start_date">تاريخ البداية</label>
                    <input type="date" id="start_date" class="form-control" placeholder="تاريخ البداية" name="start_date">
                </div>

                {{-- end_date--}}
                <div class="form-group">
                    <label for="end_date">تاريخ النهاية</label>
                    <input type="date" id="end_date" class="form-control" placeholder="تاريخ النهاية" name="end_date">
                </div>

                {{-- teacher_name--}}
                <div class="form-group">
                    <label for="teacher_name" class="w-100">اسم المعلم</label>
                    <select name="teacher_name" class="form-control select2 w-100" id="teacher_name">
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
            <i class="la la-check-square-o"></i> ارسال
        </button>
        <button type="reset" class="btn btn-warning mr-1">
            <i class="ft-x"></i> إلغاء
        </button>
    </div>

</form>

@endsection
