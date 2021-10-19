<div class="main-menu menu-fixed menu-light menu-accordion  menu-shadow " data-scroll-to-active="true" >
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class=" nav-item"><a href="#"><i class="la la-paste"></i><span class="menu-title" data-i18n="nav.templates.main">الحلقات الدراسية</span></a>
                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="{{ route('teachers.teacher.index') }}" data-i18n="nav.templates.vert.classic_menu">عرض حلقاتي</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{ route('teachers.classes.index') }}" data-i18n="nav.templates.vert.classic_menu">عرض جميع الحلقات</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('teachers.attendance.index') }}">
                    <i class="ft-log-in"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الحضور والانصراف</span>
                </a>
            </li>

            <li class=" nav-item"><a href="#"><i class="la la-question"></i><span class="menu-title" data-i18n="nav.templates.main">المساعدة</span></a>
                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="{{ route('teachers.teacher.index') }}" data-i18n="nav.templates.vert.classic_menu">كيف تقوم بـ٢</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{ route('teachers.teacher.index') }}" data-i18n="nav.templates.vert.classic_menu">كيف تقوم بـ٣</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{ route('teachers.teacher.index') }}" data-i18n="nav.templates.vert.classic_menu">كيف تقوم بـ٤</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{ route('teachers.teacher.index') }}" data-i18n="nav.templates.vert.classic_menu">كيف تقوم بـ٥</a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</div>
