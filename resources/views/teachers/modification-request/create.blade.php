@extends('teachers.layouts.master')

@section('content')

@include('teachers.partials.errors')
@include('teachers.partials.success')

<form class="form" method="POST" action="{{ route('teachers.modification_request.store') }}">

    @csrf

    <div class="form-body">
        <h4 class="form-section"><i class="ft-lock"></i>طلب تعديل درجات</h4>

        <div class="row">
            <div class="col-md-6">
                {{-- student_name--}}
                <div class="form-group" id="student_name">
                    <label>اسم الطالب:</label>
                    <br>
                    <select name="student_id" class="select2" required>
                        <option>-</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                {{-- modification_date--}}
                <div class="form-group">
                    <label for="modification_date">تاريخ اليوم المطلوب التعديل فيه:</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date') }}" id="modification_date" required>
                </div>
            </div>
            <div class="col-md-6">
                {{-- new_grades--}}
                <div class="form-group">
                    <label for="new_grades">الدرجات الجديدة المطلوبة:</label>
                    <br>
                    <textarea name="new_grades" id="" cols="60" rows="10" required>{{ old('new_grades') }}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                {{-- notes--}}
                <div class="form-group">
                    <label for="notes">ملاحظات - اختياري:</label>
                    <br>
                    <textarea name="notes" id="" cols="60" rows="10">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="la la-check-square-o"></i> ارسال
        </button>
        <button type="reset" class="btn btn-warning mr-1">
            <i class="ft-x"></i> إلغاء
        </button>
    </div>

</form>

@endsection
