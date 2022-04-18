@extends('teachers.layouts.master')

<style>
    input, textarea {
        font-weight: bold !important;
    }
</style>

@section('content')

@include('teachers.partials.errors')
@include('teachers.partials.success')

    <form class="form" method="POST" action="{{ route('teachers.request_services.attendanceAbsenceTeachers.update', $attendanceAbsenceRequest) . "?type=$attendanceAbsenceRequest->request_type" }}">
        @csrf
        @method('PUT')

        <div class="form-body" style="color: black !important; font-weight: bold">
            <h4 class="form-section bold black"><i class="ft-user"></i> طلب - {{ $attendanceAbsenceRequest->type }}</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="chapter_name" class="w-100">تاريخ الإذن المطلوب</label>
                        <input type="date" class="form-control" value="{{ $attendanceAbsenceRequest->date_excuse }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="chapter_name" class="w-100">سبب الإذن - العذر</label>
                        <textarea class="form-control" >{!! $attendanceAbsenceRequest->reason_excuse !!}</textarea>
                    </div>
                </div>

                @if($attendanceAbsenceRequest->duration_delay)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chapter_name" class="w-100">مدة التأخير - دقائق</label>
                            <input type="number" class="form-control" value="{{ @$attendanceAbsenceRequest->duration_delay }}">
                        </div>
                    </div>
                @endif

                @if($attendanceAbsenceRequest->exit_time)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chapter_name" class="w-100">موعد الخروج</label>
                            <input type="time" class="form-control" value="{{ @$attendanceAbsenceRequest->exit_time }}">
                        </div>
                    </div>
                @endif

                @if($attendanceAbsenceRequest->additional_attachments_path)
                    <div class="col-md-12">
                        <div class="form-group">
                            <a href="{{ $attendanceAbsenceRequest->additional_attachments_path }}" class="btn btn-primary">تنزيل المرفقات الإضافية</a>
                        </div>
                    </div>
                @endif

                <div class="col-md-8">
                    <label class="w-100">الحلقات</label>
                    <select class="form-control select2" name="class_number" id="class_numbers">
                        @foreach($classes as $class)
                            <option value="{{ $class->class_number }}" {{ $class->class_number == $attendanceAbsenceRequest->class_number ? 'selected' : ''  }}>{{ $class->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 mt-5 d-flex flex-row-reverse justify-content-end">
                    <button type="reset" class="btn btn-warning">
                        <i class="ft-x"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary mr-1">
                        <i class="la la-check-square-o"></i> تحديث
                    </button>
                </div>

            </div>
        </div>

    </form>

@endsection
