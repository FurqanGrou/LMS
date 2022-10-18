@extends('admins.layouts.master')

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.export-top-tracker-reports') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <a href="{{ route('admins.import-top-tracker-employees') }}" class="btn btn-success">استيراد بيانات الموظفين</a>
        <div class="form-body">
            <h4 class="form-section">
                <i class="la la-file-excel-o"></i>
                تصدير تقارير الدوام الشهرية - Top Tracker
            </h4>

            <div class="row">

                <div class="col-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">التاريخ - من</span>
                        </div>
                        <input type="date" required class="form-control" name="start_date" aria-label="التاريخ - من">
                    </div>
                </div>

                <div class="col-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon2">التاريخ - إلى</span>
                        </div>
                        <input type="date" required class="form-control" name="end_date" aria-label="التاريخ - إلى">
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
