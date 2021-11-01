<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>report</title>
</head>
<style>
    td {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        border: gray 1px solid;
        width: 400px;
        height: 35px;
    }

    table {
        border: gray 1px solid;
    }
</style>

<body>
    <div style="border: 2px solid black; width: 70%; margin: 0 auto;">
        <div style="text-align: center;">
            <img src="/dashboard/assets/img/logo3.png" alt="">
            <h1> {{date('Y').' - ' . \Carbon\Carbon::create()->month(date('m'))->subMonth()->format('F')}}  نتيجة شهر </h1>
        </div>

        <div style="text-align: right">
            <h4>المكرم ولي امر الطالب / ة : <span style="color: brown;">{{getStudentDetails($student_id)->name}}</span></h4>
        </div>

        <div>

        {{-- Totals--}}
<table dir="rtl" style="width: 100%; font-weight: bold">
    <tbody>
    <tr style="height: 40px">
        <td rowspan="9" style="text-align: center; width: 20%;font-size: 22px">
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
            {{ getLessonsNotListenedCount($student_id) }}
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            عدد مرات عدم تسميع اخر 5 صفحات / Number of not recite last 5 pages
        </td>
        <td style="text-align: center">
            {{ getLastFivePagesNotListenedCount($student_id)  }}
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            عدد مرات عدم تسميع المراجعة / Number of not recite the review
        </td>
        <td style="text-align: center">
            {{ getDailyRevisionNotListenedCount($student_id) }}
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            عدد أيام الغياب بعذر / Number of absence days with excuse
        </td>
        <td style="text-align: center">
            {{ count($monthly_report->where('absence', '=', -2)->pluck('absence')->toArray()) }}
        </td>
    </tr>
    <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            عدد أيام الغياب بدون بعذر / Number of absence days without excuse
        </td>
        <td style="text-align: center">
            {{ count($monthly_report->where('absence', '=', -5)->pluck('absence')->toArray()) }}
        </td>
    </tr>
    <!-- <tr style="height: 40px">
        <td colspan="3" style="font-size: 18px">
            رقم الصفحة / Page Number
        </td>
        <td style="text-align: center">
            -
        </td>
    </tr> -->
    <tr>
        <td style="text-align: center; background: #C6E0B4">
            <!-- التقدير العام -->
            <!-- <br> -->
            <!-- General Score -->
        </td>
        <td style="text-align: center">
{{--            {{ getRate(100 + ( (getLessonsNotListenedCount($student_id) * -1) +--}}
{{--                        (getLastFivePagesNotListenedCount($student_id) * -1) +--}}
{{--                        (getDailyRevisionNotListenedCount($student_id) * -2) +--}}
{{--                        (count($monthly_report->where('absence', '=', -2)->pluck('absence')->toArray()) * -2) +--}}
{{--                        (count($monthly_report->where('absence', '=', -5)->pluck('absence')->toArray()) * -5)--}}
{{--                        ), 'ar') }}--}}
            <br>
{{--            {{ getRate(100 + ( (getLessonsNotListenedCount($student_id) * -1) +--}}
{{--                        (getLastFivePagesNotListenedCount($student_id) * -1) +--}}
{{--                        (getDailyRevisionNotListenedCount($student_id) * -2) +--}}
{{--                        (count($monthly_report->where('absence', '=', -2)->pluck('absence')->toArray()) * -2) +--}}
{{--                        (count($monthly_report->where('absence', '=', -5)->pluck('absence')->toArray()) * -5)--}}
{{--                        ), 'en') }}--}}
        </td>
        <td style="text-align: center; background: #C6E0B4">
            <!-- نسبة -->
            <!-- <br> -->
            <!-- Percentage -->
        </td>
        <td style="text-align: center">
{{--            {{--}}
{{--                100 + ( (getLessonsNotListenedCount($student_id) * -1) +--}}
{{--                        (getLastFivePagesNotListenedCount($student_id) * -1) +--}}
{{--                        (getDailyRevisionNotListenedCount($student_id) * -2) +--}}
{{--                        (count($monthly_report->where('absence', '=', -2)->pluck('absence')->toArray()) * -2) +--}}
{{--                        (count($monthly_report->where('absence', '=', -5)->pluck('absence')->toArray()) * -5)--}}
{{--                        )--}}
{{--            }}--}}
        </td>
    </tr>
    </tbody>
</table>

        </div>
    </div>
</body>

</html>