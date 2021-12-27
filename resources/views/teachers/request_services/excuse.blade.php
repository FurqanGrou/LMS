@extends('teachers.layouts.master')

@section('content')

@include('teachers.partials.errors')
@include('teachers.partials.success')

<form class="form" method="POST" action="{{ route('teachers.request_services.store') }}">

    @csrf

    <div class="form-body">
        <h4 class="form-section"><i class="ft-lock"></i>طلب الخدمة - اقتراحات وشكاوى</h4>

        <div class="row">
            <div class="col-md-12">

                {{-- request_type--}}
                <div class="form-group" id="request_type">
                    <label>نوع الطلب:</label>
                    <br>
                    <select name="request_type" id="request_type" class="select2">
                        <option value="suggest">اقتراح</option>
                        <option value="complaint">شكوى</option>
                    </select>
                </div>

                {{-- complaint_type--}}
                <div class="form-group d-none" id="complaint_type">
                    <label>نوع الشكوى:</label>
                    <br>
                    <select class="select2">
                        <option value="observer_complaint">شكوى مراقب</option>
                        <option value="officer_complaint">شكوى مسؤول</option>
                    </select>
                </div>

                {{-- name--}}
                <div class="form-group">
                    <label for="name">الاسم:</label>
                    <input type="text" name="name" class="form-control" id="email">
                </div>

                {{-- subject--}}
                <div class="form-group">
                    <label for="subject">الموضوع:</label>
                    <input type="text" name="subject" class="form-control" id="subject">
                </div>

                {{-- details --}}
                <div class="form-group">
                    <label for="details">التفاصيل</label>
                    <textarea id="details" class="form-control" name="details" ></textarea>
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
            $(document).on('change', 'select#request_type', function (e) {
                if($(this).val() == 'complaint'){
                    $('#complaint_type').removeClass('d-none');
                    $('#complaint_type select').attr('name', 'complaint_type');
                }else{
                    $('#complaint_type').toggleClass('d-none');
                    $('#complaint_type select').removeAttr('name');
                }
            });
        });
    </script>
@endpush
