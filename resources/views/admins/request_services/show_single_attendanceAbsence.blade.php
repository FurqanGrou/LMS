@extends('admins.layouts.master')

<style>
    input, textarea {
        font-weight: bold !important;
    }
</style>
@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" >

        <div class="form-body" style="color: black !important; font-weight: bold">
            <h4 class="form-section bold black"><i class="ft-user"></i> طلب - {{ $attendanceAbsenceRequest->type }}</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="student_id" class="w-100">اسم المعلم</label>
                        <input type="text" class="form-control" value="{{ $attendanceAbsenceRequest->teacher->name }}" disabled >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="chapter_name" class="w-100">رقم المعلم</label>
                        <input type="text" class="form-control" value="{{ $attendanceAbsenceRequest->teacher->teacher_number }}" disabled >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="chapter_name" class="w-100">تاريخ الإذن المطلوب</label>
                        <input type="text" class="form-control" value="{{ $attendanceAbsenceRequest->date_excuse }}" disabled >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="chapter_name" class="w-100">تاريخ تقديم الطلب</label>
                        <input type="text" class="form-control" value="{{ $attendanceAbsenceRequest->created_at->format('g:i A Y-m-d') }}" disabled >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="chapter_name" class="w-100">سبب الإذن - العذر</label>
                        <textarea class="form-control" disabled >{!! $attendanceAbsenceRequest->reason_excuse !!}</textarea>
                    </div>
                </div>
                @if($attendanceAbsenceRequest->duration_delay)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chapter_name" class="w-100">مدة التأخير</label>
                            <input type="text" class="form-control" value="{{ @$attendanceAbsenceRequest->duration_delay }}" disabled >
                        </div>
                    </div>
                @endif
                @if($attendanceAbsenceRequest->exit_time)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chapter_name" class="w-100">موعد الخروج</label>
                            <input type="text" class="form-control" value="{{ @$attendanceAbsenceRequest->exit_time }}" disabled >
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

                <div class="col-md-12">
                    <label class="w-100">الحلقات</label>
                    @foreach(json_decode($attendanceAbsenceRequest->class_numbers) as $class_number)
                        <span class="badge badge-primary">{{ getClassName($class_number) }}</span>
                    @endforeach
                </div>

            </div>
        </div>

    </form>

@endsection
