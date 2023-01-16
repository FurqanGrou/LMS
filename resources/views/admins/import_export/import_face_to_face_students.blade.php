@extends('admins.layouts.master')

<title>ادخال وتحديث بيانات طلاب - الحضوري</title>

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.import.face_students.store') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-users"></i> ادخال وتحديث بيانات طلاب - <span class="badge-danger badge">الحضوري</span></h4>
            <div class="row">

                {{--Disable Teachers Login--}}
                <div class="col-6 col-md-4">
                    <div class="card crypto-card-3 pull-up">
                        <div class="card-content">

                            <div class="card-body d-flex flex-column justify-content-center">
                                <div class="row">
                                    <div class="col-2 d-flex justify-content-center align-items-center">
                                        <h1 class="mb-0"><i class="ft-log-out font-large-1" title="إخراج المعلمين"></i></h1>
                                    </div>
                                    <div class="col-6 d-flex justify-content-center align-items-center">
                                        <h4 class="mb-0">إخراج المعلمين</h4>
                                    </div>
                                    <div class="col-4 text-center d-flex justify-content-center align-items-center">
                                        <h5 class="danger mb-0">
                                            <input type="checkbox" name="switchery" id="switchery01" data-color="danger" class="toggle-status switchery" {{ $teachers_status == 0 ? 'checked' : '' }} />
                                        </h5>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{--Upload File--}}
                <div class="col-6 col-md-4">
                    <div class="card crypto-card-3 pull-up">
                        <div class="card-content">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <input type="file" name="file" class="form-control">
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
