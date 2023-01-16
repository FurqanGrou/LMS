@extends('admins.layouts.master')

<style>
    #students + .select2.select2-container,
    #commitment_type + .select2.select2-container {
        width: 90% !important;
    }
</style>
@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.export.commitment-report.store') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section">
                <i class="la la-file-excel-o"></i>
                تصدير تقرير إلتزام الطلاب
            </h4>

            <div class="row">
                <div class="col-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">من</span>
                        </div>
                        <input type="date" required class="form-control" name="date_from" aria-label="التاريخ من">
                    </div>
                </div>
                <div class="col-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">إلى</span>
                        </div>
                        <input type="date" required class="form-control" name="date_to" aria-label="التاريخ إلى">
                    </div>
                </div>
                <div class="col-4">
                    <div class="input-group mb-3 flex-nowrap">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">نوع الإلتزام</span>
                        </div>
                        <select name="commitment_type[]" id="commitment_type" class="select2" multiple="multiple" required>
                            <option value="camera">الكاميرا</option>
                            <option value="sitting">الجلسة</option>
                            <option value="login_exit">الدخول والخروج</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="input-group mb-3 flex-nowrap">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon2">الرقم التسلسلي</span>
                        </div>
                        <select name="students[]" id="students" class="form-control select2" multiple="multiple" required>
                            <option value="all">الكل</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->student_number . ' - ' . $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i>
                تصدير
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i>
                إلغاء
            </button>
        </div>

    </form>

@endsection
