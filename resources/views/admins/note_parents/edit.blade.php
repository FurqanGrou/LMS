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
    <form class="form" method="POST" action="{{ route('admins.note-parents.update', $noteParent->id) }}">

        @csrf
        @method('PUT')

        <section id="basic-tabs-components">
            <div class="row match-height">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                ملاحظات أولياء الأمور - تعديل
                                <span class="badge badge-danger">{{ $noteParent->gender == 'female' ? 'قسم بنات' : 'قسم بنين'  }}</span>
                            </h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

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
                                        <textarea name="text" id="" cols="30" rows="5" required>{{ $noteParent->text }}</textarea>
                                    </div>
                                    <div class="tab-pane" id="tab2" aria-labelledby="base-tab2">
                                        <textarea name="text_en" id="" cols="30" rows="5" style="direction: ltr" required>{{ $noteParent->text_en }}</textarea>
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

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </section>


    </form>

@endsection
