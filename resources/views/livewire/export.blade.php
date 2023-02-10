<form class="form" wire:submit.prevent="export"  enctype="multipart/form-data">

    <div class="form-body">
        <h4 class="form-section">
            <i class="la la-file-excel-o"></i>
            تصدير تقرير الطلاب المنتظمين
        </h4>

        <div class="row">
            <div class="col-3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">من</span>
                    </div>
                    <input type="date" required class="form-control" wire:model="date_from" name="date_from" aria-label="التاريخ من">
                </div>
            </div>
            <div class="col-3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">إلى</span>
                    </div>
                    <input type="date" required class="form-control" wire:model="date_to" name="date_to" aria-label="التاريخ إلى">
                </div>
            </div>
        </div>

    </div>

    <div class="form-actions">

            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i>
                تصدير
            </button>

            @if($exporting && !$exportFinished)
                <div class="d-inline font-weight-bold" wire:poll="updateExportProgress">يتم إعداد التقرير...من فضلك إنتظر قليلاً!</div>
            @endif

            @if($exportFinished)
                <div class="d-inline font-weight-bold">تم الإنتهاء، يمكنك تنزيل التقرير <a class="stretched-link primary" wire:click="downloadExport">من هنا</a></div>
            @endif

    </div>

</form>

