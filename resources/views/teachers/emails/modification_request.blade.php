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

    <div style="text-align: right;font-family: arial, sans-serif">
        <h4 style="padding-right: 10px;font-family: arial, sans-serif;font-size: 18px;"> الطالب: <span style="color: #c72d2d;font-family: arial, sans-serif; font-weight: bold;">{{ $result->student->name  . ' - ' . $result->student->student_number . ' - ' . ($result->student->section == 'male' ? 'بنين' : 'بنات') }}</span></h4>
    </div>

    <div>

        {{-- Details--}}
        <table dir="rtl" style="width: 100%; font-weight: bold; border-collapse: collapse;">
            <tbody>
            <tr style="height: 40px;">
                <td colspan="3" style="width: 80%;padding-right:10px;text-align: right;border: 1px solid gray; font-size: 16px;font-family: arial, sans-serif">
                    المعلم مقدم الطلب
                </td>
                <td style="border: 1px solid gray;width: 30px; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                    {{ $result->teacher->name }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="3" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                    تاريخ اليوم المطلوب التعديل فيه
                </td>
                <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                    {{ $result->date }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="3" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                    الدرجات الجديدة المطلوبة
                </td>
                <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                    {{ $result->new_grades }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="3" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                    ملاحظات أخرى
                </td>
                <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                    {{ $result->notes }}
                </td>
            </tr>

            </tbody>
        </table>

    </div>
</div>
</body>

</html>
