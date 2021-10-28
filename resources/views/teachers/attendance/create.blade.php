@extends('teachers.layouts.master')

@section('content')

    @include('teachers.partials.errors')
    @include('teachers.partials.success')

    <form class="form" method="POST" action="{{ route('teachers.attendance.store') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-user"></i> بصمة الحضور والانصراف</h4>
            <div class="row d-flex justify-content-center" style="margin-top: 100px">
                <div class="col-md-3 d-flex justify-content-center">
                    <button type="submit" class="btn btn-success" name="login_btn" value="login">
                        <i class="ft-log-in"></i> دخول
                    </button>
                </div>
                <div class="col-md-3 d-flex justify-content-center">
                    <button type="submit" class="btn btn-danger" name="logout_btn" value="logout">
                        <i class="ft-log-out"></i> خروج
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('js')
@endpush
