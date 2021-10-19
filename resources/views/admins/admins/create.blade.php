@extends('admins.layouts.master')

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.admins.store') }}">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-user"></i> بيانات المشرف</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="full-name">الاسم كامل</label>
                        <input type="text" id="full-name" class="form-control" placeholder="الأسم كامل" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" class="form-control" placeholder="البريد الإلكتروني" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">رقم الموظف</label>
                        <input type="text" id="employee_number" class="form-control" placeholder="رقم الموظف" name="employee_number" value="{{ old('employee_number') }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">اخر 4 ارقام من الهوية</label>
                        <input type="text" id="last_4_id" class="form-control" placeholder="اخر 4 ارقام من الهوية" name="last_4_id" value="{{ old('last_4_id') }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <select name="section" id="section">
                            <option value="male">بنين</option>
                            <option value="female">بنات</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="password" id="password" class="form-control" placeholder="كلمة المرور" name="password" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> اضافة
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection
