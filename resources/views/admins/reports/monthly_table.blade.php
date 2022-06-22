@extends('admins.layouts.master')


@section('content')

    <style>
        body{
            direction: rtl;
        }
        h1 {
            font-size: 24px;
            color: red;
            font-weight: bold;
        }
        table, td, th {
            border: 1px solid black;
        }
        table {
            border-collapse: collapse;
        }
        table thead td {
            color: black !important;
            font-size: 11px;
        }
        table td input,
        .total {
            color: black !important;
            font-size: 12px;
            font-weight: bold !important;
            text-align: center;
            background: transparent !important;
        }
        .custom-border {
            border-bottom: 2px solid black;
        }
        table input {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            outline: 0;
        }
        .swal-footer {
            text-align: center !important;
        }
        #new_lesson,
        #daily_revision,
        #new_lesson + .select2 .select2-selection,
        #daily_revision + .select2 .select2-selection {
            max-width: 140px !important;
        }
        #new_lesson + .select2 .select2-selection--single,
        #daily_revision + .select2 .select2-selection--single {
            min-width: 140px !important;
            max-width: 140px !important;
        }

        select#page_number + .select2 .select2-selection,
        select#page_number + .select2 .select2-selection__rendered
        {
            min-width: 80px !important;
            text-align: right !important;
        }

    </style>

    <table style="width: 100%; border: none;font-family: arial, sans-serif;">
        <thead>
            <tr style="border: none;">

                <td style="border: none;text-align: center; width: 80%;font-family: arial, sans-serif;">
                        <h3 style="font-family: arial, sans-serif;font-weight: bold;">الدليل الشهري لسير ومتابعة
                            {{ getStudentDetails(request()->student_id)->name }} في حلقات مركز الفرقان لتعليم القران الكريم</h3>
                        <h4>Monthly report for Students in AlFurqan Center for Quran Education</h4>
                </td>

            </tr>
        </thead>
    </table>

    @include('admins.partials.errors')
    @include('admins.partials.success')

    <div class="container mt-2">
        <div class="row">

            <div class="col-4">
                <form action="">
                    <fieldset class="form-group">
                        <input type="month" class="form-control" id="month_report" name="date_filter" value="{{ request()->date_filter ?? date('Y') . '-' . date('m') }}">
                    </fieldset>
                </form>
            </div>

            <div class="col-4">
                <form action="">
                    <fieldset class="form-group">
                        <select name="students" id="students_repors" class="select2">
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $student->id == request()->student_id ? 'selected' : '' }}>{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    {{-- Names--}}

    <div class="d-flex flex-row justify-content-around mt-5 black font-weight-bold">
        <p> <span style="color:#C65911;">اسم الطالب(ة) / Student Name:</span>
            {{ getStudentDetails(request()->student_id)->name }}
            -
            <span class="badge badge-primary">{{ getStudentPath(request()->student_id, request()->date_filter) }}</span>
            <span class="badge badge-danger">{{ getStudentDetails(request()->student_id)->study_type == 1 ? 'حضوري' : 'اونلاين' }}</span>
        </p>
        <p style="display: flex; flex-direction: row-reverse">
            &nbsp; {{ getStudentDetails(request()->student_id)->student_number }}
            <span style="color:#C65911;">رقم الطالب(ة) / Student ID:</span>
        </p>
        <p style="display: flex; flex-direction: row-reverse">
            &nbsp; {{ \Carbon\Carbon::create()->year($year)->month($month)->format('F') . ' ' . $year }}
            <span style="color:#C65911;">الشهر / Month:</span>
        </p>

        <p>
            <a href="#" id="edit-repo" class="btn btn-info"> تعديل</a>
        </p>
    </div>

    <form id="monthly_report" action="#">
        @csrf
        <input type="hidden" id="student_id" name="student_id" value="{{ request()->student_id }}">

        <table id="tables" style="pointer-events:none;display: flex;
            justify-content: space-between;
            margin-bottom: 50px;
            border: none;
            width: 100%">
        <tbody style="width: 100%;">
            <tr style="border: none; display: flex;align-items: flex-start;" id="lessons-tables">

                {{-- Lessons--}}
                <td style="border: none; width: 60%">
                    <table style="width: 98%;" id="lessons">
                        <thead>
                        <tr style="min-height: 45px;height: 45px;max-height: 45px;background: #C6E0B4;font-weight: bold">
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold" >التاريخ</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">اليوم</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold; width: 120px">الدرس الجديد</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold;width: 35px;">من</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold;width: 35px;">إلى</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold; width: 50px;">اخر 5 صفحات</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">المراجعة اليومية</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold;width: 35px;">من</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold;width: 35px;">إلى</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold;width: 35px;">خطأ</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold;width: 35px;">تنبيه</td>
                            <td style="min-height: 45px; height: 45px;max-height: 45px; text-align: center;font-weight: bold;width: 40px;">عدد الصفحات</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">اسم المستمع</td>
                        </tr>
                        </thead>

                        <tbody>
                        @for($day=1; $day < \Carbon\Carbon::create()->year($year)->month($month)->daysInMonth + 1; ++$day)
                            <tr style="min-height: 45px;height: 45px;max-height: 45px;" class="{{ str_contains(\Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l') ,'Friday') ? 'custom-border' : ''  }} {{ getCurrentDayClass($now, $day) . ' ' .getTodayMailStatusClass($now, $day, request()->student_id) }}">
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;padding-left: 5px;padding-right: 5px;font-weight: bold">{{ $day }}</td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;padding-left: 5px;padding-right: 5px;background: #C6E0B4;font-weight: bold;font-size: 12px;color: black;" class="day-name {{ getCurrentDayClass($now, $day) }}">{{ \Carbon\Carbon::createFromDate($now->year, $now->month, $day)->translatedFormat('l') }}</td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;width: 120px">
                                    <input type="hidden" name="date" {{ disableRecord($now, $day) }} value="{{ \Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l d-m-Y') }}">
                                    <input type="hidden" name="created_at" {{ disableRecord($now, $day) }} value="{{ \Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('Y-m-d') }}">

                                    <select name="new_lesson" {{ disableRecord($now, $day) }} id="new_lesson" class="{{ getCurrentDayClass($now, $day) }} select2 js-select2-tags" style="width: 100%;height: 100%">
                                        <option value=""></option>
                                        @foreach($new_lessons as $new_lesson)
                                            <option value="{{ $new_lesson->name }}">{{ $new_lesson->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="new_lesson_from" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="new_lesson_to" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="last_5_pages" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <select name="daily_revision" {{ disableRecord($now, $day) }} id="daily_revision" class="{{ getCurrentDayClass($now, $day) }} select2 js-select2-daily-revision-tags" style="width: 100%;height: 100%">
                                        <option value=""></option>
                                        @foreach($daily_revision as $revision)
                                            <option value="{{ $revision->name }}">{{ $revision->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="daily_revision_from" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="daily_revision_to" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="mistake" value="" {{ disableRecord($now, $day) }} class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="alert" value="" {{ disableRecord($now, $day) }} class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="number_pages" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="listener_name" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                            </tr>
                        @endfor
                        </tbody>

                    </table>
                </td>

                {{-- Grades--}}
                <td style="border: none; width: 40%">
                    <table style="width: 100%;" id="grades">
                        <thead>
                        <tr style="min-height: 45px;height: 45px;max-height: 45px; background: #C6E0B4; font-weight: bold">
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold;width: 60px;">الدرس</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">اخر 5 صفحات</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">المراجعة اليومية</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">السلوك والأخرى</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">المجموع</td>
                            <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold;width: 50px;">ملاحظات المعلم لولي الأمر</td>
                        </tr>
                        </thead>
                        <tbody>
                        @for($day=1; $day < \Carbon\Carbon::create()->year($year)->month($month)->daysInMonth + 1; ++$day)
                            <tr style="min-height: 45px;height: 45px;max-height: 45px;" class="{{ str_contains(\Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l') ,'Friday') ? 'custom-border' : '' }}">
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input type="hidden" name="date" {{ disableRecord($now, $day) }} value="{{ \Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l d-m-Y') }}">
                                    <input type="hidden" name="created_at" {{ disableRecord($now, $day) }} value="{{ \Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('Y-m-d') }}">
                                    <input style="width: 100%;" type="text" name="lesson_grade" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="last_5_pages_grade" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="daily_revision_grade" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">
                                    <input style="width: 100%;" type="text" name="behavior_grade" {{ disableRecord($now, $day) }} value="" class="{{ getCurrentDayClass($now, $day) }}">
                                </td>
                                <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;" class="{{ getCurrentDayClass($now, $day) }}">
                                    <span class="total"></span>
                                </td>
                                <td style="width: 50px;min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;" class="{{ getCurrentDayClass($now, $day) }}">
                                    <select name="notes_to_parent" {{ disableRecord($now, $day) }} id="" class="{{ getCurrentDayClass($now, $day) }} select2" style="width: 100%;height: 100%">
                                        <option value=""></option>
                                        <option value="الطالب غائب" {{ str_contains(\Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l') ,'Friday') || str_contains(\Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l') ,'Saturday') ? 'disabled' : '' }}>الطالب غائب</option>
                                        @foreach($notes as $note)
                                            <option value="{{ $note->text }}" {{ str_contains($note->text, 'دوام 3 أيام') && ( str_contains(\Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l') ,'Friday') || str_contains(\Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l') ,'Saturday') ) ? 'disabled' : '' }}>{{ $note->text }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

        {{-- Totals--}}
        <table style="width: 100%; font-weight: bold">
            <tbody>
            <tr style="height: 40px">
                <td rowspan="9" style="text-align: center; width: 40%;font-size: 15px">

                    <br>
                    <br>

                </td>

                <td colspan="4" style="text-align: center; background: #C6E0B4;">
                    جدول نهاية الشهر End of Month Table
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="font-size: 14px">
                    عدد مرات عدم تسميع الدرس الجديد / Number of not recite the new lesson
                </td>
                <td colspan="2" style="text-align: center">
                    {{ $user_student->monthlyScores->first()->new_lessons_not_listened ?? 0 }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="font-size: 14px">
                    عدد مرات عدم تسميع اخر 5 صفحات / Number of not recite last 5 pages
                </td>
                <td colspan="2" style="text-align: center">
                    {{ $user_student->monthlyScores->first()->last_five_pages_not_listened ?? 0 }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="font-size: 14px">
                    عدد مرات عدم تسميع المراجعة / Number of not recite the review
                </td>
                <td colspan="2" style="text-align: center">
                    {{ $user_student->monthlyScores->first()->daily_revision_not_listened ?? 0 }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="font-size: 14px">
                    عدد أيام الغياب بعذر / Number of absence days with excuse
                </td>
                <td colspan="2" style="text-align: center">
                    {{ $user_student->monthlyScores->first()->absence_excuse_days ?? 0 }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="font-size: 14px">
                    عدد أيام الغياب بدون بعذر / Number of absence days without excuse
                </td>
                <td colspan="2" style="text-align: center">
                    {{ $user_student->monthlyScores->first()->absence_unexcused_days ?? 0 }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="font-size: 14px">
                    رقم الصفحة / Page Number
                </td>
                <td colspan="2" style="text-align: center">
                    <select name="page_number" id="page_number">
                        <option value=""></option>

                        @php
                            $student_path = getStudentPath(request()->student_id);
                            if(request()->date_filter) {
                                $student_path = getStudentPath(request()->student_id, request()->date_filter) ?? getStudentPath(request()->student_id);
                            }
                        @endphp

                        @if($student_path == "قسم الهجاء")
                            @foreach(\App\NooraniaPage::query()->get() as $lesson_page)
                                <option value="{{ $lesson_page->id }}" {{ isset($user_student->monthlyScores->first()->noorania_page_id) && $user_student->monthlyScores->first()->noorania_page_id == $lesson_page->id ? 'selected' : ''}}>{{ $lesson_page->page_number }}</option>
                            @endforeach
                        @else
                            @foreach($lesson_pages as $lesson_page)
                                <option value="{{ $lesson_page->id }}" {{ isset($user_student->monthlyScores->first()->lesson_page_id) && $user_student->monthlyScores->first()->lesson_page_id == $lesson_page->id ? 'selected' : ''}}>{{ $lesson_page->page_number }}</option>
                            @endforeach
                        @endif

                    </select>
                    <br>
                    <span id="lesson_name">{{ $user_student->monthlyScores->first()->lessonPage->lesson_title ?? '-' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="1" style="text-align: center; background: #C6E0B4; display: flex; justify-content: space-between">
                    <div style="border: 1px solid black;width: 50%">
                        التقدير العام
                        <br>
                        General Score
                    </div>
                    <div style="border: 1px solid black;width: 50%">
                        {{ getRate($user_student->monthlyScores->first()->avg ?? 100, 'ar') }}
                        <br>
                        {{ getRate($user_student->monthlyScores->first()->avg ?? 100, 'en') }}
                    </div>

                </td>
                <td style="text-align: center; background: #C6E0B4">
                    نسبة
                    <br>
                    Percentage
                </td>
                <td style="text-align: center; padding: 5px 15px">
                    {{ $user_student->monthlyScores->first()->avg ?? 100 }}
                </td>
            </tr>
            </tbody>
        </table>

    </form>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

@endsection

@push('js')

    <script>
        $(document).ready(function(){

            $('#edit-repo').on('click',function(){
                $('#tables').css('pointer-events','unset');
            });


            setTimeout(function() {
                if($('html').hasClass('loaded')){
                    $('#test1.nav-link.nav-menu-main.menu-toggle').click();
                }
            },2000);

            @foreach($reports as $report)
                var date = $(document).find("input[value='{{ $report->date }}']");
                var current_row = date.closest('tr');

                var data = {
                    id: '{{ $report->new_lesson }}',
                    text: '{{ $report->new_lesson }}',
                };
                var newOption = new Option(data.text, data.id, true, true);
                current_row.find('.js-select2-tags').prepend(newOption).trigger('change');
                current_row.find("input[name=new_lesson_from]").val('{{ $report->new_lesson_from }}');
                current_row.find("input[name=new_lesson_to]").val('{{ $report->new_lesson_to }}');
                current_row.find("input[name=last_5_pages]").val('{{ $report->last_5_pages }}');

                var data = {
                    id: '{{ $report->daily_revision }}',
                    text: '{{ $report->daily_revision }}',
                };
                var newOption = new Option(data.text, data.id, true, true);
                current_row.find('.js-select2-daily-revision-tags').prepend(newOption).trigger('change');

                current_row.find("input[name=daily_revision_from]").val('{{ $report->daily_revision_from }}');
                current_row.find("input[name=daily_revision_to]").val('{{ $report->daily_revision_to }}');
                current_row.find("input[name=mistake]").val('{{ $report->mistake }}');
                current_row.find("input[name=alert]").val('{{ $report->alert }}');
                current_row.find("input[name=number_pages]").val('{{ $report->number_pages }}');
                current_row.find("input[name=listener_name]").val('{{ $report->listener_name }}');
                current_row.find("input[name=lesson_grade]").val('{{ $report->lesson_grade }}');
                current_row.find("input[name=last_5_pages_grade]").val('{{ $report->last_5_pages_grade }}');
                current_row.find("input[name=daily_revision_grade]").val('{{ $report->daily_revision_grade }}');
                current_row.find("input[name=behavior_grade]").val('{{ $report->behavior_grade }}');
                current_row.find("select[name=notes_to_parent]").val('{{ $report->notes_to_parent }}');
                current_row.find("select[name=notes_to_parent]").trigger('change');
                current_row.find(".total").html('{{ $report->total }}');

                if('{{$report->mail_status}}' != 1){
                    current_row.find('td.day-name').addClass('bg-danger');
                    current_row.find('td.day-name').removeClass('custom-bg-gray');
                }

                if(current_row.find('select[name="notes_to_parent"]').val() == 'الطالب غائب'
                    || current_row.find('select[name="notes_to_parent"]').val() == 'دوام 3 أيام'
                    || current_row.find('select[name="notes_to_parent"]').val() == 'نشاط لا صفي'
                ){

                    let grades_row = date.closest('#grades tr'),
                        total = grades_row.find('.total');
                    grades_row.find("input").attr('disabled', true);

                    if(current_row.find('select[name="notes_to_parent"]').val() != 'نشاط لا صفي') {
                        if({{$report->absence}} == -2){
                            total.parent().addClass('bg-success');
                        }
                        if({{$report->absence}} == -5){
                            total.parent().addClass('bg-danger');
                        }
                    }
                }

            @endforeach

            setTimeout(function() {
                $(document).on('change', 'form#monthly_report #lessons input, form#monthly_report #lessons select', function (e) {
                    var student_id = $('#student_id').val();
                    var current_row = $(this).closest('tr'),
                        date = current_row.find("input[name=date]").val(),
                        created_at = current_row.find("input[name=created_at]").val(),
                        new_lesson = current_row.find("select[name=new_lesson]").val();
                        new_lesson_from = current_row.find("input[name=new_lesson_from]").val(),
                        new_lesson_to = current_row.find("input[name=new_lesson_to]").val(),
                        last_5_pages = current_row.find("input[name=last_5_pages]").val(),
                        daily_revision = current_row.find("select[name=daily_revision]").val();
                        daily_revision_from = current_row.find("input[name=daily_revision_from]").val(),
                        daily_revision_to = current_row.find("input[name=daily_revision_to]").val(),
                        mistake = current_row.find("input[name=mistake]").val(),
                        alert = current_row.find("input[name=alert]").val(),
                        number_pages = current_row.find("input[name=number_pages]").val(),
                        listener_name = current_row.find("input[name=listener_name]").val();

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '{{ route('admins.report.table', request()->student_id) }}?type=lessons',
                        data: {
                            'student_id': student_id,
                            'date': date,
                            'created_at': created_at,
                            'new_lesson': new_lesson,
                            'new_lesson_from': new_lesson_from,
                            'new_lesson_to': new_lesson_to,
                            'last_5_pages': last_5_pages,
                            'daily_revision': daily_revision,
                            'daily_revision_from': daily_revision_from,
                            'daily_revision_to': daily_revision_to,
                            'mistake': mistake,
                            'alert': alert,
                            'number_pages': number_pages,
                            'listener_name': listener_name,
                        },
                        success: function (data) {
                            // console.log(data.report)
                        }
                    });

                })

                $(document).on('change', 'form#monthly_report #grades input, form#monthly_report #grades select', function (e) {
                    var student_id = $('#student_id').val();
                    var current_row = $(this).closest('tr'),
                        date = current_row.find("input[name=date]").val(),
                        created_at = current_row.find("input[name=created_at]").val(),
                        lesson_grade = current_row.find("input[name=lesson_grade]").val(),
                        last_5_pages_grade = current_row.find("input[name=last_5_pages_grade]").val(),
                        daily_revision_grade = current_row.find("input[name=daily_revision_grade]").val(),
                        behavior_grade = current_row.find("input[name=behavior_grade]").val(),
                        notes_to_parent = current_row.find("select[name=notes_to_parent]").val();

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '{{ route('admins.report.table', request()->student_id) }}?type=grades',
                        data: {
                            'student_id': student_id,
                            'date': date,
                            'created_at': created_at,
                            'lesson_grade': lesson_grade,
                            'last_5_pages_grade': last_5_pages_grade,
                            'daily_revision_grade': daily_revision_grade,
                            'behavior_grade': behavior_grade,
                            'notes_to_parent': notes_to_parent,
                        },
                        success: function (data) {
                            // console.log(data.report)
                        }
                    });

                    let grades = [
                        parseInt(lesson_grade),
                        parseInt(last_5_pages_grade),
                        parseInt(daily_revision_grade),
                        parseInt(behavior_grade)
                    ];

                    let sum = 0;
                    if(notes_to_parent != 'الطالب غائب'){
                        grades.forEach(sumTotal);
                    }
                    function sumTotal(item) {
                        if (!isNaN(item)){
                            sum += item;
                        }
                    }

                    current_row.find(".total").html(sum);
                })

            }, 2000);


            //=============================================================================================================================

            $(document).on('change', '[name="notes_to_parent"]', function (e) {
                let current_row = $(this).closest('tr'),
                    notes_to_parent = current_row.find("select[name=notes_to_parent]").val(),
                    lesson_grade = current_row.find("input[name=lesson_grade]"),
                    last_5_pages_grade = current_row.find("input[name=last_5_pages_grade]"),
                    daily_revision_grade = current_row.find("input[name=daily_revision_grade]"),
                    behavior_grade = current_row.find("input[name=behavior_grade]"),
                    total = current_row.find(".total");

                if(notes_to_parent == 'الطالب غائب'
                    || notes_to_parent == 'دوام 3 أيام'
                    || notes_to_parent == 'نشاط لا صفي'
                ){
                    current_row.find("input").attr('disabled', true);
                    if(notes_to_parent == 'نشاط لا صفي') {
                        lesson_grade.val(1);
                        last_5_pages_grade.val(2);
                        daily_revision_grade.val(1);
                        behavior_grade.val(1);
                    }else{
                        lesson_grade.val('غ');
                        last_5_pages_grade.val('');
                        daily_revision_grade.val('');
                        behavior_grade.val('');
                        total.parent().addClass('bg-danger');
                    }

                    let date_row = current_row.find("input[name=date]").val(),
                        lesson_date = $('form#monthly_report #lessons').find("input[value='"+ date_row +"']"),
                        lesson_row  = lesson_date.closest('tr');

                    let new_lesson = lesson_row.find('select[name="new_lesson"] :selected').text(),
                        new_lesson_from = lesson_row.find('input[name="new_lesson_from"]').val(),
                        new_lesson_to  = lesson_row.find('input[name="new_lesson_to"]').val(),
                        last_5_pages   = lesson_row.find('input[name="last_5_pages"]').val(),
                        daily_revision = lesson_row.find('select[name="daily_revision"] + .select2 #select2-daily_revision-container').attr('title'),
                        daily_revision_from = lesson_row.find('input[name="daily_revision_from"]').val(),
                        daily_revision_to   = lesson_row.find('input[name="daily_revision_to"]').val(),
                        number_pages = lesson_row.find('input[name="number_pages"]').val();

                    let next_lesson_row = lesson_row.next(),
                        next_row_status = next_lesson_row.find('input[name="new_lesson_from"]').is(':disabled');
                    if(next_row_status == true){
                        next_lesson_row = lesson_row.next().next().next();
                    }

                    var data_new_lesson = {
                        id: new_lesson,
                        text: new_lesson,
                    };
                    var newOptionLesson = new Option(data_new_lesson.text, data_new_lesson.id, true, true);

                    next_lesson_row.find('input[name="new_lesson_from"]').val(new_lesson_from);
                    next_lesson_row.find('input[name="new_lesson_to"]').val(new_lesson_to);
                    next_lesson_row.find('input[name="last_5_pages"]').val(last_5_pages);
                    next_lesson_row.find('input[name="daily_revision_from"]').val(daily_revision_from);
                    next_lesson_row.find('input[name="daily_revision_to"]').val(daily_revision_to);
                    next_lesson_row.find('input[name="number_pages"]').val(number_pages);

                    var data_revision = {
                        id: daily_revision,
                        text: daily_revision,
                    };
                    var newOptionRevision = new Option(data_revision.text, data_revision.id, true, true);
                    next_lesson_row.find('.js-select2-daily-revision-tags').prepend(newOptionRevision).trigger('change');
                    setTimeout(function (){
                        next_lesson_row.find('.js-select2-tags').prepend(newOptionLesson).trigger('change');
                    }, 1000);

                }else{
                    current_row.find("input").attr('disabled', false);
                    if(isNaN(lesson_grade.val())){
                        lesson_grade.val('');
                    }
                    total.parent().removeClass('bg-danger');
                }
            });

            //=============================================================================================================================

            $(document).on('change', 'form input#month_report', function (e) {

                window.location.href = "{{ route('admins.report.table', request()->student_id) }}?date_filter=" + $(this).val() + "";

            })

            $(document).on('change', 'form select#students_repors', function (e) {

                var url = '{{ route("admins.report.table", ":student_id") }}';
                url = url.replace(':student_id', $(this).val());

                window.location.href = url;

            })

            $(document).on('submit', 'form#monthly_report', function (e) {
                e.preventDefault();
            });

            $(document).on('click', '#btn-send-report', function (e) {

                e.preventDefault();

                swal({
                    title: "هل أنت متأكد من ارسال التقرير؟",
                    text: "بعد الضغط على تأكيد لن يمكنك التراجع عن عملية الارسال!",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "لا، إلغاء!",
                            value: null,
                            visible: true,
                            className: "btn-danger",
                            closeModal: false,
                        },
                        confirm: {
                            text: "نعم، ارسال!",
                            value: true,
                            visible: true,
                            className: "btn-success",
                            closeModal: false
                        }
                    }
                }).then((isConfirm) => {
                        if (isConfirm) {
                            $('#form-report-send').submit();
                            swal("يتم الارسال...!", "تتم حالياً عملية الارسال", "info");
                        } else {
                            swal("تم الإلغاء!", "تم إلغاء عملية الارسال", "error");
                        }
                    });
            });

            $(".js-select2-tags").select2({
                tags: true
            });
            $(".js-select2-daily-revision-tags").select2({
                tags: true
            });

            $(document).on('change', 'select#page_number', function (e) {
                var student_id = $('#student_id').val();
                var page_number_id = $('#page_number').val();
                var month_year = '{{ !is_null(request()->date_filter) ? request()->date_filter : date('Y') . '-' . date('m') }}';

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '{{ route('admins.report.changePageNumber', request()->student_id) }}',
                    data: {
                        'student_id': student_id,
                        'month_year': month_year,
                        'page_number_id': page_number_id,
                    },
                    success: function (data, textStatus, xhr) {
                        $('span#lesson_name').html(data.lesson_title);
                    },
                });
            })

            $(document).on('change', 'form#monthly_report #grades input, form#monthly_report #grades select[name="notes_to_parent"]', function (e) {

                e.preventDefault();

                let report_date = $(this).parent().parent().find("input[name=created_at]").val();
                let created_at = '{{ request()->date_filter ?? \Carbon\Carbon::now()->format('Y-m') }}';
                let student_id = '{{ request()->student_id }}';

                // fire update monthly scores event
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '{{ route('admins.report.updateMonthlyScoresEvent') }}',
                    data: {
                        'student_id': student_id,
                        'created_at': created_at,
                        'report_date': report_date,
                    },
                });
            });

            jQuery("#month_report").datepicker( {
                format: "mm-yyyy",
                startView: "months",
                minViewMode: "months"
            });

        });
    </script>
@endpush
