<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>تعيين الاجازت للطلاب</title>
</head>
<body dir="rtl">
    <h2>تم تعيين الاجازت للطلاب بنجاح :)</h2>
    <h4>من الفترة {{ $date_from }} وحتى {{ $date_to }}</h4>

    <ol>
        @foreach($students as $student)
            <li>{{ $student }}</li>
        @endforeach
    </ol>
</body>
</html>
