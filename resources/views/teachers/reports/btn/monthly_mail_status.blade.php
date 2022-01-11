
{{--@php--}}
{{--    request()->merge(['date_filter' => '2021-12'])--}}
{{--@endphp--}}

@if(\App\User::find($id)->monthlyScores()->mail_status ?? 0)
    <span class="btn btn-success" title="تم ارسال التقرير الشهري">
        <i class="la la-envelope-o"></i>
    </span>
@else
    <span class="btn btn-danger" title="لم يتم ارسال التقرير الشهري بعد!">
        <i class="la la-file-text"></i>
    </span>
@endif
