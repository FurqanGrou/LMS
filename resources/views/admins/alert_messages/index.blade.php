@extends('admins.layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">رسائل تنبيه الطلاب المنقطعين</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>

                @include('admins.partials.success')

                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">

                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                            <div class="table-responsive">
                                <table class="table">

                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>التنبيه - عربي</th>
                                            <th>التنبيه - EN</th>
                                            <th>رقم التنبيه</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>

                                    <tbody id="table_appliedRequests">
                                        @foreach($messages as $key => $message)
                                            <tr class="" style="background: lemonchiffon; color: #0a001f; font-weight: bold" id="{{ $message->id }}">
                                                <td>
                                                    {{ ($key+1) }}
                                                </td>
                                                <td class="msg-content">{{ \Illuminate\Support\Str::limit($message->content, 50)  }}</td>
                                                <td class="msg-content-en">{{ \Illuminate\Support\Str::limit($message->content_en, 50)  }}</td>
                                                <td>
                                                    <span class="badge badge-danger badge-warning badge-info">{{ $message->level }}</span>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-warning edit-alert-message-btn disabled" data-toggle="modal" data-target="#edit-alert-message-modal" data-message-id="{{ $message->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade text-left" id="edit-alert-message-modal" tabindex="-1" role="dialog" aria-labelledby="editAlertMessage" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning white">
                    <h4 class="modal-title white" id="editAlertMessage">
                        <i class="la la-tree"></i>
                        تعديل التنبيه
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" data-alert-message="">
                    <div class="modal-body">
                        <div class="alert alert-success d-none" role="alert">تم التعديل بنجاح</div>
                        <div class="alert alert-danger d-none" role="alert">يجب عليك التأكد من جميع البيانات</div>
                        <div class="row">
                            <div class="form-group col-12 mb-2">
                                <label for="message_content">نص التنبيه - عربي</label>
                                <textarea class="form-control border-primary" placeholder="نص التنبيه - عربي" id="message_content"></textarea>
                            </div>
                            <div class="form-group col-12 mb-2">
                                <label for="message_content_en">نص التنبيه - EN</label>
                                <textarea class="form-control border-primary" placeholder="نص التنبيه - EN" id="message_content_en"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-outline-warning">حفظ التغيرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {

                $('.edit-alert-message-btn').removeClass('disabled');

                $('.edit-alert-message-btn').on('click', function () {

                    let alert_message_id = $(this).data('message-id');

                    let url = '{{ route("admins.alert-messages.edit", ":alertMessage") }}';
                    url = url.replace(':alertMessage', alert_message_id);

                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: url,
                        success: function (data) {
                            $('#edit-alert-message-modal form #message_content').html(data['content']);
                            $('#edit-alert-message-modal form #message_content_en').html(data['content_en']);
                            $('#edit-alert-message-modal form').attr('data-alert-message', data['id']);
                        }
                    });
                });

                $(document).on('submit','#edit-alert-message-modal form',function(e){
                    e.preventDefault();

                    let alert_message_id = $(this).attr('data-alert-message');
                    let this_form = $('#table_appliedRequests tr#' + alert_message_id);
                    let message_content = $('#edit-alert-message-modal form #message_content').val();
                    let message_content_en = $('#edit-alert-message-modal form #message_content_en').val();
                    let url = '{{ route("admins.alert-messages.update", ":alert_messages") }}';
                        url = url.replace(':alert_messages', alert_message_id);

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: url,
                        data: {
                            message_content,
                            message_content_en,
                            _method: 'PUT',
                        },
                        success: function (data) {
                            $('#edit-alert-message-modal form .alert-danger').addClass('d-none');
                            $('#edit-alert-message-modal form .alert-success').removeClass('d-none');
                            this_form.find('.msg-content').html(data['content'].length > 20 ? data['content'].slice(0, 20) + "…" : data['content']);
                            this_form.find('.msg-content-en').html(data['content_en'].length > 20 ? data['content_en'].slice(0, 20) + "…" : data['content_en']);

                            setTimeout(function (){
                                $("#edit-alert-message-modal").modal('hide');
                                $('#edit-alert-message-modal form .alert-success').addClass('d-none');
                            }, 1500);
                        },
                        error: function (data){
                            $('#edit-alert-message-modal form .alert-success').addClass('d-none');
                            $('#edit-alert-message-modal form .alert-danger').removeClass('d-none');
                        }
                    });

                });

                $(document).on('submit', 'form.form-send-mail', function(e){
                    e.preventDefault();

                    alert('test');

                    let alert_message_id = $(this).attr('data-alert-message');
                    let this_form = $('#table_appliedRequests tr#' + alert_message_id);
                    let message_content = $('#edit-alert-message-modal form #message_content').val();
                    let url = '{{ route("admins.alert-messages.update", ":alert_messages") }}';
                    url = url.replace(':alert_messages', alert_message_id);

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: url,
                        data: {
                            message_content,
                            _method: 'PUT',
                        },
                        success: function (data) {
                            $('#edit-alert-message-modal form .alert-danger').addClass('d-none');
                            $('#edit-alert-message-modal form .alert-success').removeClass('d-none');
                            this_form.find('.msg-content').html(data['content'].length > 20 ? data['content'].slice(0, 20) + "…" : data['content']);
                            this_form.find('.msg-content-en').html(data['content_en'].length > 20 ? data['content_en'].slice(0, 20) + "…" : data['content_en']);

                            setTimeout(function (){
                                $("#edit-alert-message-modal").modal('hide');
                                $('#edit-alert-message-modal form .alert-success').addClass('d-none');
                            }, 1500);
                        },
                        error: function (data){
                            $('#edit-alert-message-modal form .alert-success').addClass('d-none');
                            $('#edit-alert-message-modal form .alert-danger').removeClass('d-none');
                        }
                    });

                });

            });

        </script>
    @endpush

@endsection



