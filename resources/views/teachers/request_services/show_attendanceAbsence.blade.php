@extends('teachers.layouts.master')

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

                            <div class="table-responsive">
                                <table class="table">

                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>نوع الطلب</th>
                                            <th>تاريخ الإذن المطلوب</th>
                                            <th>تاريخ تقديم الطلب</th>
                                            <th>حالة الطلب</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($appliedRequests as $key => $appliedRequest)
                                        <tr class="" style="background: lemonchiffon; color: #0a001f; font-weight: bold">
                                            <td>{{ ($key+1) }}</td>
                                            <td>
                                                <span class="badge @if($appliedRequest->request_type == 'absence') {{ 'badge-danger' }} @elseif($appliedRequest->request_type == 'delay') {{ 'badge-warning' }} @else {{ 'badge-info' }} @endif">{{ $appliedRequest->type }}</span>
                                            </td>
                                            <td>{{ $appliedRequest->date_excuse }}</td>
                                            <td>{{ $appliedRequest->created_at->format('g:i A Y-m-d') }}</td>
                                            <td>
                                                <span class="badge badge-status-request @if($appliedRequest->status == 'pending') {{ 'badge-success' }} @elseif($appliedRequest->status == 'processing') {{ 'badge-warning' }} @elseif($appliedRequest->status == 'canceled') {{ 'badge-danger' }} @else {{ 'badge-primary' }} @endif">{{ $appliedRequest->status_title }}</span>
                                            </td>
                                            <td class="d-flex" style="border-top: 1px solid #E3EBF3;">
                                                <div class="form-group" title="عرض الطلب" data-toggle="tooltip" data-placement="top">
                                                    <a href="{{ route('teachers.request_services.attendanceAbsenceTeachers.show', $appliedRequest) }}" class="btn btn-info mr-1">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>

                                                @if($appliedRequest->date_excuse < \Carbon\Carbon::today())

                                                    <div class="form-group" title="تعديل الطلب" data-toggle="tooltip" data-placement="top">
                                                        <a href="#" class="btn btn-warning disabled mr-1">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </div>

                                                    <div class="form-group" title="إلغاء الطلب" data-toggle="tooltip" data-placement="top">
                                                        <input type="checkbox" id="switcherySize3" class="switchery" disabled data-size="xs" {{ $appliedRequest->status == 'canceled' ? 'checked' : '' }} />
                                                    </div>
                                                @else
                                                    <div class="form-group" title="تعديل الطلب" data-toggle="tooltip" data-placement="top">
                                                        <a href="{{ route('teachers.request_services.attendanceAbsenceTeachers.edit', $appliedRequest) }}" class="btn btn-warning mr-1">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </div>
                                                    <div class="form-group cancel-request" title="إلغاء الطلب" data-toggle="tooltip" data-placement="top">
                                                        <input type="checkbox" id="switcherySize3" class="switchery" data-size="xs" {{ $appliedRequest->status == 'canceled' ? 'checked' : '' }} data-request-id="{{ $appliedRequest->id }}"/>
                                                    </div>
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


    @push('js')

        <script>
            $(document).ready(function() {
                $(document).on('click', 'div.cancel-request', function (e) {

                    let element = $(this).parent();

                    let url = '{{ route("teachers.request_services.cancel-request", ":attendanceAbsenceRequests") }}';
                        url = url.replace(':attendanceAbsenceRequests', element.find('.switchery').data('request-id'));

                    let request_status = element.find('.switchery:checked').length > 0;

                    var fd = new FormData();

                    // Append data
                    fd.append('status', request_status);

                    $.ajax({
                        type: "POST",
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        url: url,
                        data: fd,
                        success: function (data) {
                            alert('تم تقديم الطلب بنجاح');
                        },
                        error: function (data) {

                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
