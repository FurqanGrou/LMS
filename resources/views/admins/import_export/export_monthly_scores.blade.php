@extends('admins.layouts.master')

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.monthly_scores.export') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section">
                <i class="la la-file-excel-o"></i>
                تصدير تقارير المتابعة الشهرية
            </h4>

            <div class="row">
                <div class="col-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">شهر - عام</span>
                        </div>
                        <input type="month" required class="form-control" name="month_year" value="{{ date("Y" . "-" . date('m')) }}" aria-label="شهر - عام">
                    </div>
                </div>
                <div class="col-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">حالة التقرير</span>
                        </div>
                        <select name="mail_status" id="type" class="form-control">
                            <option value="-1">الكل</option>
                            <option value="1">مرسل</option>
                            <option value="0">غير مرسل</option>
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
