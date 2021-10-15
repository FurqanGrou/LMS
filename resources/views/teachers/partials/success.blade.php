@if(session('success'))

    <div class="alert alert-success">
        <p class="mb-0"> {{ session('success') }}</p>
    </div>

@endif

@if(session('error'))

    <div class="alert alert-danger">
        <p class="mb-0"> {{ session('error') }}</p>
    </div>

@endif
