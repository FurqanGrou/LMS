@extends('admins.layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    @if(empty($student))
                        <h4 class="card-title black font-weight-bold">
                            جميع التعديلات على تقارير الطلاب
                        </h4>
                    @else
                        <h4 class="card-title black font-weight-bold">
                            جميع التعديلات على تقارير الطالب/ة <span class="badge badge-primary font-weight-bold">{{ $student->name }}</span>
                        </h4>
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
                <form action="{{ route('admins.audits.index') }}" method="GET" class="m-auto">
                    <div class="row">
                        <div class="col-4">
                            <div>
                                <label for="teacher" class="font-weight-bold">من تاريخ:</label>
                            </div>
                            <input type="date" name="date_from" class="form-control black" value="{{ request()->date_from }}" required>
                        </div>
                        <div class="col-4">
                            <div>
                                <label for="teacher" class="font-weight-bold">إلى تاريخ:</label>
                            </div>
                            <input type="date" name="date_to" class="form-control black" value="{{ request()->date_to }}" required>
                        </div>

                        <div class="col-4">
                            <div>
                                <label for="student_id" class="font-weight-bold">الطالب:</label>
                            </div>
                            <select name="student_id" id="student_id" class="form-control select2 black">
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <dvi class="col-2">
                            <button type="submit" class="btn btn-primary form-control white mt-1">بحث</button>
                        </dvi>
                    </div>
                </form>

                <div class="card-content collapse show">
                    <div class="card-body card-admins">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <table id="tables" class="table table-bordered table-striped table-hover">
                                <thead class="black">
                                <tr>
                                    <th>#</th>
                                    <th>تاريخ التعديل</th>
                                    <th>بواسطة</th>
                                    <th>نوع العملية</th>
                                    <th>البيانات المعدلة</th>
                                </tr>
                                </thead>
                                <tbody class="font-weight-bold black">

                                @forelse($audits as $audit)
                                    <tr>
                                        <td>
                                            {{ $audit->auditable_id }}
                                        </td>
                                        <td>
                                            {{ $audit->created_at->timezone('asia/riyadh')->format('A g:i - Y-m-d') }}
                                        </td>
                                        <td>
                                            {{ @$audit->user->name }}
                                        </td>
                                        <td>
                                            {{ $audit->event }}
                                        </td>
                                        <td style="width: 40%">
                                            @foreach ($audit->getModified() as $attribute => $modified)
                                                <ul>
                                                    <li class="font-weight-bold"> مدخل: {{ $attribute }}</li>
                                                    <li class="bg-danger white font-weight-bold w-50" style="padding: 5px; margin: 2px; border-radius: 5px;">
                                                        القيمة السابقة:
                                                        <br>
                                                        @if(isset($modified['old']))
                                                            {{$modified['old']}}
                                                        @endif
                                                    </li>
                                                    <li class="bg-success white font-weight-bold w-50" style="padding: 5px; margin: 2px; border-radius: 5px; background-color: #05c581 !important">
                                                        القيمة الجديدة:
                                                        <br>
                                                        @if(isset($modified['new']))
                                                            {{$modified['new']}}
                                                        @endif
                                                    </li>
                                                </ul>
                                            @endforeach
                                        </td>

                                    </td>
                                @empty
                                    <h4 class="danger">لا يوجد نتائج بحث متوفرة</h4>
                                @endforelse
                                </tbody>
                            </table>

                            {{ $audits->withQueryString()->links() }}
                            <div class="box-body">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
