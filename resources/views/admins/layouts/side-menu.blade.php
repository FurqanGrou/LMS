<div class="main-menu menu-fixed menu-light menu-accordion  menu-shadow " data-scroll-to-active="true" >
    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="nav-item">
                <a href="{{ route('admins.home') }}">
                    <i class="la la-home"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الرئيسية</span>
                </a>
            </li>

            <li class="nav-item open"><a href="#"><i class="la la-cogs"></i><span class="menu-title" data-i18n="nav.templates.main">التصدير والاستيراد</span></a>
                <ul class="menu-content">
                    <li>

                        @if(isHasUserType('super_admin') || isHasUserType('furqan_group'))
                            <a class="menu-item" href="{{ route('admins.import.online_students.view') }}" data-i18n="nav.templates.vert.classic_menu">
                                <i class="ft-globe"></i>
                                ادخال وتحديث بيانات طلاب - الاونلاين
                            </a>
                        @endif

                        @if(isHasUserType('super_admin') || isHasUserType('iksab'))
                            <a class="menu-item" href="{{ route('admins.import.face_students.view') }}" data-i18n="nav.templates.vert.classic_menu">
                                <i class="ft-users"></i>
                                ادخال وتحديث بيانات طلاب - الحضوري
                            </a>
                        @endif

                            <a class="menu-item" href="{{ route('admins.report.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-file-excel-o"></i>
                            تصدير التقارير اليومية
                        </a>
                        <a class="menu-item" href="{{ route('admins.monthly_scores.export') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-file-excel-o"></i>
                            تصدير التقارير الشهرية
                        </a>
                        <a class="menu-item" href="{{ route('admins.change_send_monthly_report_status.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-send"></i>
                            تفعيل الارسال للشهر السابق
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الحلقات الدراسية</span>
                </a>
                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="{{ route('admins.classes.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="fa fa-users"></i>
                            جميع الحلقات
                        </a>
                        <a class="menu-item" href="{{ route('admins.classes.join_requests') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="fa fa-users"></i>
                            طلبات الانضمام للحلقات
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('admins.student.index') }}">
                    <i class="fa fa-user-graduate"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الطلاب</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admins.admins.index') }}">
                    <i class="ft-award"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الإداريين</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admins.absences.index') }}">
                    <i class="fa fa-chalkboard-teacher"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">غياب الطلاب</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admins.holidays.index') }}">
                    <i class="ft-sliders"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الاجازات</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admins.dropout.students.index') }}">
                    <i class="ft-activity"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">المنقطعين</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admins.request_services.attendanceAbsenceTeachers.index') }}">
                    <i class="ft-sliders"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الاذونات</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#">
                    <i class="ft-log-in"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الحضور والانصراف</span>
                </a>
                <ul class="menu-content">
                    <li>
{{--                        <a class="menu-item" href="{{ route('admins.attendance.index') }}" data-i18n="nav.templates.vert.classic_menu">--}}
{{--                            <i class="ft-log-in"></i>--}}
{{--                            تسجيل الحضور والانصراف--}}
{{--                        </a>--}}
                        <a class="menu-item" href="{{ route('admins.attendance.export') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="ft-file-text"></i>
                            تصدير تقرير الحضور والانصراف
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#">
                    <i class="ft-folder"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">تصدير طلبات الاختبارات</span>
                </a>
                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="{{ route('admins.request_services.exams.export') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="ft-folder"></i>
                            تصدير طلبات الاختبارات
                        </a>
                    </li>
                </ul>
            </li>

            @if(isHasUserType('super_admin'))
                <li class="nav-item">
                    <a href="{{ route('log-viewer::logs.list') }}">
                        <i class="fa fa-bug"></i>
                        <span class="menu-title" data-i18n="nav.templates.main">اخطاء النظام</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('admins.audits.index') }}">
                    <i class="fa fa-server"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">فاعليات المستخدم</span>
                </a>
            </li>

        </ul>

    </div>
</div>
