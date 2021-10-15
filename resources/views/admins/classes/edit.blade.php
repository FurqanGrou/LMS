@extends('dashboard.layouts.master')

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

    @include('dashboard.partials.errors')
    @include('dashboard.partials.success')


    <form class="form" method="POST" action="{{ dRoute('ads.update', $ad->id) }}" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="form-body">
            <h4 class="form-section"><i class="la la-flag-o"></i>تعديل إعلان: {{ $ad->title }}</h4>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">عنوان الإعلان</label>
                        <input type="text" id="title" class="form-control" placeholder="عنوان الإعلان" name="title" value="{{ old('title', $ad->title) }}" required>
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-end justify-content-center">
                    <div class="form-group" style="margin: 10px;">
                        <input type="checkbox" name="is_active" id="switcheryColor4" class="switchery" data-color="success" {{ $ad->is_active == 1 ? 'checked' : '' }}/>
                        <label for="switcheryColor4" class="card-title ml-1">الحالة</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="projectinput2">القسم</label>
                        <select name="subcategories" class="select2 form-control">
                            <optgroup label="من فضلك أختر قسم ">
                                @if($sub_categories && $sub_categories->count() > 0)
                                    @foreach($sub_categories as $sub_category)
                                        <option value="{{ $sub_category->id }}" {{ $ad->categories()->pluck('category_id')->contains($sub_category->id) ? 'selected' : '' }}>{{ getParentCategoryName($sub_category->id) . $sub_category->name }}</option>
                                    @endforeach
                                @endif
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-end justify-content-center">
                    <div class="form-group" style="margin: 10px;">
                        <input type="checkbox" name="is_featured" id="is_featured" class="switchery" data-color="success" {{ $ad->is_featured == 1 ? 'checked' : '' }}/>
                        <label for="is_featured" class="card-title ml-1">إعلان مميز</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <fieldset class="form-group mb-2">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image_path_special" name="image_path_special">
                            <label class="custom-file-label" for="image_path_special">إختر صورة رئيسية جديدة للإعلان</label>
                        </div>
                    </fieldset>

                    @if(!is_null($main_image))
                        <img src="{{ asset($main_image->real_image_path) }}" alt="Main image" id="main_image">
                    @endif

                </div>

                <div class="col-md-6">

                    <fieldset class="form-group mb-2">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="extra_images" name="extra_images[]" multiple>
                            <label class="custom-file-label" for="extra_images">إختر 3 صور أخرى جديدة للإعلان</label>
                        </div>
                    </fieldset>

                    @if(count( ($extra_images) ) > 0)
                        <div id="extra_images" class="d-flex justify-content-center">
                            @foreach($extra_images as $image)
                                <img src="{{ asset($image->real_image_path) }}" alt="Extra image" class="mr-3">
                            @endforeach
                        </div>
                    @endif

                </div>

                <div class="col-12 mb-2 mt-2">
                    <hr>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <input type="text" id="phone" class="form-control" placeholder="رقم الهاتف" name="phone" value="{{ old('phone', $ad->phone) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="projectinput2">عملة السعر</label>
                        <select name="currency_id" class="select2 form-control">
                            <optgroup label="من فضلك أختر عملة السعر">
                                @if($currencies && $currencies->count() > 0)
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}"  {{ ($currency->id == $ad->currency_id) ? 'selected' : '' }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="price">السعر</label>
                        <input type="text" id="price" class="form-control" placeholder="السعر" name="price" value="{{ old('price', $ad->price) }}" required>
                    </div>
                </div>

                <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="description">وصف الإعلان</label>
                        <textarea name="description" id="description" style="width: 100%" rows="7" required>{{ old('about_us', $ad->description) }}</textarea>
                    </fieldset>
                </div>

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
