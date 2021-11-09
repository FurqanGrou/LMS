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
        <div style="text-align: center;font-family: arial, sans-serif">
            <img src="{{ public_path('dashboard\assets\img\logo3.png') }}" alt="">
            <h1> {{ date('Y') . ' - ' . \Carbon\Carbon::create()->month(10)->format('F') }}  نتيجة شهر </h1>
        </div>

        <div style="text-align: right;font-family: arial, sans-serif">
            <h4 style="padding-right: 10px;font-family: arial, sans-serif;font-size: 18px;">المكرم ولي امر الطالب / ة : <span style="color: brown;font-family: arial, sans-serif;font-size: 16px;">{{ $student_name }}</span></h4>
        </div>

        <div>

            {{-- Totals--}}
            <table dir="rtl" style="width: 100%; font-weight: bold; border-collapse: collapse;">
                <tbody>
                <tr style="height: 40px;">
                    <td colspan="3" style="width: 80%;padding-right:10px;text-align: right;border: 1px solid gray; font-size: 16px;font-family: arial, sans-serif">
                        عدد مرات عدم تسميع الدرس الجديد / Number of not recite the new lesson
                    </td>
                    <td style="border: 1px solid gray;width: 30px; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                        {{ $student->monthlyScores(request()->date_filter)->new_lessons_not_listened ?? 0 }}
                    </td>
                </tr>
                <tr style="height: 40px">
                    <td colspan="3" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                        عدد مرات عدم تسميع اخر 5 صفحات / Number of not recite last 5 pages
                    </td>
                    <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                        {{ $student->monthlyScores(request()->date_filter)->last_five_pages_not_listened ?? 0 }}
                    </td>
                </tr>
                <tr style="height: 40px">
                    <td colspan="3" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                        عدد مرات عدم تسميع المراجعة / Number of not recite the review
                    </td>
                    <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                        {{ $student->monthlyScores(request()->date_filter)->daily_revision_not_listened ?? 0 }}
                    </td>
                </tr>
                <tr style="height: 40px">
                    <td colspan="3" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                        عدد أيام الغياب بعذر / Number of absence days with excuse
                    </td>
                    <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                        {{ $student->monthlyScores(request()->date_filter)->absence_excuse_days ?? 0 }}
                    </td>
                </tr>
                <tr style="height: 40px">
                    <td colspan="3" style="padding-right:10px;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif;text-align: right">
                        عدد أيام الغياب بدون بعذر / Number of absence days without excuse
                    </td>
                    <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                        {{ $student->monthlyScores(request()->date_filter)->absence_unexcused_days ?? 0 }}
                    </td>
                </tr>
                <tr style="height: 40px">
                    <td colspan="3" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                        رقم الصفحة / Page Number
                    </td>
                    <td style="border: 1px solid gray;width: 30px; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                        {{ $student->monthlyScores(request()->date_filter)->lessonPage->page_number ?? '-' }}
                    </td>
                </tr>
                <tr style="height: 40px">
                    <td colspan="3" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                        اسم السورة / Lesson Name
                    </td>
                    <td style="border: 1px solid gray;width: 30px; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                        {{ $student->monthlyScores(request()->date_filter)->lessonPage->lesson_title ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 16px;text-align: center; background: #C6E0B4;font-family: arial, sans-serif">
                        التقدير العام
                        <br>
                        General Score
                    </td>
                    <td style="font-size: 16px;text-align: center;font-family: arial, sans-serif">
                        {{ getRate($student->monthlyScores(request()->date_filter)->avg ?? 100, 'ar') }}
                        <br>
                        {{ getRate($student->monthlyScores(request()->date_filter)->avg ?? 100, 'en') }}
                    </td>
                    <td style="font-size: 16px;text-align: center; background: #C6E0B4;font-family: arial, sans-serif">
                        نسبة
                        <br>
                        Percentage
                    </td>
                    <td style="font-size: 16px;text-align: center;font-family: arial, sans-serif">
                        {{ $student->monthlyScores(request()->date_filter)->avg ?? 100 }}
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</body>

</html>
