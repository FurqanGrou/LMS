<!DOCTYPE html>
<html class="loading" lang="ar" data-textdirection="rtl">
<head>

    @include('admins.layouts.head-links')

</head>

<body class="vertical-layout vertical-menu 2-columns fixed-navbar pace-done menu-collapsed" data-open="click" data-menu="vertical-menu" data-col="2-columns">
   @include('admins.layouts.header-navbar')

   @include('admins.layouts.side-menu')

  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">

          @yield('content')

      </div>
    </div>
  </div>

  @include('admins.layouts.footer')

  @include('admins.layouts.footer-links')

</body>
</html>
