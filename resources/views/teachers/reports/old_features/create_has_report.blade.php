@extends('teachers.layouts.master')

@section('content')

    @include('teachers.partials.errors')
    @include('teachers.partials.success')

    <div class="content-body">
        <!-- view assignment of yesterday -->
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

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            {{--                                                    <th>التاريخ</th> فوق--}}
                                            {{--                                                    <th>اليوم</th> فوق--}}

                                            <th>اليوم</th>
                                            <th>التاريخ</th>
                                            <th>الدرس الجديد</th>
                                            <th>من</th>
                                            <th>إلى</th>
                                            <th>أخر 5 صفحات</th>
                                            <th>المراجعة اليومية</th>
                                            <th>من</th>
                                            <th>إلى</th>

                                            {{--                                                    <th>خطأ</th>--}}
                                            {{--                                                    <th>تنبيه</th>--}}
                                            {{--                                                    <th>عدد الصفحات</th>--}}
                                            {{--                                                    <th>درجات الدرس</th>--}}
                                            {{--                                                    <th>درجات أخر 5 صفحات</th>--}}
                                            {{--                                                    <th>درجات المراجعة اليومية</th>--}}
                                            {{--                                                    <th>درجات السلوك</th>--}}
                                            {{--                                                    <th>المجموع</th>--}}
                                            {{--                                                    <th>ملاحظات الى ولي الأمر</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="bg-blue bg-lighten-5">
                                            {{--                                                    <td>{{ substr($user_report->date, -10) }}</td>--}}
                                            {{--                                                    <td>{{ substr($user_report->date, 0, -10) }}</td>--}}
                                            <td>{{ substr($hasReportTomorrow->date, 0, -10) }}</td>
                                            <td>{{ substr($hasReportTomorrow->date, -10) }}</td>
                                            <td>{{ $hasReportTomorrow->new_lesson }}</td>
                                            <td>{{ $hasReportTomorrow->new_lesson_from }}</td>
                                            <td>{{ $hasReportTomorrow->new_lesson_to }}</td>
                                            <td>{{ $hasReportTomorrow->last_5_pages }}</td>
                                            <td>{{ $hasReportTomorrow->daily_revision }}</td>
                                            <td>{{ $hasReportTomorrow->daily_revision_from }}</td>
                                            <td>{{ $hasReportTomorrow->daily_revision_to }}</td>
                                            {{--                                                    <td>{{ $user_report->mistake }}</td>--}}
                                            {{--                                                    <td>{{ $user_report->alert }}</td>--}}
                                            {{--                                                    <td>{{ $user_report->number_pages }}</td>--}}
                                            {{--                                                    <td>{{ $user_report->lesson_grade }}</td>--}}
                                            {{--                                                    <td>{{ $user_report->last_5_pages_grade }}</td>--}}
                                            {{--                                                    <td>{{ $user_report->daily_revision_grade }}</td>--}}
                                            {{--                                                    <td>{{ $user_report->behavior_grade }}</td>--}}
                                            {{--                                                    <td>{{ $user_report->total }}</td>--}}
                                            {{--                                                    <td>{{ $user_report->notes_to_parent }}</td>--}}
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- view assignment of yesterday end -->

        <!-- insert marks and new assignment of yesterday -->
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

                                @if($lastGrades->mail_status == 1)
                                    <h4 class="success mb-2">
                                        <i class="ft-mail"></i>
                                        تم ارسال التقرير اليومي بنجاح
                                    </h4>
                                @else
                                    <h4 class="danger mb-2">
                                        <i class="ft-x"></i>
                                        لم يتم ارسال التقرير اليومي بعد!!
                                    </h4>
                                @endif

                                @if(!is_null($lastGrades))
                                    <fieldset>
                                        <h2>
                                            <input type="checkbox" {{ $lastGrades->absence != 0 ? 'checked' : '' }} class="{{ $lastGrades->absence != 0 ? 'js-clicked' : '' }}" id="absence-check">
                                            <label for="absence-check">الطالب{{ getTitleName($user->section) }} غائب{{ getTitleName($user->section) }}</label>
                                        </h2>
                                    </fieldset>
                                @endif

                                <form class="form prevent-multiple-submit-form" method="POST" action="{{ route('teachers.report.updateTomorrow', $hasReportTomorrow->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-body">

                                        <input type="hidden" name="student_id" id="student_id" value="{{ $user->id }}" class="form-control">
                                        <input type="hidden" name="class_number" value="{{ $user->class_number }}" class="form-control">


                                        @if(!is_null($lastGrades))
                                            <div class="row absence-inputs {{ $lastGrades->absence == 0 ? 'd-none' : '' }}">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="absence" {{ $lastGrades->absence == -2 ? 'checked' : '' }} value="-2" id="ab1">
                                                        <label for="ab1">غياب بعذر</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="radio" name="absence"  {{ $lastGrades->absence == -5 ? 'checked' : '' }} value="-5" id="ab2">
                                                        <label for="ab2">غياب بدون عذر</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="report_id" value="{{ $lastGrades->id }}" class="form-control">
                                            <div class="row absence {{ $lastGrades->absence != 0 ? 'd-none' : '' }} ">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">خطأ:</label>
                                                        <input type="text" name="mistake" class="form-control" value="{{ old('mistake', $lastGrades->mistake) }}" placeholder="خطأ">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">تنبيه:</label>
                                                        <input type="text" name="alert" class="form-control" value="{{ old('alert', $lastGrades->alert) }}" placeholder="تنبيه">
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
                                                        <input type="text" name="listener_name" class="form-control" value="{{ old('listener_name', $lastGrades->listener_name) }}" placeholder="اسم المستمع">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row absence {{ $lastGrades->absence != 0 ? 'd-none' : '' }}">

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">درجة الدرس الجديد:</label>
                                                        <input type="text" name="lesson_grade" class="form-control" value="{{ old('lesson_grade', $lastGrades->lesson_grade) }}" placeholder="درجة الدرس الجديد">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">درجة أخر 5 صفحات:</label>
                                                        <input type="text" name="last_5_pages_grade" class="form-control" value="{{ old('last_5_pages_grade', $lastGrades->last_5_pages_grade) }}" placeholder="درجة أخر 5 صفحات">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">درجة المراجعة اليومية:</label>
                                                        <input type="text" name="daily_revision_grade" class="form-control" value="{{ old('daily_revision_grade', $lastGrades->daily_revision_grade) }}" placeholder="درجة المراجعة اليومية">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="">درجة السلوك وغيره:</label>
                                                        <input type="text" name="behavior_grade" class="form-control" value="{{ old('behavior_grade', $lastGrades->behavior_grade) }}" placeholder="درجة السلوك وغيره">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">ملاحظات الى ولي الأمر:</label>
                                                        <select name="notes_to_parent" id="" class="select2">
                                                            @foreach($notes as $note)
                                                                <option value="{{ $note->text }}" {{ $note->text == $lastGrades->notes_to_parent ? 'selected': '' }}>{{ $note->text }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        @else
                                            <input type="hidden" name="report_id" value="no" class="form-control">
                                        @endif

                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">الدرس الجديد:</label>
                                                    <input type="text" name="new_lesson" class="form-control" value="{{ $hasReportTomorrow->new_lesson }}" placeholder="الدرس الجديد">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">من:</label>
                                                    <input type="text" name="new_lesson_from" class="form-control" value="{{ $hasReportTomorrow->new_lesson_from }}" placeholder="من">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">إلى:</label>
                                                    <input type="text" name="new_lesson_to" class="form-control" value="{{ $hasReportTomorrow->new_lesson_to }}" placeholder="إلى">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">أخر 5 صفحات:</label>
                                                    <input type="text" name="last_5_pages" class="form-control" value="{{ $hasReportTomorrow->last_5_pages }}" placeholder="أخر 5 صفحات">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">المراجعة اليومية:</label>
                                                    <input type="text" name="daily_revision" class="form-control" value="{{ $hasReportTomorrow->daily_revision }}" placeholder="المراجعة اليومية">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">من:</label>
                                                    <input type="text" name="daily_revision_from" class="form-control" value="{{ $hasReportTomorrow->daily_revision_from }}" placeholder="من">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">إلى:</label>
                                                    <input type="text" name="daily_revision_to" class="form-control" value="{{ $hasReportTomorrow->daily_revision_to }}" placeholder="إلى">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">عدد الصفحات:</label>
                                                    <input type="text" name="number_pages" class="form-control" value="{{ $hasReportTomorrow->number_pages }}" placeholder="عدد الصفحات">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary prevent-multiple-submit-button">
                                                    تحديث
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                    <i class="ft-thumbs-up position-right"></i>
                                                </button>
                                                <button type="submit" name="update_and_send" class="btn btn-danger">
                                                    تحديث وارسال
                                                    <i class="ft-message-square position-right"></i>
                                                </button>
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

        <!-- view assignments of month -->
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

                                            <th>الخيارات</th>
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
                                                <td>
                                                    <a href="{{ route('teachers.report.edit', $dayReport->id) }}" class="btn btn-warning">
                                                        <i class="la la-edit"></i>
                                                    </a>
                                                </td>
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
        <!-- view assignment of month end -->
    </div>

@endsection

@push('js')
    <script>
        $(function() {
            $(document).on('click', '#absence-check', function () {

                if($(this).is(':checked')) {
                    var student_id = $('#student_id').val();
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: '{{ route('teachers.report.absence') }}',
                        data: {'student_id': student_id,},
                        success: function (data) {
                            report = data.report;
                            $('input[name="new_lesson"]').val(report.new_lesson);
                            $('input[name="new_lesson_from"]').val(report.new_lesson_from);
                            $('input[name="new_lesson_to"]').val(report.new_lesson_to);
                            $('input[name="last_5_pages"]').val(report.last_5_pages);
                            $('input[name="daily_revision"]').val(report.daily_revision);
                            $('input[name="daily_revision_from"]').val(report.daily_revision_from);
                            $('input[name="daily_revision_to"]').val(report.daily_revision_to);
                            $('input[name="number_pages"]').val(report.number_pages);
                        }
                    });
                }else{
                    $('input[name="new_lesson"]').val("");
                    $('input[name="new_lesson_from"]').val("");
                    $('input[name="new_lesson_to"]').val("");
                    $('input[name="last_5_pages"]').val("");
                    $('input[name="daily_revision"]').val("");
                    $('input[name="daily_revision_from"]').val("");
                    $('input[name="daily_revision_to"]').val("");
                    $('input[name="number_pages"]').val("");
                }

            })

        });
    </script>
@endpush
