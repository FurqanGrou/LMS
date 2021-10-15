@extends('teachers.layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ \App\Classes::where('class_number', '=', request('class_number'))->first()->title }}</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card mb-0">
                            <div class="card-header d-flex flex-column pb-0 pt-0">
                                <div class="mb-1">
                                    <span class="btn btn-danger" title="لم يتم إدخال تقرير اليوم!"><i class="la la-file-text"></i>
                                    </span>
                                    <label for="">لم يتم إدخال تقرير اليوم</label>
                                </div>

                                <div class="mb-1">
                                    <span class="btn btn-warning" title="لم يتم ارسال التقرير بعد!"><i class="la la-envelope-o"></i>
                                    </span>
                                    <label for="">لم يتم ارسال التقرير بعد</label>
                                </div>
                                <div>
                                    <span class="btn btn-success" title="تم ارسال التقرير"><i class="la la-envelope-o"></i>
                                    </span>
                                    <lable>تم ارسال التقرير</lable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('teachers.partials.success')

                <div class="card-content collapse show">

                    <div class="card-body card-admins">
{{--                        <h3>عدد التقارير المتبقي إدخاله: <span class="danger">{{ $remaining }}</span></h3>--}}
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

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

    @endpush

@endsection
