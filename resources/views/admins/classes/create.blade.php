@extends('dashboard.layouts.master')

@section('content')

    @include('dashboard.partials.errors')
    @include('dashboard.partials.success')

    <form class="form" method="POST" action="{{ dRoute('subcategories.store') }}">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-user"></i> بيانات القسم الفرعي</h4>
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="projectinput2"> أختر قسم رئيسي </label>
                        <select name="parent_id" class="select2 form-control">
                                @if($categories && $categories->count() > 0)
                                    <optgroup label="من فضلك أختر قسم رئيسي ">
                                    @foreach($categories as $category)
                                        <option
                                            value="{{$category->id }}">{{$category->name}}
                                        </option>
                                    @endforeach
                                @else
                                    <optgroup label="!لا يوجد اقسام رئيسية مدخلة">
                                @endif
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">اسم القسم الفرعي</label>
                        <input type="text" id="name" class="form-control" placeholder="اسم القسم الفرعي" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-end justify-content-center">
                    <div class="form-group" style="margin: 10px;">
                        <input type="checkbox" name="is_active" id="switcheryColor4" class="switchery" data-color="success" checked/>
                        <label for="switcheryColor4" class="card-title ml-1">الحالة </label>
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
