<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body dir="rtl">

    <div style="color: black !important; font-weight: bold">
        <h4 style="color: black !important; font-weight: bold !important; text-align: center; font-size: 20px !important;"> {{ $details['type'] }} </h4>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>اسم المعلم:</label>
                    <p style="display: inline-block">{{ auth('teacher_web')->user()->name }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>رقم المعلم:</label>
                    <p style="display: inline-block">{{ auth('teacher_web')->user()->teacher_number }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>تاريخ الإذن المطلوب:</label>
                    <p style="display: inline-block">{{ $details['date_excuse'] }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>تاريخ تقديم الطلب:</label>
                    <p style="display: inline-block">{{ Carbon\Carbon::now()->format('g:i A Y-m-d') }}</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>سبب الإذن - العذر:</label>
                    <p style="display: inline-block">{!! ($details['reason_excuse'] == 'other' && $details['type'] == 'absence') ? $details['absence_reason'] : $details['reason_excuse'] !!}</p>
                </div>
            </div>

            @if(@$details['duration_delay'])
                <div class="col-md-4">
                    <div class="form-group">
                        <label>مدة التأخير - دقائق:</label>
                        <p style="display: inline-block">{{ @$details['duration_delay'] }}</p>
                    </div>
                </div>
            @endif

            @if(@$details['exit_time'])
                <div class="col-md-4">
                    <div class="form-group">
                        <label>موعد الخروج:</label>
                        <p style="display: inline-block">{{ @$details['exit_time'] }}</p>
                    </div>
                </div>
            @endif

            @if(@$details['additional_attachments_path'])
                <div class="col-md-4">
                    <div class="form-group">
                        <a style="display: block !important;" href="{{ $details['additional_attachments_path'] }}" class="btn btn-primary">تنزيل المرفقات الإضافية</a>
                    </div>
                </div>
            @endif

            @if(@$details['class_numbers'])
                <div class="col-md-4">
                    <div class="form-group">
                        <label>رقم الحلقة/الحلقات:</label>
                        @foreach(explode(',', $details['class_numbers']) as $classNumber)
                            <br>
                            <span class="badge badge-primary">- {{ $classNumber }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

</body>

</html>
