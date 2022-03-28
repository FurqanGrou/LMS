@extends('teachers.layouts.master')

<style>
    .required {
        color: #F74B5C !important;
        font-weight: bold;
    }

    select + .select2.select2-container {
        width: 100% !important;
    }

    .select2-selection.select2-selection--single {
        max-width: 100% !important;
    }
</style>
@section('content')

    @include('teachers.partials.errors')
    @include('teachers.partials.success')

    <div class="container">
        <div class="row d-flex justify-content-center">
            <button type="button" class="btn btn-blue col-3 m-1" data-toggle="modal"
                    data-target="#request_1">
                طلب إذن غياب
            </button>

            <button type="button" class="btn btn-blue col-3 m-1" data-toggle="modal"
                    data-target="#request_2">
                طلب إذن تأخير
            </button>

            <button type="button" class="btn btn-blue col-3 m-1" data-toggle="modal"
                    data-target="#request_3">
                طلب إذن الخروج مبكراً
            </button>

{{--            <button type="button" class="btn btn-blue col-3 m-1" data-toggle="modal"--}}
{{--                    data-target="#request_2">--}}
{{--                طلب - رابط البصمة لا يعمل--}}
{{--            </button>--}}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade text-left" id="request_1" tabindex="-1" role="dialog" aria-labelledby="request_1"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel35"> طلب إذن غياب</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form" method="POST" action="{{ route('teachers.request_services.attendanceAbsenceTeachers.store') . "?type=absence" }}" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <fieldset class="form-group floating-label-form-group">
                            <div class="alert alert-danger d-none" id="date_excuse_absence_alert" role="alert">يجب عليك ادخال تاريخ مستقبلي بشكل صحيح</div>

                            <label for="email"><span class="required">*</span> اليوم المطلوب الغياب فيه</label>
                            <input type="date" name="date_excuse" value="{{ old('date_excuse') }}" id="date_excuse_absence" class="form-control" required>
                        </fieldset>
                        <br>

                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> الحلقة</label>
                            <select class="form-control select2" name="class_numbers[]" id="class_numbers" multiple required>
                                @foreach($classes as $class)
                                    <option value="{{ $class->class_number }}">{{ $class->title }}</option>
                                @endforeach
                            </select>
                        </fieldset>

                        <br>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> سبب الغياب</label>
                            <select class="form-control select2" name="reason_excuse" id="absence_reason" required>
                                <option value=""></option>
                                <option value="مرض">مرض</option>
                                <option value="أجازة">أجازة</option>
                                <option value="سفر">سفر</option>
                                <option value="اختبارات">اختبارات</option>
                                <option value="other">أخرى</option>
                            </select>
                        </fieldset>
                        <br>
                        <fieldset class="form-group floating-label-form-group d-none" id="other_absence_reason">
                            <textarea class="form-control" id="title1" rows="3" cols="5" name="absence_reason">{!! old('absence_reason') !!}</textarea>
                        </fieldset>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title6">مرفقات اضافية</label>
                            <input type="file" class="form-control" id="title6" name="absence_additional_attachments">
                        </fieldset>
                    </div>

                    <div class="modal-footer d-flex flex-row-reverse">
                        <button type="reset" class="btn btn-warning">
                            <i class="ft-x"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary mr-1">
                            <i class="fa fa-check"></i>
                            <i class="fa fa-spinner" aria-hidden="true"></i>
                            ارسال
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="request_2" tabindex="-1" role="dialog" aria-labelledby="request_2"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel35"> طلب إذن تأخير</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form" method="POST" action="{{ route('teachers.request_services.attendanceAbsenceTeachers.store') . "?type=delay" }}" id="form-type-delay">
                    @csrf

                    <div class="modal-body">
                        <fieldset class="form-group floating-label-form-group">
                            <div class="alert alert-danger d-none" id="date_excuse_delay_alert" role="alert">يجب عليك ادخال تاريخ مستقبلي بشكل صحيح</div>
                            <label for="email"><span class="required">*</span> اليوم المطلوب التأخر فيه</label>
                            <input type="date" class="form-control" id="date_excuse_delay" value="{{ old('date_excuse') }}" name="date_excuse" required>
                        </fieldset>

                        <br>

                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> الحلقة</label>
                            <select class="form-control select2" name="class_numbers[]" id="class_numbers" multiple required>
                                @foreach($classes as $class)
                                    <option value="{{ $class->class_number }}">{{ $class->title }}</option>
                                @endforeach
                            </select>
                        </fieldset>

                        <br>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> سبب التأخير</label>
                            <textarea class="form-control" id="title7" rows="3" cols="5" name="reason_excuse" required>{!! old('reason_excuse') !!}</textarea>
                        </fieldset>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title2"><span class="required">*</span>  مدة التأخير - دقائق</label>
                            <input type="number" class="form-control" id="title2" value="{{ old('duration_delay') }}" name="duration_delay" placeholder="مدة التأخير - دقائق" min="1" required>
                        </fieldset>
                    </div>

                    <div class="modal-footer d-flex flex-row-reverse">
                        <button type="reset" class="btn btn-warning">
                            <i class="ft-x"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary mr-1">
                            <i class="fa fa-check"></i>
                            <i class="fa fa-spinner" aria-hidden="true"></i>
                            ارسال
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="request_3" tabindex="-1" role="dialog" aria-labelledby="request_3"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel35"> طلب إذن الخروج مبكراً</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form" method="POST" action="{{ route('teachers.request_services.attendanceAbsenceTeachers.store') . "?type=exit" }}">
                    @csrf

                    <div class="modal-body">
                        <fieldset class="form-group floating-label-form-group">
                            <div class="alert alert-danger d-none" id="date_excuse_exit_alert" role="alert">يجب عليك ادخال تاريخ مستقبلي بشكل صحيح</div>
                            <label for="email"><span class="required">*</span> اليوم المطلوب الخروج فيه</label>
                            <input type="date" class="form-control" id="date_excuse_exit" name="date_excuse" value="{{ old('date_excuse') }}" required>
                        </fieldset>

                        <br>

                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> الحلقة</label>
                            <select class="form-control select2" name="class_numbers[]" id="class_numbers" multiple required>
                                @foreach($classes as $class)
                                    <option value="{{ $class->class_number }}">{{ $class->title }}</option>
                                @endforeach
                            </select>
                        </fieldset>

                        <br>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title3"><span class="required">*</span> سبب الخروج</label>
                            <textarea class="form-control" id="title3" rows="3" cols="5" name="reason_excuse" required>{!! old('reason_excuse') !!}</textarea>
                        </fieldset>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title4"><span class="required">*</span>  موعد الخروج</label>
                            <input type="time" class="form-control" id="title4" name="exit_time" value="{{ old('exit_time') }}" required>
                        </fieldset>
                    </div>

                    <div class="modal-footer d-flex flex-row-reverse">
                        <button type="reset" class="btn btn-warning">
                            <i class="ft-x"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary mr-1">
                            <i class="fa fa-check"></i>
                            <i class="fa fa-spinner" aria-hidden="true"></i>
                            ارسال
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="request_4" tabindex="-1" role="dialog" aria-labelledby="request_4"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel35"> طلب - رابط البصمة لا يعمل</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form" method="POST" action="{{ route('teachers.request_services.attendanceAbsenceTeachers.store') }}">
                    @csrf

                    <div class="modal-body">
                        <fieldset class="form-group floating-label-form-group">
                            <label for="email">Email Address</label>
                            <input type="text" class="form-control" id="email" placeholder="Email Address">
                        </fieldset>
                        <br>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title">Password</label>
                            <input type="password" class="form-control" id="title" placeholder="Password">
                        </fieldset>
                        <br>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title5">Description</label>
                            <textarea class="form-control" id="title5" rows="3" placeholder="Description"></textarea>
                        </fieldset>
                    </div>

                    <div class="modal-footer d-flex flex-row-reverse">
                        <button type="reset" class="btn btn-warning">
                            <i class="ft-x"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary mr-1">
                            <i class="fa fa-check"></i>
                            <i class="fa fa-spinner" aria-hidden="true"></i>
                            ارسال
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function() {

            $(document).on('change', 'form #date_excuse_absence', function (e) {
                let CurrentDateTime = new Date();
                let date_excuse_absence = new Date($('form #date_excuse_absence').val());

                if(date_excuse_absence >= CurrentDateTime){
                    $('#date_excuse_absence_alert').addClass('d-none');
                    $('form #date_excuse_absence').css('border-color', 'green');
                }else{
                    $('#date_excuse_absence_alert').removeClass('d-none');
                    $('form #date_excuse_absence').css('border-color', 'red');
                    $('form #date_excuse_absence').val('');
                }
            });

            $(document).on('change', 'form #date_excuse_delay', function (e) {
                let CurrentDateTime = new Date().getUTCDate();
                let date_excuse_delay = new Date($('form #date_excuse_delay').val()).getUTCDate();

                if(date_excuse_delay >= CurrentDateTime){
                    $('#date_excuse_delay_alert').addClass('d-none');
                    $('form #date_excuse_delay').css('border-color', 'green');
                }else{
                    $('#date_excuse_delay_alert').removeClass('d-none');
                    $('form #date_excuse_delay').css('border-color', 'red');
                    $('form #date_excuse_delay').val('');
                }
            });

            $(document).on('change', 'form #date_excuse_exit', function (e) {
                let CurrentDateTime = new Date().getUTCDate();
                let date_excuse_exit = new Date($('form #date_excuse_exit').val()).getUTCDate();

                if(date_excuse_exit >= CurrentDateTime){
                    $('#date_excuse_exit_alert').addClass('d-none');
                    $('form #date_excuse_exit').css('border-color', 'green');
                }else{
                    $('#date_excuse_exit_alert').removeClass('d-none');
                    $('form #date_excuse_exit').css('border-color', 'red');
                    $('form #date_excuse_exit').val('');
                }
            });

            $(document).on('change', 'select#absence_reason', function (e) {
                if ($(this).val() == 'other'){
                    $('#other_absence_reason').removeClass('d-none');
                    $('#other_absence_reason textarea').attr('required', true);
                }else{
                    $('#other_absence_reason').addClass('d-none');
                    $('#other_absence_reason textarea').removeAttr('required');
                }
            });

            $(document).on('submit', 'form', function (e) {
                $('.lds-roller').css('display', 'inline-block');
                $('button[type="submit"]').addClass('disabled');
                $('button[type="submit"] i.fa-spinner').css('display', 'inline-block');
                $('button[type="submit"] i.fa-check').css('display', 'none');
            });

            $('#form-type-delay select#class_numbers').on('change', function () {

                let date = $('#form-type-delay #date_excuse_delay').val();
                let class_numbers = $(this).val();
                let url = '{{ route("teachers.request_services.attendanceAbsenceTeachers.checkPeriod") }}';

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: url,
                    data: {
                        "class_numbers": class_numbers,
                        "date": date,
                    },
                    success: function (data) {
                        console.log(data);
                    }
                });
            });

        });
    </script>
@endpush
