@extends('admins.layouts.master')

<style>

    #main_image {
        width: 70px;
        margin: 0 auto;
        display: block;
    }

    #extra_images img{
        width: 70px;
    }

</style>

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')


    <form class="form" method="POST" action="{{ route('admins.request_services.update', $request_code) }}">

        @csrf
        @method('PUT')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-lock"></i>{{ $request_type }}</h4>
            <div class="row">

                <input type="hidden" name="form_title" value="طلب اختبار">
                <input type="hidden" name="request_code" value="{{ $request_code }}">

                @if($request_type == 'طلب اختبار')
                    @foreach($values as $key => $value)
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="{{ $key }}">{{ __('services.' . $key) }}</label>

                                @if(str_contains($key, "number_"))
                                    <input type="number" min="0" max="7" id="{{ $key }}" class="form-control" placeholder="{{ __('services.' . $key) }}" name="service[{{ $key }}]" value="{{ $value }}">
                                @else
                                    <input type="text" id="{{ $key }}" class="form-control" placeholder="{{ __('services.' . $key) }}" value="{{ $value }}" disabled>
                                @endif

                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> تحديث
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection
