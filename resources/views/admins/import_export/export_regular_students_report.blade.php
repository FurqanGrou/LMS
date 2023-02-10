@extends('admins.layouts.master')
<title>تصدير تقرير الطلاب المنتظمين</title>
<style>
    #students + .select2.select2-container,
    #commitment_type + .select2.select2-container {
        width: 90% !important;
    }
</style>

@livewireStyles

@section('content')

    @include('admins.partials.errors')
    @include('admins.partials.success')

    @livewire('export')

@endsection

@livewireScripts
