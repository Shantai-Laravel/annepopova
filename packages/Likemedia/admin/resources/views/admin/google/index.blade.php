@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="gallery">Google API </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Google API </h3>
</div>

<div class="card card-block">
    <div class="row">
        <div class="col-md-12">
            <h6>Export data:</h6>
        </div>
        <div class="col-md-2">
            <label for="">Step 1</label>
            <a href="{{ url('/back/google-api/get-categories-id') }}" class="btn btn-primary btn-block" target="_blank">Get categories id</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 2</label>
            <a href="{{ url('/back/google-api/get-parameters-id') }}" class="btn btn-primary btn-block"  target="_blank">Get parameters id</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 3</label>
            <a href="{{ url('/back/google-api/get-subproducts-id') }}" class="btn btn-primary btn-block" target="_blank">Get Subproducts id</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 4</label>
            <a href="{{ url('/back/google-api/get-trans-data') }}" class="btn btn-primary btn-block" target="_blank">Get Trans data</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h6>Upload data:</h6>
        </div>
        <div class="col-md-2">
            <label for="">Step 1</label>
            <a href="{{ url('/back/google-api/upload-products') }}" class="btn btn-primary btn-block">Upload Products</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 2</label>
            <a href="{{ url('/back/google-api/upload-parameters') }}" class="btn btn-primary btn-block">Upload Parameters</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 3</label>
            <a href="{{ url('/back/google-api/upload-prices') }}" class="btn btn-primary btn-block">Upload Prices</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 4</label>
            <a href="{{ url('/back/google-api/upload-stocks') }}" class="btn btn-primary btn-block">Upload Stocks</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 5</label>
            <a href="{{ url('/back/google-api/upload-images') }}" class="btn btn-primary btn-block">Upload Images</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 6</label>
            <a href="{{ url('/back/google-api/upload-translations') }}" class="btn btn-primary btn-block">Upload Translation</a>
        </div>
    </div>
</div>

{{-- <div class="col-md-2">
    <label for="">google merchant api test</label>
    <a href="{{ url('/back/google-api/google-merchant-api') }}" class="btn btn-primary btn-block">Handle</a>
</div> --}}


@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
