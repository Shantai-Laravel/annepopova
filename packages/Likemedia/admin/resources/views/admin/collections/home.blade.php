@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="collection">Collections </li>
    </ol>
</nav>

<div class="title-block">
    <h3 class="title"> Product Collections </h3>
</div>

<div id="cover">
    <div class="items">

        <collections :collections_prop="{{ $collections }}" :langs="{{ $langs }}"></collections>

    </div>
</div>

@php
     $settings = json_decode(file_get_contents(storage_path('discountRate.json')), true);
@endphp

<div class="form-group col-md-4 col-md-offset-3 text-center"> <hr>
    <form class="" action="{{ url('/back/product-collections/set-discount-rate') }}" method="post">
        {{ csrf_field() }}
        <label for="" class="text-center">Set discount rate %:</label>
        <input type="number" name="rate" value="{{ $settings['discountRate'] }}" class="form-control"> <br>
        <input type="submit" class="btn btn-primary" value="Save">
    </form>
</div>


<script src="{{asset('fronts/js/app.js?'.uniqid())}}"></script>
<link rel="stylesheet" href="{{ asset('admin/css/nestable.css') }}">

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
