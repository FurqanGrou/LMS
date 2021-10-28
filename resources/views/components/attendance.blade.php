<div>
    <form class="form" method="POST" action="{{ route('teachers.attendance.store') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-success" name="login_btn" value="login">
                <i class="ft-log-in"></i> دخول
            </button>
            <p class="white mt-1" style="margin-left: 20px; margin-right: 20px;">{{ \Carbon\Carbon::now()->addHour(8)->format('g:i A') }}</p>
            <button type="submit" class="btn btn-danger" name="logout_btn" value="logout">
                <i class="ft-log-out"></i> خروج
            </button>
        </div>

    </form>
</div>
