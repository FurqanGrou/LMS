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
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 container">

                            <div class="box-body row">
                                <div class="col-4">
                                    <form action="">
                                        <fieldset class="form-group">

                                            <h4 class="form-section">
                                                <i class="fa fa-chalkboard-teacher"></i>
                                                قائمة غيابات الطلاب اليومية
                                            </h4>

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">عرض حسب التاريخ</span>
                                                </div>
                                                <input type="date" class="form-control" id="month_report" name="date_filter" value="{{ request()->date_filter }}" aria-label="التاريخ من">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                                <div class="col-8">
                                    <form class="form" method="POST" action="{{ route('admins.absence.export') }}" enctype="multipart/form-data">

                                        @csrf
                                        @method('POST')

                                        <div class="form-body">
                                            <h4 class="form-section">
                                                <i class="la la-file-excel-o"></i>
                                                تصدير تقرير شامل بالغياب حسب التاريخ
                                            </h4>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">من</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="date_from" aria-label="التاريخ من" required>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">إلى</span>
                                                        </div>
                                                        <input type="date" class="form-control" name="date_to" aria-label="التاريخ إلى" required>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">حالة الغياب</span>
                                                        </div>
                                                        <select name="absence_status" id="" class="form-control">
                                                            <option value="">الكل</option>
                                                            <option value="-2">بعذر</option>
                                                            <option value="-5">بدون عذر</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-file"></i>
                                                تصدير
                                            </button>

                                        </div>

                                    </form>
                                </div>
                            </div>

                            <div class="box-body">
                                {!! $dataTable->table(['class' => 'table table-striped table-bordered dataTable', 'id' => 'DataTables_Table_4', 'style' => 'width:100%']) !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')

        {!! $dataTable->scripts() !!}

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

                // fire update monthly scores event
                let report_date = $('#report_date_input').val();
                let created_at = '{{ request()->date_filter ? substr(request()->date_filter, 0, -3) : \Carbon\Carbon::now()->format('Y-m') }}';
                let student_id = $('#report_student_id').val();

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '{{ route('admins.report.updateMonthlyScoresEvent') }}',
                    data: {
                        'student_id': student_id,
                        'created_at': created_at,
                        'report_date': report_date,
                    },
                });

            });

            $(document).on('change', 'form input#month_report', function (e) {
                window.location.href = "{{ route('admins.absences.index') }}?date_filter=" + $(this).val() + "";
            })
        </script>
    @endpush

@endsection
