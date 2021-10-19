@extends('admins.layouts.master')

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.attendance.store') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-user"></i> بصمة الحضور والانصراف</h4>
            <div class="row d-flex justify-content-center" style="margin-top: 100px">
                <div class="col-md-3 d-flex justify-content-center">
                    <button type="submit" class="btn btn-success" name="login_btn" value="login">
                        <i class="ft-log-in"></i> دخول
                    </button>
                </div>
                <div class="col-md-3 d-flex justify-content-center">
                    <button type="submit" class="btn btn-danger" name="logout_btn" value="logout">
                        <i class="ft-log-out"></i> خروج
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('js')
{{--    <script>--}}
{{--        $(function() {--}}
{{--            $('.toggle-status').change(function() {--}}
{{--                var status = $(this).prop('checked') == true ? 0 : 1;--}}
{{--                $.ajax({--}}
{{--                    type: "GET",--}}
{{--                    dataType: "json",--}}
{{--                    url: '{{ route('admins.disable.admins.login') }}',--}}
{{--                    data: {'status': status},--}}
{{--                    success: function(data){--}}
{{--                        // console.log(data.status);--}}
{{--                    }--}}
{{--                });--}}
{{--            })--}}
{{--        })--}}
{{--    </script>--}}
@endpush
