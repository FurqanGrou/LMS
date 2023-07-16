<div class="main-menu menu-fixed menu-light menu-accordion  menu-shadow " data-scroll-to-active="true" >
    <div class="main-menu-content ps-container ps-theme-dark ps-active-y">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="nav-item">
                <a href="{{ route('admins.home') }}">
                    <i class="la la-home"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الرئيسية</span>
                </a>
            </li>

            <li class="nav-item open">
                <a href="#">
                    <i class="la la-cogs"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">التصدير والاستيراد</span>
                </a>
                <ul class="menu-content">
                    <li>

                        <a class="menu-item" href="{{ route('admins.import.students.view') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="ft-users"></i>
                            ادخال وتحديث بيانات الطلاب
                        </a>

                        <a class="menu-item" href="{{ route('admins.report.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-file-excel-o"></i>
                            تصدير التقارير اليومية
                        </a>

                        <a class="menu-item" href="{{ route('admins.monthly_scores.export') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-file-excel-o"></i>
                            تصدير التقارير الشهرية
                        </a>
                        <a class="menu-item" href="{{ route('admins.export.commitment-report.view') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-file-excel-o"></i>
                            تصدير تقرير الإلتزام
                        </a>
                        <a class="menu-item" href="{{ route('admins.import-dropout-dates.view') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-file-excel-o"></i>
                            تحديث تواريخ الإنقطاع
                        </a>
                        <a class="menu-item" href="{{ route('admins.export.regular-students-report.view') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-file-excel-o"></i>
                            تصدير تقرير الطلاب المنتظمين
                        </a>
                        <a class="menu-item" href="{{ route('admins.change_send_monthly_report_status.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-send"></i>
                            تفعيل الارسال للشهر السابق
                        </a>
                        <a class="menu-item" href="{{ route('admins.enable_teachers_update.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-send"></i>
                            تفعيل تعديلات الشهر السابق
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
                <a href="#">
                    <i class="fa fa-user-graduate"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الطلاب والملاحظات</span>
                </a>

                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="{{ route('admins.student.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="fa fa-user-graduate"></i>
                            الطلاب
                        </a>
                        <a class="menu-item" href="{{ route('admins.note-parents.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="fa fa-book"></i>
                            ملاحظات أولياء الأمور
                        </a>
                    </li>
                </ul>

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
                <a href="#">
                    <i class="ft-file-text"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">التقارير</span>
                </a>
                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="{{ route('admins.request_services.attendanceAbsenceTeachers.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            الاذونات
                        </a>
                    </li>
                    @if(isHasUserType('super_admin'))
                        <li>
                            <a class="menu-item" href="{{ route('admins.export-top-tracker-reports') }}" data-i18n="nav.templates.vert.classic_menu">
                                حضور الموظفين - البصمة
                            </a>
                        </li>
                        <li>
                            <a class="menu-item" href="{{ route('admins.attendance.export') }}" data-i18n="nav.templates.vert.classic_menu">
                                الحضور والانصراف - قديم
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="menu-item" href="{{ route('admins.request_services.exams.export') }}" data-i18n="nav.templates.vert.classic_menu">
                            طلبات الاختبارات
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">

                <a href="#">
                    <i class="ft-activity"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">قسم المنقطعين</span>
                </a>

                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="{{ route('admins.dropout.students.index') }}">
                            <i class="ft-activity"></i>
                            الطلاب المنقطعين
                        </a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{ route('admins.alert-messages.index') }}">
                            <i class="ft-message-square"></i>
                            رسائل المنقطعين
                        </a>
                    </li>
                </ul>

            </li>

            @if(isHasUserType('super_admin'))

                <li class=" nav-item">
                    <a href="#">
                        <i class="fa fa-bug"></i>
                        <span class="menu-title" data-i18n="nav.navbars.main">مراقبة النظام</span>
                    </a>
                    <ul class="menu-content">
                        <li>
                            <a class="menu-item" href="{{ route('log-viewer::logs.list') }}" data-i18n="nav.navbars.nav_light">اخطاء النظام</a>
                        </li>
                        <li>
                            <a class="menu-item" href="{{ route('admins.audits.index') }}" data-i18n="nav.navbars.nav_light">حركات المستخدم</a>
                        </li>
                    </ul>
                </li>

            @endif

        </ul>

    </div>
</div>
