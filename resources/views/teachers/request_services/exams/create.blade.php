@extends('teachers.layouts.master')

@section('content')

@include('teachers.partials.errors')
@include('teachers.partials.success')

<form class="form" method="POST" action="{{ route('teachers.request_services.exam.store') }}">

    @csrf

    <div class="form-body">
        <h4 class="form-section"><i class="ft-lock"></i>طلب الخدمة - طلب اختبار</h4>

        <div class="row">
            <div class="col-md-12">

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

                {{-- chapter_name--}}
                <div class="form-group">
                    <label>اسم الجزء:</label>
                    <br>
                    <select name="chapter_id" class="select2" required>
                        <option>-</option>
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- start_date--}}
                <div class="form-group">
                    <label for="start_date">تاريخ البداية:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" id="start_date" required>
                </div>

                {{-- end_date--}}
                <div class="form-group">
                    <label for="end_date">تاريخ النهاية:</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" id="end_date" required>
                </div>

                {{-- teacher_name--}}
                <div class="form-group">
                    <label>اسم المعلم:</label>
                    <br>
                    <select name="teacher_name" class="select2" required>
                        <option>-</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->name }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
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

@push('js')
    <script>
        $(document).ready(function() {

            $(document).on('click', 'form button[type="reset"]', function (e) {
                $('form select.select2').val(null).trigger('change');
            });

            $(document).on('change', 'select#request_type', function (e) {
                if($(this).val() == 'complaint'){
                    $('#complaint_type').removeClass('d-none');
                    $('#complaint_type select').attr('name', 'complaint_type');
                }else{
                    $('#complaint_type').toggleClass('d-none');
                    $('#complaint_type select').removeAttr('name');
                }
            });

            $(document).on('change', '#start_date', function (e) {
                let today = new Date();
                let CurrentDate1 = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                let CurrentDate = new Date(CurrentDate1).getTime();
                let start_date  = new Date($(this).val()).getTime();
                let end_date    = new Date($('#end_date').val()).getTime();

                if(isNaN(end_date)){
                    if(start_date >= CurrentDate){
                        alert("يرجى إدخال تاريخ صحيح!");
                        $(this).val('');
                        $(this).focus();
                    }
                }else{
                    if(end_date <= start_date || end_date > CurrentDate){
                        alert("يرجى إدخال تاريخ صحيح!");
                        $('#end_date').val('');
                        $(this).val('');
                        $(this).focus();
                    }
                }

            });

            $(document).on('change', '#end_date', function (e) {
                let today = new Date();
                let CurrentDate = new Date(today).getTime();
                let start_date  = new Date($('#start_date').val()).getTime();
                let end_date    = new Date($(this).val()).getTime();

                if(end_date <= start_date || end_date > CurrentDate){
                    alert("يرجى إدخال تاريخ صحيح!");
                    $(this).val('');
                    $(this).focus();
                }

                if(isNaN(start_date)){
                    alert("يرجى إدخال تاريخ بداية اولاً!");
                    $(this).val('');
                    $('#start_date').val('');
                    $('#start_date').focus();
                }
            });
        });
    </script>
@endpush
