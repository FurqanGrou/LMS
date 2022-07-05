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
                        <input type="date" class="form-control" name="date_excuse" value="{{ old('date_excuse', $attendanceAbsenceRequest->date_excuse) }}">
                    </div>
                </div>

                @if(in_array($attendanceAbsenceRequest->reason_excuse, ['مرض', 'أجازة', 'سفر', 'اختبارات']))
                    <div class="col-md-4">
                        <label class="w-100">سبب الإذن - العذر</label>
                        <select class="form-control select2 reason_excuse" name="reason_excuse" id="absence_reason" required>
                            <option value=""></option>
                            <option value="مرض" {{ $attendanceAbsenceRequest->reason_excuse == 'مرض' ? 'selected' : '' }}>مرض</option>
                            <option value="أجازة" {{ $attendanceAbsenceRequest->reason_excuse == 'أجازة' ? 'selected' : '' }}>أجازة</option>
                            <option value="سفر" {{ $attendanceAbsenceRequest->reason_excuse == 'سفر' ? 'selected' : '' }}>سفر</option>
                            <option value="اختبارات" {{ $attendanceAbsenceRequest->reason_excuse == 'اختبارات' ? 'selected' : '' }}>اختبارات</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                @else
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chapter_name" class="w-100">سبب الإذن - العذر</label>
                            <div class="position-relative has-icon-left">
                                <textarea id="timesheetinput7" rows="5" class="form-control" name="reason_excuse" placeholder="سبب الإذن - العذر">{!! old('reason_excuse', $attendanceAbsenceRequest->reason_excuse) !!}</textarea>
                                <div class="form-control-position">
                                    <i class="ft-file"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($attendanceAbsenceRequest->duration_delay)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chapter_name" class="w-100">مدة التأخير - دقائق</label>
                            <input type="number" class="form-control" name="duration_delay" value="{{ old('duration_delay', @$attendanceAbsenceRequest->duration_delay) }}">
                        </div>
                    </div>
                @endif

                @if($attendanceAbsenceRequest->exit_time)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="chapter_name" class="w-100">موعد الخروج</label>
                            <input type="time" class="form-control" name="exit_time" value="{{ old('exit_time', @$attendanceAbsenceRequest->exit_time) }}">
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

                <div class="col-md-4">
                    <label class="w-100" for="class_numbers">الحلقة</label>
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
