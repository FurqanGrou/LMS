<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body dir="rtl">

    <div style="color: black !important; font-weight: bold">
        <h4 style="color: black !important; font-weight: bold !important; text-align: center; font-size: 20px !important;">
            تعيين معلم احتياط
        </h4>
        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label>عنوان الحلقة:</label>
                    <p style="display: inline-block">{{ $details->classNumber->title }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>رقم الحلقة:</label>
                    <p style="display: inline-block">{{ $details->class_number }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>موعد الحلقة:</label>
                    <p style="display: inline-block">{{ getPeriod($details->classNumber->period) }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>رابط الحلقة:</label>
                    <p style="display: inline-block">{{ $details->classNumber->zoom_link }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>مسار الحلقة:</label>
                    <p style="display: inline-block">{{ $details->classNumber->path }}</p>
                </div>
            </div>

        </div>
    </div>

</body>

</html>
