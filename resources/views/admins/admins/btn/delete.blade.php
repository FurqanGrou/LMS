
<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#del_admin{{ $id }}">
    <i class="la la-trash"></i>
</button>

<!-- Modal -->
<div id="del_admin{{ $id }}" class="modal fade text-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel10" aria-hidden="true">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header bg-danger white">
                <h4 class="modal-title white" id="myModalLabel10">حذف مشرف</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form action="{{ route('admins.admins.destroy', $id) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <div class="alert alert-danger">
                        <h4>هل أنت متأكد من حذف المشرف</h4>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">إغلاق</button>
                    <input type="submit" value="حسنا" class="btn btn-danger">
                </div>
            </form>
        </div>

    </div>
</div>
