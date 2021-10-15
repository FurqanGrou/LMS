@extends('students.layouts.master')

@section('content')

    @include('students.partials.errors')
    @include('students.partials.success')

    <div class="content-body">

        <a href="{{ route('students.student.download', $student_id) }}" class="btn btn-primary has-blur text-blur">تحميل</a>

        <!-- edit marks and new assignment of yesterday -->
        <section class="horizontal-grid" id="horizontal-grid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">

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
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>اليوم</th>
                                            <th>الدرس الجديد</th>
                                            <th>من</th>
                                            <th>إلى</th>
                                            <th>أخر 5 صفحات</th>
                                            <th>المراجعة اليومية</th>
                                            <th>من</th>
                                            <th>إلى</th>

                                            <th>خطأ</th>
                                            <th>تنبيه</th>
                                            <th>عدد الصفحات</th>
                                            <th>درجات الدرس</th>
                                            <th>درجات أخر 5 صفحات</th>
                                            <th>درجات المراجعة اليومية</th>
                                            <th>درجات السلوك</th>
                                            <th>المجموع</th>
                                            <th>ملاحظات الى ولي الأمر</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($monthReports as $dayReport)
                                            <tr class="bg-blue bg-lighten-5">
                                                <td>{{ substr($dayReport->date, -10) }}</td>
                                                <td>{{ substr($dayReport->date, 0, -10) }}</td>
                                                <td>{{ $dayReport->new_lesson }}</td>
                                                <td>{{ $dayReport->new_lesson_from }}</td>
                                                <td>{{ $dayReport->new_lesson_to }}</td>
                                                <td>{{ $dayReport->last_5_pages }}</td>
                                                <td>{{ $dayReport->daily_revision }}</td>
                                                <td>{{ $dayReport->daily_revision_from }}</td>
                                                <td>{{ $dayReport->daily_revision_to }}</td>
                                                <td>{{ $dayReport->mistake }}</td>
                                                <td>{{ $dayReport->alert }}</td>
                                                <td>{{ $dayReport->number_pages }}</td>
                                                <td>{{ $dayReport->lesson_grade }}</td>
                                                <td>{{ $dayReport->last_5_pages_grade }}</td>
                                                <td>{{ $dayReport->daily_revision_grade }}</td>
                                                <td>{{ $dayReport->behavior_grade }}</td>
                                                <td>{{ $dayReport->total }}</td>
                                                <td>{{ $dayReport->notes_to_parent }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

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
