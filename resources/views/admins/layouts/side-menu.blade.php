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
                        <a class="menu-item" href="{{ route('admins.import.students.view') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-cloud-upload"></i>
                            ادخال وتحديث بيانات الطلاب
                        </a>
                        <a class="menu-item" href="{{ route('admins.report.index') }}" data-i18n="nav.templates.vert.classic_menu">
                            <i class="la la-file-excel-o"></i>
                            تصدير التقارير
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#">
                    <i class="la la-cogs"></i>
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
                <a href="{{ route('log-viewer::logs.list') }}">
                    <i class="fa fa-bug"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">اخطاء النظام</span>
                </a>
            </li>

        </ul>

    </div>
</div>
