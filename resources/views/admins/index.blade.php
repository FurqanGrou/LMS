@extends('admins.layouts.master')

@section('content')
    <div id="crypto-stats-3" class="row">

        {{--Admins--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-award font-large-1" title="الإداريين"></i></h1>
                            </div>
                            <div class="col-6 pl-2">
                                <h4>الإداريين</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h4>{{ $statistics['admins'] }}</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{--Teachers--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-book font-large-1" title="Users"></i></h1>
                            </div>
                            <div class="col-6 pl-2">
                                <h4>المعلمين</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h4>{{ $statistics['teachers'] }}</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{--Classes--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-grid font-large-1" title="الحلقات"></i></h1>
                            </div>
                            <div class="col-6 pl-2">
                                <h4>الحلقات</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h4>{{ $statistics['classes'] }}</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{--Users--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-users font-large-1" title="Users"></i></h1>
                            </div>
                            <div class="col-6 pl-2">
                                <h4>إجمالي الطلاب</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h4>{{ $statistics['students'] }}</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{--Regular Users--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-users font-large-1" title="Users"></i></h1>
                            </div>
                            <div class="col-6 pl-2">
                                <h4>الطلاب المنتظمين</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h4>{{ $statistics['regular_students'] }}</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 border-bottom-blue mb-2"></div>

        {{--Last Report--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-file-text font-large-1" title="وقت إدخال أخر تقرير"></i></h1>
                            </div>
                            <div class="col-6">
                                <h4>وقت إدخال أخر تقرير</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h5 class="danger">{{ $statistics['last_report'] }}</h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{--Sent Messages--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-file-text font-large-1" title="الايميلات المرسلة"></i></h1>
                            </div>
                            <div class="col-6">
                                <h4>الايميلات المرسلة</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h5 class="danger">{{ $statistics['sent_messages'] }}</h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{--Not Sent Messages--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-file-text font-large-1" title="الايميلات غير المرسلة"></i></h1>
                            </div>
                            <div class="col-6">
                                <h4 style="font-size: 17px">الايميلات غير المرسلة</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h5 class="danger">{{ $statistics['not_sent_messages'] }}</h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
