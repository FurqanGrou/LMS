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
{{--                        <a class="dropdown-item" href="#">--}}
{{--                            <i class="ft-user"></i>--}}
{{--                            تعديل بياناتي--}}
{{--                        </a>--}}

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
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
