@extends('admins.layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">جميع طلبات الاذونات المقدمة</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>

                @include('teachers.partials.success')

                <div class="card-content collapse show">
                    <div class="card-body card-admins">

                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                            <a href="{{ route('admins.request_services.attendanceAbsenceTeachers.export') }}" class="btn btn-primary mb-2">تصدير - Excel</a>

                            <div class="table-responsive">
                                <table class="table">

                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المعلم</th>
                                            <th>رقم المعلم</th>
                                            <th>نوع الطلب</th>
                                            <th>تاريخ تقديم الطلب</th>
                                            <th>تاريخ الإذن المطلوب</th>
                                            <th>اسم الحلقة</th>
                                            <th>حالة الطلب</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>

                                    <tbody id="table_appliedRequests">
                                        @foreach($appliedRequests as $key => $appliedRequest)
                                            <tr class="" style="background: lemonchiffon; color: #0a001f; font-weight: bold">
                                                <td>
                                                    <input type="hidden" name="attendanceAbsenceRequest_id" value="{{ $appliedRequest->id }}">
                                                    {{ ($key+1) }}
                                                </td>
                                                <td>{{ $appliedRequest->teacher->name }}</td>
                                                <td>{{ $appliedRequest->teacher->teacher_number }}</td>
                                                <td>
                                                    <span class="badge @if($appliedRequest->request_type == 'absence') {{ 'badge-danger' }} @elseif($appliedRequest->request_type == 'delay') {{ 'badge-warning' }} @else {{ 'badge-info' }} @endif">{{ $appliedRequest->type }}</span>
                                                </td>
                                                <td>{{ @$appliedRequest->created_at->format('Y-m-d (g:i) A') }}</td>
                                                <td>{{ $appliedRequest->date_excuse }}</td>
                                                <td>{{ @$appliedRequest->classNumber->title }}</td>
                                                <td>
                                                    <span class="badge badge-status-request @if($appliedRequest->status == 'pending') {{ 'badge-danger' }} @elseif($appliedRequest->status == 'processing') {{ 'badge-success' }} @else {{ 'badge-primary' }} @endif">{{ $appliedRequest->status_title }}</span>
                                                </td>
                                                <td>
                                                    @if($appliedRequest->date_excuse < \Carbon\Carbon::now()->format('Y-m-d') || $appliedRequest->status == 'completed')
                                                        <a href="#" class="btn btn-success disabled" data-toggle="modal" data-target="#">
                                                            <i class="fa fa-plus-circle"></i>
                                                        </a>
                                                    @else
                                                        <a href="#" class="btn btn-success add_teacher_to_class_btn disabled" data-toggle="modal" data-target="#add_teacher_to_class">
                                                            <i class="fa fa-plus-circle"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $appliedRequests->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="add_teacher_to_class" role="dialog" aria-labelledby="add_teacher_to_class"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel35"> إستعلام الإذن - تعيين معلم إحتياط</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form" method="POST" enctype="multipart/form-data" id="popup_add_teacher_to_class">
                    @csrf

                    <input type="hidden" name="attendanceAbsenceRequest_id" id="hidden_attendanceAbsenceRequest_id">

                    <div class="modal-body">
                        <fieldset class="form-group floating-label-form-group">
                            <label class="font-weight-bold" for="title">العذر المقدم</label>
                            <p id="reason_excuse" class="black"></p>
                        </fieldset>

                        <fieldset class="form-group floating-label-form-group">
                            <label for="title" class="font-weight-bold">الحلقة</label>
                            <br>
                            <span class="badge badge-primary" id="class_title"></span>
                        </fieldset>

                        <fieldset class="form-group floating-label-form-group">
                            <label for="title" class="block font-weight-bold">المعلم الاحتياطي</label>
                            <select class="form-control select2" name="teacher_id" id="teacher_id" required>
                                <option value=""></option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </fieldset>

                        <label for="title" class="block font-weight-bold">إعتماد وقت اضافي للمعلم</label>
                        <h5 class="success mb-0" id="overtime-checkbox-1">
{{--                            <input type="checkbox" name="overtime_checkbox" id="overtime-checkbox" data-color="success" class="toggle-status switchery" />--}}
                        </h5>
                        <input type="checkbox" name="overtime_checkbox" id="overtime-checkbox" style="width: 25px;height: 25px;"  />

                        <br>

                        <fieldset class="form-group floating-label-form-group">
                            <label class="font-weight-bold">الصلاحية ستتوفر للمعلم حتى</label>
                            <input type="date" name="date_excuse" class="form-control" id="available_to_date" required>
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

    @push('js')
        <script>
            $(document).ready(function() {

                    $('.add_teacher_to_class_btn.disabled').removeClass('disabled');

                    $('.add_teacher_to_class_btn').on('click', function () {

                    let current_row = $(this).closest('tr'),
                        attendanceAbsenceRequest = current_row.find("input[name=attendanceAbsenceRequest_id]").val();
                    $('#hidden_attendanceAbsenceRequest_id').val(attendanceAbsenceRequest);


                    let url = '{{ route("admins.assign.teacher.query", ":attendanceAbsenceRequest") }}';
                    url = url.replace(':attendanceAbsenceRequest', attendanceAbsenceRequest);

                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: url,
                        success: function (data) {
                            $("#add_teacher_to_class span#class_title").html('');
                            $('#add_teacher_to_class #available_to_date').val('');
                            $('form#popup_add_teacher_to_class #reason_excuse').html('');
                            $("form#popup_add_teacher_to_class select[name=teacher_id]").val('');
                            $("form#popup_add_teacher_to_class select[name=teacher_id]").trigger('change');

                            $('form#popup_add_teacher_to_class span#class_title').html(data['class']['title']);

                            $('form#popup_add_teacher_to_class #reason_excuse').html(data['attendanceAbsenceRequest']['reason_excuse']);
                            $('form#popup_add_teacher_to_class #available_to_date').val(data['attendanceAbsenceRequest']['available_to_date']);
                            $("form#popup_add_teacher_to_class select[name=teacher_id]").val(data['attendanceAbsenceRequest']['spare_teacher_id']);
                            $("form#popup_add_teacher_to_class select[name=teacher_id]").trigger('change');

                            $('form#popup_add_teacher_to_class #overtime-checkbox').prop('checked', data['attendanceAbsenceRequest']['is_overtime'] ? true : false);
                        }
                    });
                });

                $(document).on('submit','form#popup_add_teacher_to_class',function(e){
                    e.preventDefault();

                    let available_to_date = $("form#popup_add_teacher_to_class input[name=date_excuse]").val(),
                        teacher_id        = $("form#popup_add_teacher_to_class select[name=teacher_id]").val(),
                        attendanceAbsence_id  = $("form#popup_add_teacher_to_class #hidden_attendanceAbsenceRequest_id").val(),
                        overtime_checkbox  = $("form#popup_add_teacher_to_class #overtime-checkbox").is(':checked');

                    let url = '{{ route("admins.assign.teacher.update", ":attendanceAbsenceRequest") }}';
                        url = url.replace(':attendanceAbsenceRequest', attendanceAbsence_id);

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: url,
                        data: {
                            teacher_id: teacher_id,
                            class_number: teacher_id,
                            attendanceAbsence_id: attendanceAbsence_id,
                            available_to_date: available_to_date,
                            overtime_checkbox: overtime_checkbox,
                            _method: 'PUT',
                        },
                        success: function (data) {
                            $("#add_teacher_to_class").modal('hide');
                            alert("تمت العملية بنجاح");
                            $('.lds-roller').css('display', 'none');
                            $('button[type="submit"]').removeClass('disabled');
                            $('button[type="submit"] i.fa-spinner').css('display', 'none');
                            $('button[type="submit"] i.fa-check').css('display', 'inline-block');

                            let current_row = $('#hidden_attendanceAbsenceRequest_id').val(),
                                row_status = $("#table_appliedRequests input[value='"+ current_row +"']").closest('tr');
                                row_status.find('span.badge-status-request').html(data['attendanceAbsenceRequest']['status_title']);
                                row_status.find('span.badge-status-request').removeClass('badge-danger');
                                row_status.find('span.badge-status-request').addClass('badge-success');
                        }
                    });

                    $('.lds-roller').css('display', 'inline-block');
                    $('button[type="submit"]').addClass('disabled');
                    $('button[type="submit"] i.fa-spinner').css('display', 'inline-block');
                    $('button[type="submit"] i.fa-check').css('display', 'none');
                });

            });
        </script>
    @endpush

@endsection
