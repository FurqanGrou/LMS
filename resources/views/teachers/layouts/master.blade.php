<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr" dir="ltr">
<head>

    @include('teachers.layouts.head-links')

</head>

<body class="vertical-layout vertical-menu 2-columns fixed-navbar pace-done menu-collapsed" data-open="click" data-menu="vertical-menu" data-col="2-columns">
   @include('teachers.layouts.header-navbar')

   @include('teachers.layouts.side-menu')

  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body">

          @yield('content')

      </div>
    </div>
  </div>

  @include('teachers.layouts.footer')

  @include('teachers.layouts.footer-links')

</body>
</html>
