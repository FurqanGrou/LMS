<div>
    <form class="form" method="POST" action="{{ route('teachers.attendance.store') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="d-flex justify-content-between align-items-center">
            <button type="submit" class="btn btn-success" name="login_btn" value="login">
                <i class="ft-log-in"></i> دخول
            </button>
            <p class="white mt-1">{{ \Carbon\Carbon::now()->hour . ':' . \Carbon\Carbon::now()->minute }}</p>
            <button type="submit" class="btn btn-danger" name="logout_btn" value="logout">
                <i class="ft-log-out"></i> خروج
            </button>
        </div>

    </form>
</div>
