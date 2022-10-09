@extends('admins.layouts.master')

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <style>
        textarea{
            width: 100%;
            resize: none;
            padding: 15px;
            border-radius: 5px
        }
    </style>
    <form class="form" method="POST" action="{{ route('admins.note-parents.store') }}">

        @csrf

        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">ملاحظات أولياء الأمور</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admins.home') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.note-parents.index') }}">ملاحظات أولياء الأمور</a>
                        </li>
                        <li class="breadcrumb-item active"><a href="{{ route('admins.note-parents.create') }}">إضافة ملاحظة جديدة</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <section id="basic-tabs-components">
            <div class="row match-height">
                <div class="col-6">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">

                                <label for="section">المحتوى:</label>

                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" style="width: 150px;" id="base-tab1" data-toggle="tab" aria-controls="tab1"
                                           href="#tab1" aria-expanded="true">عربي</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" style="width: 150px;" id="base-tab2" data-toggle="tab" aria-controls="tab2" href="#tab2"
                                           aria-expanded="false">انجليزي</a>
                                    </li>
                                </ul>
                                <div class="tab-content px-1 pt-1">
                                    <div role="tabpanel" class="tab-pane active" id="tab1" aria-expanded="true" aria-labelledby="base-tab1">
                                        <textarea name="text" id="" cols="30" rows="5" required>{!! old('text') !!}</textarea>
                                    </div>
                                    <div class="tab-pane" id="tab2" aria-labelledby="base-tab2">
                                        <textarea name="text_en" id="" cols="30" rows="5" style="direction: ltr" required>{!! old('text_en') !!}</textarea>
                                    </div>
                                </div>

                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="section">القسم:</label>
                                            <select class="selectBox w-100" name="section" id="section" required>
                                                <option value="0">-</option>
                                                <option value="1">بنين</option>
                                                <option value="2">بنات</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="la la-check-square-o"></i> حفظ
                                    </button>
                                    <button type="reset" class="btn btn-warning mr-1">
                                        <i class="ft-x"></i> إلغاء
                                    </button>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>


    </form>

@endsection
