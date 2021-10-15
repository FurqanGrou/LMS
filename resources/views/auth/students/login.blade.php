<html>

<title>تسجيل الدخول | الطلاب - لوحة التحكم</title>

@include('admins.layouts.head-links')

<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css-rtl/pages/login-register.css') }}">

<body class="vertical-layout vertical-menu 1-column  bg-cyan bg-lighten-2 menu-expanded fixed-navbar"
      data-open="click" data-menu="vertical-menu" data-col="1-column">
<!-- fixed-top-->

<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item">
                    <a class="navbar-brand" href="index.html">
                        <img class="brand-logo" alt="msbah admin logo" src="{{ asset('dashboard/app-assets/images/logo/logo.png') }}">
                        <h3 class="brand-text">مركز الفرقان لتعليم القرآن</h3>
                    </a>
                </li>
                <li class="nav-item d-md-none">
                    <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- ////////////////////////////////////////////////////////////////////////////-->

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="flexbox-container">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-md-4 col-10 box-shadow-2 p-0">
                        <div class="card border-grey border-lighten-3 m-0">
                            <div class="card-header border-0">
                                <div class="card-title text-center">
                                    <img src="{{ asset('dashboard/app-assets/images/logo/logo-dark.png') }}" alt="branding logo">
                                </div>
                                <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                    <span>مركز الفرقان لتعليم القرآن || دخول الطلاب</span>
                                </h6>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form-horizontal" method="POST" action="{{ route('students.login.post') }}" novalidate>
                                        @csrf

                                        <fieldset class="form-group position-relative has-icon-left">
                                            <input name="student_number" type="text"  class="form-control input-lg @error('student_number') is-invalid @enderror" id="user-name" placeholder="رقم الطالب"
                                                   tabindex="1" data-validation-required-message="يرجى إدخال رقم الطالب الصحيح." value="{{ old('student_number') }}" required autocomplete="student_number" autofocus>
                                            @error('student_number')
                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                            @enderror

                                            <div class="form-control-position">
                                                <i class="ft-user"></i>
                                            </div>
                                            <div class="help-block font-small-3"></div>
                                        </fieldset>

                                        <fieldset class="form-group position-relative has-icon-left">
                                            <input type="password" class="form-control input-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" id="password" placeholder="كلمة المرور"
                                                   tabindex="2" required data-validation-required-message="يرجى إدخال كلمة مرور صحيحة.">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                            @enderror


                                            <div class="form-control-position">
                                                <i class="la la-key"></i>
                                            </div>
                                            <div class="help-block font-small-3"></div>
                                        </fieldset>

                                        <div class="col-md-12 col-sm-12 d-flex justify-content-around">
                                            <fieldset>
                                                <input type="radio" name="section" value="male" id="input-radio-11" required="required">
                                                <label for="input-radio-11">بنين</label>
                                            </fieldset>
                                            <fieldset>
                                                <input type="radio" name="section" value="female" id="input-radio-12" required="required">
                                                <label for="input-radio-12">بنات</label>
                                            </fieldset>

                                            @error('section')
                                            <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                            @enderror

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6 col-12 text-center text-md-left">
                                                <fieldset>
                                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="remember-me" class="chk-remember">
                                                    <label for="remember-me"> تذكرني</label>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-block btn-lg">تسجيل الدخول <i class="ft-unlock"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->

<footer class="footer fixed-bottom footer-dark navbar-border navbar-shadow">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">

          <span class="float-md-left d-block d-md-inline-block"> جميع الحقوق محفوظة لدى
              <a class="text-bold-800 grey darken-2" href="{{ \Illuminate\Support\Facades\URL::current() }}" target="_blank">مركز الفرقان لتعليم القرآن </a>
              {{ date('Y') }} &copy;
          </span>

        <span class="float-md-right d-block d-md-inline-blockd-none d-lg-block">صنع بكل حب <i class="ft-heart pink"></i></span>
    </p>
</footer>

@include('admins.layouts.footer-links')
<script src="{{ asset('dashboard/app-assets/js/scripts/forms/form-login-register.js') }}" type="text/javascript"></script>

</body>

</html>
