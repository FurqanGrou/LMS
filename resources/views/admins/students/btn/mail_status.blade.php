

@if(getMailStatus($id) == 1)
    <span class="btn btn-success" title="تم ارسال التقرير"><i class="la la-envelope-o"></i></span>
@elseif(getMailStatus($id) == 404)
    <span class="btn btn-danger" title="لم يتم إدخال تقرير اليوم!"><i class="la la-file-text"></i></span>
@else
    <span class="btn btn-warning" title="لم يتم ارسال التقرير بعد!"><i class="la la-envelope-o"></i></span>
@endif
