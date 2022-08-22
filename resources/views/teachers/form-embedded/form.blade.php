@extends('teachers.layouts.master')

<title>{{ $form->title }}</title>
@section('content')

    @include('teachers.partials.errors')
    @include('teachers.partials.success')


    <iframe src="{{ $form->url }}" title="{{ $form->title }}" style="width: 100%; height: 100vh; border: 0;"></iframe>


@endsection
