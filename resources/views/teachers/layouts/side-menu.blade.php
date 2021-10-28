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

            <li class=" nav-item"><a href="#"><i class="la la-question"></i><span class="menu-title" data-i18n="nav.templates.main">المساعدة</span></a>
                <ul class="menu-content">
                    <li>
                        <a class="menu-item" target="_blank" href="https://share.vidyard.com/watch/hnRsxmrxJXQsTdDjSMniGV" data-i18n="nav.templates.vert.classic_menu">التحديثات الجديدة</a>
                    </li>
                    <li>
                        <a class="menu-item" target="_blank" href="https://share.vidyard.com/watch/gfh3qmhCCUyj3YGHTksX9F" data-i18n="nav.templates.vert.classic_menu">كيف تقوم بارسال دليل جديد</a>
                    </li>
                </ul>
            </li>

            <li class=" nav-item">
                <a href="#">
                    <i class="la la-folder"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">الشكاوى والاقتراحات</span>
                </a>
                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="{{ route('teachers.request_services.index') }}" data-i18n="nav.templates.vert.classic_menu">عرض الشكاوى والاقتراحات المقدمة</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{ route('teachers.request_services.create') }}" data-i18n="nav.templates.vert.classic_menu">تقديم شكوى أو مقترح</a>
                    </li>
                </ul>
            </li>

            <li class=" nav-item">
                <a href="#">
                    <i class="la la-file-text"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">طلبات الاختبارات</span>
                </a>
                <ul class="menu-content">
                    <li>
                        <a class="menu-item" href="#" data-i18n="nav.templates.vert.classic_menu">عرض الطلبات المقدمة</a>
                    </li>
                    <li>
                        <a class="menu-item" href="{{ route('teachers.request_services.exam.create') }}" data-i18n="nav.templates.vert.classic_menu">طلب اختبار</a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</div>
