@extends('admin::admin.app')
@section('content')

<div id="cover">

    <top-bar-autoupload
                :categories="{{ $categories }}"
                :current="{{ $currentCategory ?? 0 }}">
    </top-bar-autoupload>

    <div class="wrapp">
        <autoupload
                :category="{{ $currentCategory ?? 0 }}"
                :langs="{{ $langs }}"
                :promotions="{{ $promotions }}"
                :sets="{{ $sets }}"
                :collections="{{ $collections }}"
                :brands="{{ $brands }}"
                :categories="{{ $categories }}"
                :dillergroups="{{ $dillerGroups }}"
                >
        </autoupload>
    </div>

</div>

<script src="{{asset('fronts/js/app.js?'.uniqid())}}"></script>
<link rel="stylesheet" href="{{ asset('admin/css/auto-upload.css') }}">
@stop
