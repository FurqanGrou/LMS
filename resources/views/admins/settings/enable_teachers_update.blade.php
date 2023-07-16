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

    select + .select2.select2-container {
        width: 85% !important;
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
                                <div class="col-12">
                                    <form class="form" method="POST" action="{{ route('admins.enable_teachers_update.store') }}" enctype="multipart/form-data">

                                        @csrf
                                        @method('POST')

                                        <div class="form-body">
                                            <h4 class="form-section">
                                                <i class="la la-file-excel-o"></i>
                                                تعيين المعلمين المتاح لهم تعديل بيانات الشهر السابق
                                            </h4>

                                            <div class="row">

                                                <div class="col-12">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text" id="basic-addon1">المعلمين</span>
                                                        <select name="teachers_id[]" class="form-control js-example-basic-multiple" multiple="multiple" required>
                                                            <option value="-1">الكل</option>
                                                            @foreach($teachers as $teacher)
                                                                <option value="{{ $teacher->id }}" {{ $teacher->update_previous_month ? 'selected' : '' }}>{{ $teacher->name . ' - ' . $teacher->teacher_number }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-file"></i>
                                                حفظ
                                            </button>

                                        </div>

                                    </form>
                                </div>
                            </div>

                            <div class="box-body">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();

            $(document).on('change', 'select[name="teachers_id[]"]', function() {
                if($(this).val().includes('-1') && $(this).val().length > 1){
                    $('.js-example-basic-multiple').val(null).trigger('change');
                    $('.js-example-basic-multiple').val('-1');
                    $('.js-example-basic-multiple').trigger('change');
                }
            });
        });
    </script>

@endpush
