@extends('teachers.layouts.master')

@section('content')

@include('teachers.partials.errors')
@include('teachers.partials.success')

<form class="form" method="POST" action="{{ route('teachers.request_services.exam.store') }}">

    @csrf

    <div class="form-body">
        <h4 class="form-section"><i class="ft-lock"></i>طلب الخدمة - طلب اختبار</h4>

        <div class="row">
            <div class="col-md-12">

                {{-- student_name--}}
                <div class="form-group" id="student_name">
                    <label>اسم الطالب:</label>
                    <br>
                    <select name="student_id" class="select2">
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- chapter_name--}}
                <div class="form-group">
                    <label>اسم الجزء:</label>
                    <br>
                    <select name="chapter_id" class="select2">
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- start_date--}}
                <div class="form-group">
                    <label for="start_date">تاريخ البداية:</label>
                    <input type="date" name="start_date" class="form-control" id="start_date">
                </div>

                {{-- end_date--}}
                <div class="form-group">
                    <label for="end_date">تاريخ النهاية:</label>
                    <input type="date" name="end_date" class="form-control" id="end_date">
                </div>

                {{-- teacher_name--}}
                <div class="form-group">
                    <label>اسم المعلم:</label>
                    <br>
                    <select name="teacher_name" class="select2">
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

@push('js')
    <script>
        $(document).ready(function() {
            $(document).on('change', 'select#request_type', function (e) {
                if($(this).val() == 'complaint'){
                    $('#complaint_type').removeClass('d-none');
                    $('#complaint_type select').attr('name', 'complaint_type');
                }else{
                    $('#complaint_type').toggleClass('d-none');
                    $('#complaint_type select').removeAttr('name');
                }
            });
        });
    </script>
@endpush