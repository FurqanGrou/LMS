@extends('admins.layouts.master')

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.admins.update', $admin->id) }}">

        @csrf
        @method('PUT')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-user"></i> بيانات المشرف</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="full-name">الاسم كامل</label>
                        <input type="text" id="full-name" class="form-control" name="name" value="{{ old('name', $admin->name) }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}" required>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">رقم الموظف</label>
                        <input type="text" id="employee_number" class="form-control" placeholder="رقم الموظف" name="employee_number" value="{{ old('employee_number', $admin->employee_number) }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">اخر 4 ارقام من الهوية</label>
                        <input type="text" id="last_4_id" class="form-control" placeholder="اخر 4 ارقام من الهوية" name="last_4_id" value="{{ old('last_4_id', $admin->last_4_id) }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="section">القسم</label>
                        <select name="section" class="form-control" id="section">
                            <option value="male" {{ $admin->user_type == 'male' ? 'selected' : '' }}>بنين</option>
                            <option value="female" {{ $admin->user_type == 'female' ? 'selected' : '' }}>بنات</option>
                        </select>
                    </div>
                </div>

            @if(isHasUserType('super_admin'))
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="user_type">المؤسسة</label>
                            <select name="user_type" class="form-control" id="user_type">
                                <option value="select">-إختر-</option>
                                <option value="super_admin" {{ $admin->user_type == 'super_admin' ? 'selected' : '' }}>إداري عام</option>
                                <option value="furqan_group" {{ $admin->user_type == 'furqan_group' ? 'selected' : '' }}>مجموعة الفرقان</option>
                                <option value="iksab" {{ $admin->user_type == 'iksab' ? 'selected' : '' }}>إكساب</option>
                                <option value="egypt" {{ $admin->user_type == 'egypt' ? 'selected' : '' }}>فرع مصر</option>
                            </select>
                        </div>
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="password" id="password" class="form-control" placeholder="كلمة المرور" name="password">
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
