@extends('admins.layouts.master')

    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 45px;
            height: 20px;
            vertical-align: middle;
            margin-top: 8px;
        }

        .switch input {display:none;}

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #A1A6AB;
            -webkit-transition: .4s;
            transition: .4s;

        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 14px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: green;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">قائمة غيابات الطلاب اليومية</h4>
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

                            <div class="box-body">
                                <div class="col-4">
                                    <form action="">
                                        <fieldset class="form-group">
                                            <input type="date" class="form-control" id="month_report" name="date_filter" value="{{ request()->date_filter }}">
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <div class="box-body">
                                {!! Form::open(['id' => 'form_data_delete', 'url' => route('admins.admins.deleteAll'), 'method' => 'delete']) !!}
                                {!! $dataTable->table(['class' => 'table table-striped table-bordered dataTable', 'id' => 'DataTables_Table_4', 'style' => 'width:100%']) !!}
                                {!! Form::close() !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')

        {!! $dataTable->scripts() !!}

        <script type="text/javascript">
            delete_all();
        </script>

        <script>
            $(document).on('change', '.toggle-absence-type', function() {
                var absence_type = $(this).prop('checked') == true ? -2 : -5;
                var report_id = $(this).closest('label').find('.report_id').val();
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('admins.absence.type', ['date_filter' => request()->date_filter]) }}',
                    data: {'absence_type': absence_type, 'report_id': report_id},
                    success: function (data) {
                        // console.log(data.status);
                    }
                });
            });

            $(document).on('change', 'form input#month_report', function (e) {
                window.location.href = "{{ route('admins.absences.index') }}?date_filter=" + $(this).val() + "";
            })
        </script>
    @endpush

@endsection

{{-- Modal --}}
<div class="modal fade text-left" id="delete-all-admins" tabindex="-1" role="dialog" aria-labelledby="myModalLabel10" aria-hidden="true" style="display: none; z-index: 999999999">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger white">
                <h4 class="modal-title white" id="myModalLabel10">حذف المشرفين</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <div class="empty_record hidden">
                        <h4>يرجى تحديد المشرفين المراد حذفهم</h4>
                    </div>
                    <div class="not_empty_record hidden">
                        <h4>هل أنت متأكد من حذف المشرفين والذي عددهم <span class="record_count"></span> ? </h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <div class="empty_record hidden">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="not_empty_record hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">لا</button>
                    <input type="submit"  value="حسنا"  class="btn btn-outline-danger delete_all_submit" />
                </div>

            </div>
        </div>
    </div>
</div>
