<div>
    <lable class="white">طلبات الخدمة</lable>
    <select name="" id="request_form_type" class="select2">
        <option value="">-</option>

        @foreach(\App\Form::all() as $form)
{{--            <option value="{{ route('teachers.request_services.form', $form->id) }}" {{ isset(request("form")->id) && request("form")->id == $form->id  ? 'selected' : '' }}>{{ $form->title }}</option>--}}
        @endforeach
    </select>
</div>
