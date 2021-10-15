@extends('students.layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">بيانات الطالب</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>

                @include('students.partials.success')

                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">

                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                            <div class="row">
                                <div class="col-sm-12"><table class="table table-striped table-bordered zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 126.531px;">الاسم</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 202.375px;">رقم الطالب</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 92.2031px;">رابط الحلقة</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 92.2031px;">اسم المعلم</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 28.8438px;">اللغة</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 76.9844px;">الحالة</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 73.4688px;">القسم</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 73.4688px;">وقت الدخول</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 73.4688px;">المسار</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{ auth()->user()->name }}</td>
                                                <td>{{ auth()->user()->student_number }}</td>
                                                <td>
                                                    <a href="{{ $zoom_link }}" target="_blank">فتح الحلقة</a>
                                                </td>
                                                <td>{{ $teacher_name }}</td>
                                                <td>{{ auth()->user()->language }}</td>
                                                <td>{{ auth()->user()->status }}</td>
                                                <td>{{ auth()->user()->section }}</td>
                                                <td>{{ auth()->user()->login_time }}</td>
                                                <td>{{ auth()->user()->path }}</td>
                                            </tr>
                                        </tbody>

=                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>            </div>
        </div>
    </div>

    @push('js')


    @endpush

@endsection
