

<style>
    .errors_div{
        display: none;
    }
</style>


@if($errors->any())

    <div class="alert alert-danger">

        @foreach($errors->all() as $error)
            <p class="mb-0"> {{ $error }}</p>
        @endforeach

    </div>

@endif

<div class="alert alert-danger errors_div">
    <ul class="errors_ul"></ul>
</div>
