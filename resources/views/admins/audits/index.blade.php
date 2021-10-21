@extends('admins.layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">جميع التفاعلات</h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <form action="/dashboard-admins/admins/audits" method="get">
                <select name="teacher">
                    @foreach(App\Teacher::get() as $tech)
                    <option value="{{$tech->id}}">{{$tech->name}}</option>
                    @endforeach
                </select>
                <button type="submit">بحث</button>
            </form>

            <div class="card-content collapse show">
                <div class="card-body card-admins">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <table id="tables" class="table table-striped table-bordered dataTable">
                            <tbody>
                                @foreach ($audits as $audit)
                                <tr>
                                    <td> <span>{{$audit->created_at}}</span> <strong>{{$audit->user->name}}</strong>  <span>{{substr($audit->auditable_type,4)}}</span> <strong>ID</strong> <span>{{$audit->auditable_id}}</span></td>
                                    <td>
                                    <strong>{{$audit->event}}</strong><br>    
                                    @foreach ($audit->getModified() as $attribute => $modified)
                                        <ul>
                                            <li> @if (isset($modified['new']))
                                                {{$modified['new']}}
                                                @endif
                                                <strong>-</strong>
                                                @if (isset($modified['old']))
                                                {{$modified['old']}}
                                                @endif
                                            </li>
                                        </ul>
                                        @endforeach
                                    </td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="box-body">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection