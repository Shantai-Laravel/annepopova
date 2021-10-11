@extends('front.'.$device.'.app')
@section('content')
@include('front.'.$device.'.partials.header')

    <main class="notFoundContent">
    <div class="container">
      <div class="row justify-content-center align-items-center flex-column">
        <div class="title">
          404
        </div>
        <div>
          <p>Ups. ceva nu a mers bine.</p>
          <p>Incercati inca o data sau alegeti o optiune din meniul de sus.</p>
          <p>You can continue shopping by pressing one of the following options:</p>
        </div>
        <div class="buttons">
          <a href="{{ url('/'.$lang->lang.'/'.$site.'/promotions') }}">{{ trans('vars.HeaderFooter.promo') }}</a>
          <a href="{{ url('/'.$lang->lang.'/'.$site.'/sale') }}">{{ trans('vars.HeaderFooter.outlet') }}</a>
        </div>
      </div>
    </div>
    </main>

@include('front.'.$device.'.partials.footer')
@stop
