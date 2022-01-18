<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب اجتماع مع مراقب</title>
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
        <h4 style="padding-right: 10px;font-family: arial, sans-serif;font-size: 18px;text-align: center;">طلب اجتماع مع مراقب</h4>
    </div>

    <div>

        {{-- Details--}}
        <table dir="rtl" style="width: 100%; font-weight: bold; border-collapse: collapse;">
            <tbody>
            <tr style="height: 40px;">
                <td colspan="2" style="width: 50%;padding-right:10px;text-align: right;border: 1px solid gray; font-size: 16px;font-family: arial, sans-serif">
                    المعلم مقدم الطلب
                </td>
                <td style="border: 1px solid gray;width: 30px; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                    {{ $result->teacher->name }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                    حالة الاجتماع
                </td>
                <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                    {{ $result->status }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                    الوقت المناسب للاجتماع عند المعلم
                </td>
                <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                    {{ $result->favorite_time }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                    سبب الاجتماع
                </td>
                <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                    {{ $result->reason }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td colspan="2" style="padding-right:10px;text-align: right;border: 1px solid gray;font-size: 16px;font-family: arial, sans-serif">
                    وصف الاجتماع
                </td>
                <td style="border: 1px solid gray;width: 30px;font-size: 16px;text-align: center;font-family: arial, sans-serif">
                    {{ $result->description }}
                </td>
            </tr>

            </tbody>
        </table>

    </div>
</div>
</body>

</html>
