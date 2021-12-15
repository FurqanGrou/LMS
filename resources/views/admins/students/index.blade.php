@extends('admins.layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admins.classes.students')
                        @php
                            $teacher_email = \App\ClassesTeachers::query()->where('class_number', '=', request('class_number'))->where('role', '=', 'main')->first()->teacher_email ?? '';
                            $supervisor_email = \App\ClassesTeachers::query()->where('class_number', '=', request('class_number'))->where('role', '=', 'supervisor')->first()->teacher_email ?? null;
                        @endphp

                        <h4 class="card-title">{{ \App\Classes::where('class_number', '=', request('class_number'))->first()->title }}</h4>
                        <h4 class="card-title">المعلم: {{ \App\Teacher::query()->where('email', '=', $teacher_email)->first()->name }}</h4>
                        <h4 class="card-title">المراقب: {{ ($supervisor_email) ? \App\Teacher::query()->where('email', '=', $supervisor_email)->first()->name : 'لا يوجد' }}</h4>
                    @endif

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

        <script>
            $(document).ready(function (){
                setTimeout(function (){
                    var secondFraction = '3.5'
                    $('.js--bar-animated').css('animation', secondFraction + 's linear 0s normal none infinite progress-bar-stripes');
                }, 10000);
            });
        </script>

    @endpush

@endsection
