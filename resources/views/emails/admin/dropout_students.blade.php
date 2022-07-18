<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>تنبيه إنقطاع عن حضور حلقات مركز الفرقان</title>
</head>
<body dir="rtl">

    <p>المكرم ولي أمر الطالب: {{ $student->name }}</p>
    <p>الرقم التسلسلي: ({{ $student->student_number }})</p>

    <p>السلام عليكم ورحمة الله وبركاته...أما بعد:</p>

    <p>إشارة إلى انقطاع ابنكم عن الحضور لليوم ({{ dropoutCounts($student->id) }}) على التوالي من غير عذر مسبق، وحرصاً على مستوى أبنائنا وبناتنا من الطلاب والطالبات؛ </p>

    <p>لذا فإننا نود أن نلفت انتباهكم إلى أن الانقطاع يتسبب انخفاض مستوى الطالب، وبناء عليه فإننا نشعركم بضرورة الانضباط في الحضور وعدم الغياب، وفي حال استمرار الانقطاع فسيتم نقل ابنكم من حلقته إلى فصل المنقطعين.</p>

    <p style="font-weight: bold;">سائلين الله عز وجل لكم التوفيق والسداد</p>

    <p>الشؤون الإدارية</p>
</body>
</html>
