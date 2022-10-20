@extends('admins.layouts.master')

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.report.export') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section">
                <i class="la la-file-excel-o"></i>
                تصدير تقارير متابعة الطلاب حسب التاريخ
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
                <div class="col-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">حالة التقرير</span>
                        </div>
                        <select name="mail_status" id="" class="form-control">
                            <option value="2">الكل</option>
                            <option value="1">مرسل</option>
                            <option value="0">غير مرسل</option>
                        </select>
                    </div>
                </div>

                @if(auth()->user()->user_type == 'super_admin')
                    <div class="col-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">نوع الطلاب</span>
                            </div>
                            <select name="study_type" id="" class="form-control">
                                <option value="2">الكل</option>
                                <option value="1">حضوري</option>
                                <option value="0">اونلاين</option>
                            </select>
                        </div>
                    </div>
                @endif
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
