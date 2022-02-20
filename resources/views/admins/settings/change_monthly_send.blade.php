@extends('admins.layouts.master')

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.change_send_monthly_report_status.update') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-mail"></i> التحكم في الارسال الشهري يدوياً</h4>
            <div class="row">

                {{--PREVIOUS MONTH STATUS--}}
                <div class="col-6 col-md-4">
                    <div class="card crypto-card-3 pull-up">
                        <div class="card-content">

                            <div class="card-body d-flex flex-column justify-content-center">
                                <div class="row">
                                    <div class="col-2 d-flex justify-content-center align-items-center">
                                        <h1 class="mb-0"><i class="ft-log-out font-large-1" title="امكانية الارسال للشهر السابق"></i></h1>
                                    </div>
                                    <div class="col-6 d-flex justify-content-center align-items-center">
                                        <h4 class="mb-0">امكانية الارسال للشهر السابق</h4>
                                    </div>
                                    <div class="col-4 text-center d-flex justify-content-center align-items-center">
                                        <h5 class="danger mb-0">
                                            <input type="checkbox" name="switchery" id="previous-month-status" data-color="danger" class="toggle-status switchery" {{ $status_previous == 'true' ? 'checked' : '' }} />
                                        </h5>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{--CURRENT MONTH STATUS--}}
{{--                <div class="col-6 col-md-4">--}}
{{--                    <div class="card crypto-card-3 pull-up">--}}
{{--                        <div class="card-content">--}}

{{--                            <div class="card-body d-flex flex-column justify-content-center">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-2 d-flex justify-content-center align-items-center">--}}
{{--                                        <h1 class="mb-0"><i class="ft-log-out font-large-1" title="امكانية الارسال للشهر الحالي"></i></h1>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-6 d-flex justify-content-center align-items-center">--}}
{{--                                        <h4 class="mb-0">امكانية الارسال للشهر الحالي</h4>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-4 text-center d-flex justify-content-center align-items-center">--}}
{{--                                        <h5 class="danger mb-0">--}}
{{--                                            <input type="checkbox" name="switchery" id="current-month-status" data-color="danger" class="toggle-status switchery" {{ $status_current == 'true' ? 'checked' : '' }} />--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>
        </div>
    </form>

@endsection

@push('js')
    <script>
        $(function() {
            $('.toggle-status').change(function() {
                // var current_status  = $('#current-month-status').prop('checked') == true ? 'true' : 'false';
                var previous_status = $('#previous-month-status').prop('checked') == true ? 'true' : 'false';
                $.ajax({
                    type: "PUT",
                    dataType: "json",
                    url: '{{ route('admins.change_send_monthly_report_status.update') }}',
                    data: {
                        // 'current_status': current_status,
                        'previous_status': previous_status,
                    },
                    success: function(data){
                        // console.log(data.status);
                    }
                });
            })
        })
    </script>
@endpush
