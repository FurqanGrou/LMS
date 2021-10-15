
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Hatim Hussein Description">
    <meta name="keywords" content="Hatim Hussein keywords">
    <meta name="author" content="Dev. Hatim Hussein">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title> @yield('title') </title>

    <link rel="apple-touch-icon" href="{{ asset('dashboard/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('dashboard/app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
          rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/select.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/extensions/keyTable.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/forms/selects/select2.min.css')}}">

    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css-rtl/vendors.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/forms/toggle/bootstrap-switch.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/forms/toggle/switchery.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/forms/icheck/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css/plugins/forms/checkboxes-radios.css')}}">

    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/extensions/sweetalert.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css-rtl/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css-rtl/custom-rtl.css') }}">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css-rtl/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/cryptocoins/cryptocoins.css') }}">
    <!-- END Page Level CSS-->


    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/style-rtl.css') }}">
    <style>
        #DataTables_Table_4_wrapper .dt-buttons {
            top: 48px;
            right: auto;
            left: 0;
            position: relative;
            display: flex;
            justify-content: flex-end;
        }

        .dt-button-collection.dropdown-menu {
            left: 77px;
            right: auto;
        }

        #DataTables_Table_4_filter {
            display: flex;
        }

        #DataTables_Table_4_filter label {
            z-index: 999;
        }

    </style>

    @stack('css')

    <!-- END Custom CSS-->
