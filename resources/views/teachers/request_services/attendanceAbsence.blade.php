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

                <form class="form" method="POST" data-request-type="absence" enctype="multipart/form-data">

                    <div class="modal-body">
                        <fieldset class="form-group floating-label-form-group">
                            <div class="alert alert-danger d-none" id="date_excuse_absence_alert" role="alert">يجب عليك ادخال تاريخ مستقبلي بشكل صحيح</div>
                            <div class="alert alert-danger class-number-danger d-none" role="alert"></div>
                            <label for="email"><span class="required">*</span> اليوم المطلوب الغياب فيه</label>
                            <input type="date" name="date_excuse" value="{{ old('date_excuse') }}" id="date_excuse_absence" class="form-control" required>
                        </fieldset>
                        <br>

                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> الحلقة</label>
                            <select class="form-control select2" name="class_number" id="class_number" required>
                                    <option value=""></option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->class_number }}">{{ $class->title }}</option>
                                @endforeach
                            </select>
                        </fieldset>

                        <br>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> سبب الغياب</label>
                            <select class="form-control select2 reason_excuse" name="reason_excuse" id="absence_reason" required>
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
                            <textarea class="form-control absence_reason" id="title1" rows="3" cols="5" name="absence_reason">{!! old('absence_reason') !!}</textarea>
                        </fieldset>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="absence_additional_attachment">مرفق اضافي</label>
                            <input type="file" class="form-control" id="absence_additional_attachment" name="absence_additional_attachment">
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

                <form class="form" method="POST" data-request-type="delay" id="form-type-delay">

                    <div class="modal-body">
                        <fieldset class="form-group floating-label-form-group">
                            <div class="alert alert-danger d-none" id="date_excuse_delay_alert" role="alert">يجب عليك ادخال تاريخ مستقبلي بشكل صحيح</div>
                            <div class="alert alert-danger class-number-danger d-none" role="alert"></div>
                            <label for="email"><span class="required">*</span> اليوم المطلوب التأخر فيه</label>
                            <input type="date" class="form-control" id="date_excuse_delay" value="{{ old('date_excuse') }}" name="date_excuse" required>
                        </fieldset>

                        <br>

                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> الحلقة</label>
                            <select class="form-control select2" name="class_number" id="class_number" required>
                                <option value=""></option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->class_number }}">{{ $class->title }}</option>
                                @endforeach
                            </select>
                        </fieldset>

                        <br>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> سبب التأخير</label>
                            <textarea class="form-control reason_excuse" id="title7" rows="3" cols="5" name="reason_excuse" required>{!! old('reason_excuse') !!}</textarea>
                        </fieldset>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title2"><span class="required">*</span>  مدة التأخير - دقائق</label>
                            <input type="number" class="form-control duration_delay" id="title2" value="{{ old('duration_delay') }}" name="duration_delay" placeholder="مدة التأخير - دقائق" min="1" required>
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

                <form class="form" method="POST" data-request-type="exit">

                    <div class="modal-body">
                        <fieldset class="form-group floating-label-form-group">
                            <div class="alert alert-danger d-none" id="date_excuse_exit_alert" role="alert">يجب عليك ادخال تاريخ مستقبلي بشكل صحيح</div>
                            <div class="alert alert-danger class-number-danger d-none" role="alert"></div>
                            <label for="email"><span class="required">*</span> اليوم المطلوب الخروج فيه</label>
                            <input type="date" class="form-control" id="date_excuse_exit" name="date_excuse" value="{{ old('date_excuse') }}" required>
                        </fieldset>

                        <br>

                        <fieldset class="form-group floating-label-form-group">
                            <label for="title"><span class="required">*</span> الحلقة</label>
                            <select class="form-control select2" name="class_number" id="class_number" required>
                                <option value=""></option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->class_number }}">{{ $class->title }}</option>
                                @endforeach
                            </select>
                        </fieldset>

                        <br>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title3"><span class="required">*</span> سبب الخروج</label>
                            <textarea class="form-control reason_excuse" id="title3" rows="3" cols="5" name="reason_excuse" required>{!! old('reason_excuse') !!}</textarea>
                        </fieldset>
                        <fieldset class="form-group floating-label-form-group">
                            <label for="title4"><span class="required">*</span>  موعد الخروج</label>
                            <input type="time" class="form-control exit_time" id="title4" name="exit_time" value="{{ old('exit_time') }}" required>
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
                let CurrentDateTime2 = formatDate(CurrentDateTime);
                let date_excuse_absence = $('form #date_excuse_absence').val();

                if(dateCompare(CurrentDateTime2, date_excuse_absence)){
                    $('#date_excuse_absence_alert').addClass('d-none');
                    $('form #date_excuse_absence').css('border-color', 'green');
                }else{
                    $('#date_excuse_absence_alert').removeClass('d-none');
                    $('form #date_excuse_absence').css('border-color', 'red');
                    $('form #date_excuse_absence').val('');
                }

            });

            function formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2)
                    month = '0' + month;
                if (day.length < 2)
                    day = '0' + day;

                return [year, month, day].join('-');
            }

            function dateCompare(current_date, future_date){
                const date1 = new Date(current_date);
                const date2 = new Date(future_date);

                // greater than
                if(date1 > date2){
                    return false;
                // less than
                } else if(date1 < date2){
                    return true;
                //equal
                } else{
                    return true;
                }
            }

            $(document).on('change', 'form #date_excuse_delay', function (e) {
                let CurrentDateTime = new Date();
                let CurrentDateTime2 = formatDate(CurrentDateTime);
                let date_excuse_delay = $('form #date_excuse_delay').val();

                if(dateCompare(CurrentDateTime2, date_excuse_delay)){
                    $('#date_excuse_delay_alert').addClass('d-none');
                    $('form #date_excuse_delay').css('border-color', 'green');
                }else{
                    $('#date_excuse_delay_alert').removeClass('d-none');
                    $('form #date_excuse_delay').css('border-color', 'red');
                    $('form #date_excuse_delay').val('');
                }
            });

            $(document).on('change', 'form #date_excuse_exit', function (e) {
                let CurrentDateTime = new Date();
                let CurrentDateTime2 = formatDate(CurrentDateTime);
                let date_excuse_exit = $('form #date_excuse_exit').val();

                if(dateCompare(CurrentDateTime2, date_excuse_exit)){
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
                e.preventDefault();
                // $('.lds-roller').css('display', 'inline-block');
                // $('button[type="submit"]').addClass('disabled');
                // $('button[type="submit"] i.fa-spinner').css('display', 'inline-block');
                // $('button[type="submit"] i.fa-check').css('display', 'none');

                let element = $(this).parent().parent();

                let request_type = element.find('form').data('request-type');
                let reason_excuse = element.find('.reason_excuse').val();

                let absence_reason = element.find('.absence_reason').val();

                let absence_additional_attachment_file = $('#absence_additional_attachment')[0].files[0];

                // if (reason_excuse == 'other' && request_type == 'absence'){
                //     absence_reason = element.find('.absence_reason').val();
                //     // Get the selected file
                // }

                let date = element.find('[name="date_excuse"]').val();
                let class_number = element.find('select#class_number').val();
                let duration_delay = element.find('.duration_delay').val();
                let exit_time = element.find('.exit_time').val();

                let url = '{{ route("teachers.request_services.attendanceAbsenceTeachers.checkPeriod") }}';

                var fd = new FormData();

                // Append data
                fd.append('class_number', class_number);
                fd.append('date_excuse', date);
                fd.append('type', request_type);

                if(absence_reason){
                    fd.append('absence_reason', absence_reason);
                }
                if(duration_delay){
                    fd.append('duration_delay', duration_delay);
                }
                if(reason_excuse){
                    fd.append('reason_excuse', reason_excuse);
                }
                if(exit_time){
                    fd.append('exit_time', exit_time);
                }
                if(absence_additional_attachment_file){
                    fd.append('absence_additional_attachment', absence_additional_attachment_file);
                }

                $.ajax({
                    type: "POST",
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    url: url,
                    data: fd,
                    success: function (data) {
                        element.find('.class-number-danger').addClass('d-none');
                        element.find('form').trigger("reset");
                        element.parent().modal('hide');
                        alert('تم تقديم الطلب بنجاح');
                    },
                    error: function (data) {
                        data['responseJSON']['errors'].forEach(function (message){
                            element.find('.class-number-danger').removeClass('d-none');
                            element.find('.class-number-danger').html(message)
                        });
                        element.parent().scrollTop(0);
                    }
                });
            });

        });
    </script>
@endpush
