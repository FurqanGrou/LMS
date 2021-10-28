@extends('teachers.layouts.master')

@section('content')

@include('teachers.partials.errors')
@include('teachers.partials.success')

<form class="form" method="POST" action="{{ route('teachers.request_services.store') }}">

    @csrf
    <input type="hidden" name="form_title" value="{{ $form['title'] }}">
    <input type="hidden" name="form_id" value="{{ $form['id'] }}">
    <input type="hidden" name="submitted_by" value="{{ auth()->guard('teacher_web')->user()->name }}">


    <div class="form-body">
        <h4 class="form-section"><i class="ft-lock"></i>طلب الخدمة - {{ $form['title'] }}</h4>

        <div class="row">
            @foreach ($form->FormInputs as $input)
            <div class="col-md-12">
                @if ($input->type=='text')
                <div class="form-group">
                    <label for="chapter_name">{{$input->title}}</label>
                    <input type="text" id="chapter_name" class="form-control" placeholder="{{$input->title}}" name="{{$input->name}}">
                </div>
                @elseif($input->type=='select')
                <div class="form-group">
                    <label for="student_name" class="w-100">{{$input->title}}</label>
                    <select name="{{$input->name}}" class="form-control select2 w-100" id="student_name">
                        @foreach(\DB::table( \explode(',', $input->attr)[0])->select('id', \explode(',', $input->attr)[1])->get() as $item)
                        <option value="{{ $item->id }}">{{ $item->{\explode(',', $input->attr)[1]} }}</option>
                        @endforeach
                    </select>
                </div>
                @elseif($input->type=='date')
                <div class="form-group">
                    <label for="start_date">{{$input->title}}</label>
                    <input type="date" id="start_date" class="form-control" placeholder="{{$input->title}}" name="{{$input->name}}">
                </div>
                @elseif($input->type=='int')
                <div class="form-group">
                    <label for="start_date">{{$input->title}}</label>
                    <input type="number" id="start_date" class="form-control" placeholder="{{$input->title}}" name="{{$input->name}}">
                </div>
                @elseif($input->type=='hidden')
                    <input type="hidden" name="{{$input->name}}" value="{{ $input->default_value }}">
                @endif
            </div>
            @endforeach

        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="la la-check-square-o"></i> حفظ
        </button>
        <button type="reset" class="btn btn-warning mr-1">
            <i class="ft-x"></i> إلغاء
        </button>
    </div>

</form>

@endsection
