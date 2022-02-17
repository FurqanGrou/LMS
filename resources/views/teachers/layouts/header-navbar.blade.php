<!-- fixed-top-->
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto">
                    <a class="nav-link nav-menu-main menu-toggle hidden-xs" id="test1" href="#">
                        <i class="ft-menu font-large-1"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="navbar-brand" href="#">
                        <img class="brand-logo" alt="modern admin logo" src="{{ asset('dashboard/app-assets/images/logo/logo.png') }}">
                        <h3 class="brand-text">الفرقان لتعليم القرآن</h3>
                    </a>
                </li>
                <li class="nav-item d-md-none">
                    <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
                            <i class="ft-menu"></i>
                        </a>
                    </li>
                </ul>

                @if(\Route::currentRouteName() == 'teachers.teacher.index')
                    <div style="width: 320px; margin: auto; margin-top: 20px; max-width: 320px; display: flex; justify-content: center;">
                        <x-attendance></x-attendance>
                    </div>
                @endif

                @if(\Route::currentRouteName() == 'teachers.report.table')
                    <div style="width: 320px; margin: auto; margin-top: 20px; max-width: 320px; display: flex; justify-content: center;">

                    <!--<form id="form-send-daily-report" method="POST" action="{{ route('teachers.send.report', request()->student_id . '?date_filter=' . request()->date_filter) }}" class="mr-5">-->
                        <!--    @csrf-->
                    <!--    <input type="hidden" name="student_id" value="{{ request()->student_id }}">-->
                        <!--    <input type="submit" class="btn" style="color:#0a0e45;background: lemonchiffon !important;" id="btn-send-report" value="ارسال التقرير اليومي">-->
                        <!--</form>-->

                        @if(isAvailableToSendMonthlyReport(request()->date_filter))
{{--                            <form method="POST" id="monthly_report-monthly" action="{{ route('teachers.send.report.monthly', request()->student_id . '?date_filter=' . date('Y') . '-' . date('m')) }}">--}}
                            <form method="POST" id="monthly_report-monthly" style="width: 100%;display: flex;justify-content: space-between;align-items: center;" action="{{ route('teachers.send.report.monthly', request()->student_id . '?date_filter=' . getReportMonth()) }}">
                                @csrf
                                <a href="{{ route('teachers.report.table', request()->student_id . '?date_filter=' . getReportMonth()) }}" class="btn btn-danger" style="display: inline-block;margin-left: 5px;">عرص تقرير شهر -
                                    {{ getReportMonth() }}</a>
                                <input type="submit" class="btn" style="color:#0a0e45;background: lavenderblush !important;" id="btn-send-report-monthly" value="ارسال التقرير الشهري">
                            </form>
                        @endif
                    </div>
                @endif


                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="mr-1">مرحبا،
                              <span class="user-name text-bold-700">{{ auth()->user()->name }}</span>
                            </span>
                            <span class="avatar avatar-online">
                              <img src="{{ asset('dashboard/app-assets/images/portrait/small/avatar-s-19.png') }}" alt="avatar"><i></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item" href="{{ route('teachers.change_password.view', auth()->guard('teacher_web')->user()->id) }}">
                                    <i class="ft-user"></i>
                                    تغيير كلمة المرور
                                </a>

                                <a class="dropdown-item" id="logout-button" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ft-power"></i>
                                    تسجيل خروج
                                </a>

                                <form id="logout-form" action="{{ route('teachers.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- ////////////////////////////////////////////////////////////////////////////-->
