@extends('teachers.layouts.master')

@section('content')

    @include('teachers.partials.errors')
    @include('teachers.partials.success')

    <form class="form" method="POST" action="">

        @csrf
        @method('PUT')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-lock"></i> تغيير كلمة المرور</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="password">كلمة المرور الحالية</label>
                        <input type="password" id="current_password" class="form-control" placeholder="كلمة المرور الحالية" name="current_password">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="password">كلمة المرور الجديدة</label>
                        <input type="password" id="password" class="form-control" placeholder="كلمة المرور الجديدة" name="password">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="password">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" id="password_confirmation" class="form-control" placeholder="تأكيد كلمة المرور الجديدة" name="password_confirmation">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> تحديث
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection
