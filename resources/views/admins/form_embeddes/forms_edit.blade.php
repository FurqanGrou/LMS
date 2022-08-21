@extends('admins.layouts.master')

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <form class="form" method="POST" action="{{ route('admins.forms-service.update', $form->id) }}">

        @csrf
        @method('PUT')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-user"></i> بيانات الطلب</h4>
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="full-name">عنوان الطلب</label>
                        <input type="text" id="full-name" class="form-control" placeholder="عنوان الطلب" name="title" value="{{ old('title', $form->title) }}" required>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">الرابط - URL</label>
                        <input type="url" id="email" class="form-control" placeholder="الرابط - URL" name="url" value="{{ old('url', $form->url) }}" required>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="switcherySize3">حالة الطلب</label>
                        <input type="checkbox" id="switcherySize3" class="switchery" data-size="xs" name="status" {{ $form->status ? 'checked' : '' }} />
                    </div>
                </div>

            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> اضافة
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection
