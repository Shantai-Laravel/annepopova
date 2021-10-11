@extends('admin::admin.app')
@section('content')

    <div id="cover">
        <div class="card">
            <div class="header-space"></div>
            <top-bar-translations></top-bar-translations>

            <group-translations :langs="{{ $langs }}"></group-translations>

        </div>
    </div>

    <script src="{{asset('fronts/js/app.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('admin/css/auto-upload.css') }}">
@stop
