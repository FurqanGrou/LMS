@extends('teachers.layouts.master')

@section('content')

    @include('teachers.partials.errors')
    @include('teachers.partials.success')

    <div class="content-body">

        <!-- edit marks and new assignment of yesterday -->
            <section class="horizontal-grid" id="horizontal-grid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <h4 class="success text-center">
                                    <a href="{{ route('teachers.report.create', $report->student_id) }}" class="btn btn-warning">العودة إلى تقارير الطالب</a>

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

                                    <form class="form" method="POST" action="{{ route('teachers.report.update', $report->id) . '?updateType=normal' }}">

                                        @csrf
                                        @method('PUT')

                                        <div class="form-body">

                                            <h2>{{ \App\User::where('id', '=', $report->student_id)->first()->name }}</h2>
                                            <h2>{{ substr($report->date, 0, -10) . ' - ' . substr($report->date, -10)}}</h2>

                                            <hr>

                                            <input type="hidden" name="student_id" value="{{ $report->student_id }}" class="form-control">

                                            @if($is_new_student)
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="">خطأ:</label>
                                                            <input type="text" name="mistake" class="form-control" value="{{ old('mistake', $report->mistake) }}" placeholder="خطأ">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="">تنبيه:</label>
                                                            <input type="text" name="alert" class="form-control" value="{{ old('alert', $report->alert) }}" placeholder="تنبيه">
                                                        </div>
                                                    </div>
                                                    {{--                                                <div class="col-md-2">--}}
                                                    {{--                                                    <div class="form-group">--}}
                                                    {{--                                                        <label for="">عدد الصفحات:</label>--}}
                                                    {{--                                                        <input type="text" name="number_pages" class="form-control" value="{{ old('number_pages', $report->number_pages) }}" placeholder="عدد الصفحات">--}}
                                                    {{--                                                    </div>--}}
                                                    {{--                                                </div>--}}
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="">اسم المستمع:</label>
                                                            <input type="text" name="listener_name" class="form-control" value="{{ old('listener_name', $report->listener_name) }}" placeholder="اسم المستمع">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="">درجة الدرس الجديد:</label>
                                                            <input type="text" name="lesson_grade" class="form-control" value="{{ old('lesson_grade', $report->lesson_grade) }}" placeholder="درجة الدرس الجديد">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="">درجة أخر 5 صفحات:</label>
                                                            <input type="text" name="last_5_pages_grade" class="form-control" value="{{ old('last_5_pages_grade', $report->last_5_pages_grade) }}" placeholder="درجة أخر 5 صفحات">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="">درجة المراجعة اليومية:</label>
                                                            <input type="text" name="daily_revision_grade" class="form-control" value="{{ old('daily_revision_grade', $report->daily_revision_grade) }}" placeholder="درجة المراجعة اليومية">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="">درجة السلوك وغيره:</label>
                                                            <input type="text" name="behavior_grade" class="form-control" value="{{ old('behavior_grade', $report->behavior_grade) }}" placeholder="درجة السلوك وغيره">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="">ملاحظات الى ولي الأمر:</label>
                                                            <select name="notes_to_parent" id="" class="select2">
                                                                @foreach($notes as $note)
                                                                    <option value="{{ $note->text }}" {{ $note->text == $report->notes_to_parent ? 'selected': '' }}>{{ $note->text }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                            @endif

                                            <div class="row">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">الدرس الجديد:</label>
                                                        <input type="text" name="new_lesson" value="{{ old('new_lesson', $report->new_lesson) }}" class="form-control" placeholder="الدرس الجديد">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">من:</label>
                                                        <input type="text" name="new_lesson_from" value="{{ old('new_lesson_from', $report->new_lesson_from) }}" class="form-control" placeholder="من">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">إلى:</label>
                                                        <input type="text" name="new_lesson_to" class="form-control" value="{{ old('new_lesson_to', $report->new_lesson_to) }}" placeholder="إلى">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">أخر 5 صفحات:</label>
                                                        <input type="text" name="last_5_pages" value="{{ old('last_5_pages', $report->last_5_pages) }}" class="form-control" placeholder="أخر 5 صفحات">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">المراجعة اليومية:</label>
                                                        <input type="text" name="daily_revision" value="{{ old('daily_revision', $report->daily_revision) }}" class="form-control" placeholder="المراجعة اليومية">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">من:</label>
                                                        <input type="text" name="daily_revision_from" value="{{ old('daily_revision_from', $report->daily_revision_from) }}" class="form-control" placeholder="من">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">إلى:</label>
                                                        <input type="text" name="daily_revision_to" value="{{ old('daily_revision_to', $report->daily_revision_to) }}" class="form-control" placeholder="إلى">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">عدد الصفحات:</label>
                                                        <input type="text" name="number_pages" value="{{ old('number_pages', $report->number_pages) }}" class="form-control" placeholder="عدد الصفحات">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary">تحديث <i class="ft-thumbs-up position-right"></i></button>
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
        <!-- edit marks and new assignment of yesterday -->

    </div>

{{--    <form class="form" method="POST" action="{{ route('teachers.report.store') }}">--}}

{{--        @csrf--}}
{{--        @method('POST')--}}

{{--        <div class="content-body" bis_skin_checked="1">--}}

{{--            <div class="row" bis_skin_checked="1">--}}
{{--                <div class="col-12" bis_skin_checked="1">--}}
{{--                    <div class="card" bis_skin_checked="1">--}}
{{--                        <div class="card-header" bis_skin_checked="1">--}}
{{--                            <h4 class="card-title">08-2021</h4>--}}
{{--                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>--}}
{{--                            <div class="heading-elements" bis_skin_checked="1">--}}
{{--                                <ul class="list-inline mb-0">--}}
{{--                                    <li><a data-action="collapse"><i class="ft-plus"></i></a></li>--}}
{{--                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>--}}
{{--                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}


{{--                        <div class="card-content collapse" bis_skin_checked="1" style="overflow-x: scroll;">--}}
{{--                            <div class="card-body card-admins" bis_skin_checked="1" style="overflow-x: scroll;">--}}

{{--                                <div class="form-body" style="overflow-x: scroll;">--}}
{{--                                    <h4 class="form-section"><i class="ft-user"></i> {{ $user->name }}</h4>--}}

{{--                                    <div class="table-responsive">--}}
{{--                                        <table class="table" style="overflow-x: scroll;">--}}
{{--                                            <thead>--}}
{{--                                            <tr>--}}
{{--                                                <th>#</th>--}}
{{--                                                <th>التاريخ</th>--}}
{{--                                                <th>اليوم</th>--}}
{{--                                                <th>الدرس الجديد</th>--}}
{{--                                                <th>من</th>--}}
{{--                                                <th>إلى</th>--}}
{{--                                                <th>أخر 5 صفحات</th>--}}
{{--                                                <th>المراجعة اليومية</th>--}}
{{--                                                <th>من</th>--}}
{{--                                                <th>إلى</th>--}}
{{--                                                <th>خطأ</th>--}}
{{--                                                <th>تنبيه</th>--}}
{{--                                                <th>عدد الصفحات</th>--}}
{{--                                                <th>اسم المستمع</th>--}}
{{--                                                <th>الدرس</th>--}}
{{--                                                <th>أخر 5 صفحات</th>--}}
{{--                                                <th>المراجعة اليومية</th>--}}
{{--                                                <th>السلوك والأخرى</th>--}}
{{--                                                <th>المجموع</th>--}}
{{--                                                <th>ملاحظات المعلم لولي الأمر</th>--}}
{{--                                            </tr>--}}
{{--                                            </thead>--}}
{{--                                            <tbody>--}}

{{--                                            @foreach($month as $key => $dayes)--}}
{{--                                                <tr class="bg-blue bg-lighten-5">--}}

{{--                                                    <input type="hidden" name="date" value="{{ substr($dayes->day, -2)  . '-' . $dayes->month . '-' . $dayes->year }}">--}}

{{--                                                    <td>--}}
{{--                                                        {{ $key+1 }}</td>--}}
{{--                                                    <td style="@if( str_contains($dayes->day, 'Saturday')) background: black; color: #FFF @endif">--}}
{{--                                                        {{ substr($dayes->day, -2)  . '-' . $dayes->month . '-' . $dayes->year}}--}}
{{--                                                    </td>--}}
{{--                                                    <td style="@if( str_contains($dayes->day, 'Saturday')) background: black; color: #FFF @endif">--}}
{{--                                                        {{ substr($dayes->day, 0, -2)}}--}}
{{--                                                    </td>--}}

{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="new_lesson_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="الدرس الجديد">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="new_lesson_from_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="new_lesson_to_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="last_5_pages_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="daily_revision_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="daily_revision_from_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="daily_revision_to_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="mistake_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="alert_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="number_pages_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="listener_name_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="behavior_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="total_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="notes_to_parent_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" name="student_id_{{ substr($dayes->day, -2) }}" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <fieldset class="form-group">--}}
{{--                                                            <input type="text" class="form-control" id="helpInput" placeholder="With Help Text">--}}
{{--                                                        </fieldset>--}}
{{--                                                    </td>--}}

{{--                                                </tr>--}}
{{--                                            @endforeach--}}
{{--                                            </tbody>--}}
{{--                                        </table>--}}
{{--                                    </div>--}}

{{--                                </div>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}

{{--        <div class="form-actions">--}}
{{--            <button type="submit" class="btn btn-primary">--}}
{{--                <i class="la la-check-square-o"></i> اضافة وارسال--}}
{{--            </button>--}}
{{--            <button type="reset" class="btn btn-warning mr-1">--}}
{{--                <i class="ft-x"></i> إلغاء--}}
{{--            </button>--}}
{{--        </div>--}}

{{--    </form>--}}

@endsection
