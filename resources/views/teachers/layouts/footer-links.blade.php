    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('dashboard/app-assets/vendors/js/vendors.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/extensions/sweetalert.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('dashboard/app-assets/vendors/js/forms/toggle/bootstrap-switch.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js')}}" type="text/javascript"></script>

    <script src="{{ asset('dashboard/app-assets/vendors/js/forms/toggle/switchery.min.js')}}" type="text/javascript"></script>

    <script src="{{ asset('dashboard/app-assets/js/scripts/forms/switch.js')}}" type="text/javascript"></script>

    <script src="{{ asset('dashboard/app-assets/vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/js/scripts/forms/select/form-select2.js')}}" type="text/javascript"></script>

    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/buttons.flash.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/jszip.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/pdfmake.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/vfs_fonts.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/buttons.html5.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/buttons.print.min.js') }}" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{ asset('dashboard/app-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/charts/echarts/echarts.js') }}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->

    <!-- BEGIN MODERN JS-->
    <script src="{{ asset('dashboard/app-assets/js/core/app-menu.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/js/core/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/js/scripts/customizer.js') }}" type="text/javascript"></script>

    <script src="{{ asset('dashboard/app-assets/js/scripts/tables/datatables/datatable-advanced.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('dashboard/app-assets/js/scripts/forms/checkbox-radio.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dashboard/app-assets/js/scripts/extensions/sweet-alerts.js') }}" type="text/javascript"></script>
    <!-- END MODERN JS-->

    <!-- BEGIN PAGE LEVEL JS-->
{{--    <script src="{{ asset('dashboard/app-assets/js/scripts/pages/dashboard-crypto.js') }}" type="text/javascript"></script>--}}
    <!-- END PAGE LEVEL JS-->


    <script src="{{ asset('dashboard/assets/js/my-functions.js') }}" type="text/javascript"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function (){
            $(document).on('change', '#request_form_type', function(){
                if ($(this).val()){
                    window.location.href = $(this).val();
                }
            });
        });
    </script>

    <script type="text/javascript" src="https://furqandevteam.atlassian.net/s/d41d8cd98f00b204e9800998ecf8427e-T/-dtzt95/b/6/c95134bc67d3a521bb3f4331beb9b804/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=da96fa13"></script>

    @stack('js')
