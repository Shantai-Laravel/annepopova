@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')

    <main class="wishContent politiques">
        <h3>{{ $page->translation->title }}</h3>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                      {!! $page->translation->body !!}
                    </div>
                </div>
            </div>
        </section>
    </main>

@include('front.desktop.partials.footer')
@stop
