@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')
<main class="contactContent cartClass">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ url()->previous() }}"><div class="undoStatic"></div></a>
            </div>
            <div class="col-12">
                <h3>{{ $page->translation->title }}</h3>
            </div>
        </div>
    </div>
    <div class="container shippSize">
        <div class="row" style="margin-top: 30px;">
            <div class="col-12">
                {!! $page->translation->body !!}
            </div>
        </div>
    </div>
</main>
@include('front.mobile.partials.footer')
@stop
