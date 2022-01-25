<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page
        {
            font-family: arial, sans-serif;
        }
        * {
            font-family: arial, sans-serif;
        }
        body{
            direction: rtl;
            font-family: arial, sans-serif;
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
            font-size: 12px;
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

<table style="width: 100%; border: none;font-family: arial, sans-serif;">
    <thead>
    <tr style="border: none;">
        <td style="width: 20%;border: none; text-align: center">
            <img src="{{ public_path('dashboard\assets\img\logo2.png') }}" style="width: 120px;display: inline-block" alt="">
            <img src="{{ public_path('dashboard\assets\img\logo3.png') }}" style="width: 120px;display: inline-block" alt="">
        </td>
        <td style="border: none;text-align: center; width: 80%;font-family: arial, sans-serif;">
            <p style="font-family: arial, sans-serif;font-size: 28px;font-weight: bold;">الدليل الشهري لسير ومتابعة {{ getStudentDetails($student_id)->name }} في حلقات مركز الفرقان لتعليم القران الكريم</p>
            <p style="font-size: 28px;">Monthly report for Students in AlFurqan Center for Quran Education</p>
        </td>
        <td style="width: 20%;border: none; text-align: center">
            <img src="{{ public_path('dashboard\assets\img\logo1.png') }}" style="width: 220px;" alt="">
        </td>
    </tr>
    </thead>
</table>

{{-- Names--}}
<table style="border: none; margin-bottom: 40px; margin-top: 40px;font-family: arial, sans-serif;">

    <tr style="border: none;">
        <td style="font-size: 19px;border: none; font-weight: bold;width: 5px">
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;font-family: arial, sans-serif;color:#C65911;">
            اسم الطالب(ة) / Student Name:
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;width: 30px">
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;">
            {{ getStudentDetails($student_id)->name }}
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;width: 50px">
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;color:#C65911;">
            رقم الطالب(ة) / Student ID:
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;width: 30px">
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;">
            {{ getStudentDetails($student_id)->student_number }}
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;width: 50px">
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;color:#C65911;">
            الشهر / Month:
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;width: 30px">
        </td>
        <td style="font-size: 19px;border: none; font-weight: bold;">
            {{ \Carbon\Carbon::create()->month($month)->format('F') . ' - ' . date('Y') }}
        </td>
    </tr>

</table>

<table id="tables" style="display: flex;
            justify-content: space-between;
            margin-bottom: 50px; border: none; width: 100%">
    <tbody style="width: 100%;">

    <tr style="border: none; display: flex;align-items: flex-start;" id="lessons-tables">
        {{-- Lessons--}}
        <td style="border: none; width: 60%">
            <table style="width: 98%;">
                <thead>
                <tr style="min-height: 45px;height: 45px;max-height: 45px;background: #C6E0B4;font-weight: bold">
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold" >
                        التاريخ
                        <br>
                        <br>
                        Date
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        اليوم
                        <br>
                        <br>
                        Day
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        الدرس الجديد
                        <br>
                        <br>
                        New Lesson
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        من
                        <br>
                        <br>
                        From
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        إلى
                        <br>
                        <br>
                        To
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        اخر 5 صفحات
                        <br>
                        <br>
                        Last 5 Pages
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        المراجعة اليومية
                        <br>
                        <br>
                        Daily Revision
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        من
                        <br>
                        <br>
                        From
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        إلى
                        <br>
                        <br>
                        To
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        خطأ
                        <br>
                        <br>
                        Mistake
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        تنبيه
                        <br>
                        <br>
                        Alert
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold;">
                        عدد الصفحات
                        <br>
                        <br>
                        No. of Pages
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align: center;font-weight: bold">
                        اسمع المستمع
                        <br>
                        <br>
                        Listener's Name
                    </td>
                </tr>
                </thead>
                <tbody>
                @for($day=1; $day < \Carbon\Carbon::create()->month($month)->daysInMonth + 1; ++$day)
                    <tr style="min-height: 45px;height: 45px;max-height: 45px;">
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;padding-left: 5px;padding-right: 5px;font-weight: bold">{{ $day }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;padding-left: 5px;padding-right: 5px;background: #C6E0B4;font-weight: bold;font-size: 12px">{{ \Carbon\Carbon::createFromDate($now->year, $now->month, $day)->translatedFormat('l') . ' ' . \Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l') }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->new_lesson ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->new_lesson_from ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->new_lesson_to ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->last_5_pages ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->daily_revision ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->daily_revision_from ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->daily_revision_to ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->mistake ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->alert ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->number_pages ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->listener_name ?? '' }}</td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </td>
        {{-- Grades--}}
        <td style="border: none; width: 40%">
            <table style="width: 100%;">
                <thead>
                <tr style="min-height: 45px;height: 45px;max-height: 45px; background: #C6E0B4; font-weight: bold">
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">
                        الدرس
                        <br>
                        <br>
                        Lesson
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">
                        اخر 5 صفحات
                        <br>
                        <br>
                        Last 5 Pages
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">
                        المراجعة اليومية
                        <br>
                        <br>
                        Daily Revision
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">
                        السلوك والأخرى
                        <br>
                        <br>
                        Behavior & Other
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">
                        المجموع
                        <br>
                        <br>
                        Total
                    </td>
                    <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-weight: bold">
                        ملاحظات المعلم لولي الأمر
                        <br>
                        <br>
                        Teacher's message to the parent
                    </td>
                </tr>
                </thead>
                <tbody>
                @for($day=1; $day < \Carbon\Carbon::create()->month($month)->daysInMonth + 1; ++$day)
                    <tr style="min-height: 45px;height: 45px;max-height: 45px;">
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->lesson_grade ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->last_5_pages_grade ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->daily_revision_grade ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->behavior_grade ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->total ?? '' }}</td>
                        <td style="min-height: 45px;height: 45px;max-height: 45px; text-align:center;font-size:10px;">{{ $monthly_report->where('date', dateFormatMail($now, $day))->first()->notes_to_parent ?? '' }}</td>
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
        <td rowspan="9" style="text-align: center; width: 60%;font-size: 22px">
            نرجو من ولي الأمر متابعة درجات الابن/الابنة يوميا بعناية
            <br>
            <br>
            We ask the parents to follow up their son/daughter daily progress
        </td>

        <td colspan="4" style="text-align: center; background: #C6E0B4;">
            جدول نهاية الشهر End of Month Table
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            عدد مرات عدم تسميع الدرس الجديد / Number of not recite the new lesson
        </td>
        <td style="text-align: center">
            {{ $user_student->monthlyScores->first()->new_lessons_not_listened ?? 0 }}
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            عدد مرات عدم تسميع اخر 5 صفحات / Number of not recite last 5 pages
        </td>
        <td style="text-align: center">
            {{ $user_student->monthlyScores->first()->last_five_pages_not_listened ?? 0 }}
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            عدد مرات عدم تسميع المراجعة / Number of not recite the review
        </td>
        <td style="text-align: center">
            {{ $user_student->monthlyScores->first()->daily_revision_not_listened ?? 0 }}
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            عدد أيام الغياب بعذر / Number of absence days with excuse
        </td>
        <td style="text-align: center">
            {{ $user_student->monthlyScores->first()->absence_excuse_days ?? 0 }}
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            عدد أيام الغياب بدون بعذر / Number of absence days without excuse
        </td>
        <td style="text-align: center">
            {{ $user_student->monthlyScores->first()->absence_unexcused_days ?? 0 }}
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            رقم الصفحة / Page Number
        </td>
        <td style="text-align: center">
            -
        </td>
    </tr>
    <tr>
        <td style="text-align: center; background: #C6E0B4">
            التقدير العام
            <br>
            General Score
        </td>
        <td style="text-align: center">
            {{ getRate($user_student->monthlyScores->first()->avg ?? 100, 'ar') }}
            <br>
            {{ getRate($user_student->monthlyScores->first()->avg ?? 100, 'en') }}
        </td>
        <td style="text-align: center; background: #C6E0B4">
            نسبة
            <br>
            Percentage
        </td>
        <td style="text-align: center">
            {{ $user_student->monthlyScores->first()->avg ?? 100 }}
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>
