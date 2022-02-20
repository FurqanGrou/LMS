
{{--@php--}}
{{--    request()->merge(['date_filter' => '2021-12'])--}}
{{--@endphp--}}

@if(\App\User::find($id)->monthlyScores()->first()->mail_status ?? 0)
    <span class="btn btn-success" title="تم ارسال التقرير الشهري {{ getReportMonth() }}">
        <i class="la la-envelope-o"></i>
    </span>
@else
    <span class="btn btn-danger" title="لم يتم ارسال التقرير الشهري بعد! {{ getReportMonth() }}">
        <i class="la la-file-text"></i>
    </span>
@endif
