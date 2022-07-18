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
                                                قائمة الطلاب المنقطعين عن الحضور
                                            </h4>

                                        </fieldset>
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
            $(document).ready(function (){
                $(document).on('submit', '.form-send-mail', function (e){
                   e.preventDefault();
                   let student_id = $(this).find('.student_id').val(),
                       message_id = $(this).find('.alert-message').val();
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            student_id,
                            message_id,
                        },
                        url: '{{ route('admins.dropout.student.send.alert') }}',
                        success: function (data) {
                            alert('تم ارسال التنبيه بنجاح');
                        },
                        error: function (data){
                        }
                    });
               }) ;
            });
        </script>
    @endpush

@endsection
