<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>تنبيه إنقطاع عن حضور حلقات مركز الفرقان | Absence from attending Al-Furqan Center sessions</title>
</head>
<body dir="rtl">

    <table width="100%">
        <tr>
            <td>
                <p> <span style="font-weight: bold">المكرم ولي أمر الطالب:</span> {{ $student->name . ' - ' . $student->student_number}}</p>
            </td>
            <td dir="ltr">
                <p> <span style="font-weight: bold">Dear parent of student:</span> {{ $student->name . ' - ' . $student->student_number }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>السلام عليكم ورحمة الله وبركاته...أما بعد:</p>
            </td>
{{--            <td>--}}
{{--                <p>السلام عليكم ورحمة الله وبركاته...أما بعد:</p>--}}
{{--            </td>--}}
        </tr>
        <tr>
            <td>
                <p style="font-weight: bold">إشارة إلى انقطاع ابنكم عن الحضور لليوم ({{ dropoutCounts($student->id) }}) على التوالي من غير عذر مسبق، وحرصاً على مستوى أبنائنا وبناتنا من الطلاب والطالبات؛ </p>
            </td>
            <td dir="ltr" width="250">
                <p style="font-weight: bold">Based on the absence of your son continuously for ({{ dropoutCounts($student->id) }}) without excuse, and in order to ensure the educational level of our students, </p>
            </td>
        </tr>
        <tr>
            <td width="250">
                <p>
                    {!! $message_content->content !!}
                </p>
            </td>
            <td dir="ltr" width="250">
                <p>
                    {!! $message_content->content_en !!}
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-weight: bold;">سائلين الله عز وجل لكم التوفيق والسداد</p>
            </td>
            <td dir="ltr">
                <p style="font-weight: bold;">We appreciate your cooperation</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>الشؤون الإدارية</p>
            </td>
            <td dir="ltr">
                <p>Best regards</p>
                <p>Student Affairs</p>
            </td>
        </tr>
    </table>

</body>
</html>
