@extends('admins.layouts.master')

<title>ادخال وتحديث بيانات طلاب - الاونلاين</title>

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.import-top-tracker-employees') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-globe"></i> ادخال وتحديث بيانات الموظفين - <span class="badge-success badge font-weight-bold">Top Tracker</span></h4>
                <div class="row">

                    {{--Upload File--}}
                    <div class="col-6 col-md-4">
                        <div class="card crypto-card-3 pull-up">
                            <div class="card-content">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> استيراد
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection

@push('js')
    <script>
        $(function() {
            $('.toggle-status').change(function() {
                var status = $(this).prop('checked') == true ? 0 : 1;
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('admins.disable.teachers.login') }}',
                    data: {'status': status},
                    success: function(data){
                        // console.log(data.status);
                    }
                });
            })
        })
    </script>
@endpush
