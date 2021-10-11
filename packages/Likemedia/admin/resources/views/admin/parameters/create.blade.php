@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<div id="cover">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameters.index') }}">Parameters</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Parameter</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Parameter </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
            trans('variables.add_element') => route('parameters.create'),
        ]
    ])
</div>
@include('admin::admin.alerts')
<div class="list-content">

    <create-parameter :langs="{{ $langs }}"
                      :lang="{{ $lang }}"
                      :categories="{{ $categories }}"
                      :groups="{{ $groups }}"
                      >
    </create-parameter>

</div>
</div>
<script src="{{asset('fronts/js/app.js')}}"></script>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
