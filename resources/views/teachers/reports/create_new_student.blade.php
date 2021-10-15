@extends('teachers.layouts.master')

@section('content')

    @include('teachers.partials.errors')
    @include('teachers.partials.success')

    <div class="content-body">

        <!-- insert first assignment of yesterday -->
        <section class="horizontal-grid" id="horizontal-grid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">

                            <h4 class="success text-center">
                                <a href="{{ route('teachers.classStudents.index', $user->class_number) }}" class="btn btn-secondary">عرض قائمة الطلاب</a>
                            </h4>

                            <a class="heading-elements-toggle"><i class="ft-align-justify font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <p>الطالب{{ getTitleName($user->section) . ': ' . $user->name . ' - ' . $user->student_number }}</p>

                                <br>

                                <form class="form prevent-multiple-submit-form" method="POST" action="{{ route('teachers.report.store') }}">

                                    @csrf
                                    @method('POST')

                                    <div class="form-body">

                                        <input type="hidden" name="date" value="{{ $tomorrow_date }}" class="form-control">
                                        <input type="hidden" name="student_id" value="{{ $user->id }}" class="form-control">
                                        <input type="hidden" name="class_number" value="{{ $user->class_number }}" class="form-control">

                                        <fieldset>
                                            <h2>
                                                <input type="checkbox" id="absence-check">
                                                <label for="absence-check">الطالب{{ getTitleName($user->section) }} غائب{{ getTitleName($user->section) }}</label>
                                            </h2>
                                        </fieldset>

                                        <div class="row absence-inputs d-none">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="radio" name="absence" value="-2" id="ab1">
                                                    <label for="ab1">غياب بعذر</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <input type="radio" name="absence" value="-5" id="ab2">
                                                    <label for="ab2">غياب بدون عذر</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row absence">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">خطأ:</label>
                                                    <input type="text" name="mistake" class="form-control" value="{{ old('mistake') }}" placeholder="خطأ">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">تنبيه:</label>
                                                    <input type="text" name="alert" class="form-control" value="{{ old('alert') }}" placeholder="تنبيه">
                                                </div>
                                            </div>
                                            {{--                                                    <div class="col-md-2">--}}
                                            {{--                                                        <div class="form-group">--}}
                                            {{--                                                            <label for="">عدد الصفحات:</label>--}}
                                            {{--                                                            <input type="text" name="number_pages" class="form-control" value="{{ old('number_pages') }}" placeholder="عدد الصفحات">--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </div>--}}
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">اسم المستمع:</label>
                                                    <input type="text" name="listener_name" class="form-control" value="{{ old('listener_name') }}" placeholder="اسم المستمع">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row absence">

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">درجة الدرس الجديد:</label>
                                                    <input type="number" min="0" name="lesson_grade" class="form-control" value="{{ old('lesson_grade') }}" placeholder="درجة الدرس الجديد">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">درجة أخر 5 صفحات:</label>
                                                    <input type="text" name="last_5_pages_grade" class="form-control" value="{{ old('last_5_pages_grade') }}" placeholder="درجة أخر 5 صفحات">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">درجة المراجعة اليومية:</label>
                                                    <input type="text" name="daily_revision_grade" class="form-control" value="{{ old('daily_revision_grade') }}" placeholder="درجة المراجعة اليومية">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">درجة السلوك وغيره:</label>
                                                    <input type="text" name="behavior_grade" class="form-control" value="{{ old('behavior_grade') }}" placeholder="درجة السلوك وغيره">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">ملاحظات الى ولي الأمر:</label>
                                                    <select name="notes_to_parent" class="select2">
                                                        @foreach($notes as $note)
                                                            <option value="{{ $note->text }}">{{ $note->text }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">الدرس الجديد:</label>
                                                    <input type="text" name="new_lesson" class="form-control" placeholder="الدرس الجديد">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">من:</label>
                                                    <input type="text" name="new_lesson_from" class="form-control" placeholder="من">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">إلى:</label>
                                                    <input type="text" name="new_lesson_to" class="form-control" placeholder="إلى">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">أخر 5 صفحات:</label>
                                                    <input type="text" name="last_5_pages" class="form-control" placeholder="أخر 5 صفحات">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">المراجعة اليومية:</label>
                                                    <input type="text" name="daily_revision" class="form-control" placeholder="المراجعة اليومية">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">من:</label>
                                                    <input type="text" name="daily_revision_from" class="form-control" placeholder="من">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">إلى:</label>
                                                    <input type="text" name="daily_revision_to" class="form-control" placeholder="إلى">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">عدد الصفحات:</label>
                                                    <input type="text" name="number_pages" class="form-control" placeholder="عدد الصفحات">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary prevent-multiple-submit-button">
                                                    حفظ
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                    <i class="ft-thumbs-up position-right"></i></button>
                                                <button type="reset" class="btn btn-warning">الغاء <i class="ft-refresh-cw position-right"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- insert marks and new assignment of yesterday -->

    </div>

@endsection
