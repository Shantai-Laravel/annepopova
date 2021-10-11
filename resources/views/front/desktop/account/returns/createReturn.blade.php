@extends('front.app')
@section('content')
    <div class="fullWidthHeader">
        @include('front.partials.header')
    </div>
    <main>
        <div class="cabinet" id="bodyScroll">
            <ul class="crumbs">
                <li>
                    <a href="{{ url('/'.$lang->lang) }}">Home</a>
                </li>
                <li><a href="#">{{ trans('vars.Cabinet.returns') }}</a></li>
            </ul>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h5>{{ trans('vars.Cabinet.returns') }}</h5>
                    </div>
                    <div class="col-lg-4 col-md-5">
                        <div class="cabinetNavBloc">
                            <div class="cabNavTitle">
                                {{ trans('vars.General.hello') }}, {{ $userdata->name }}
                            </div>
                            
                            @include('front.account.accountMenu')
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        @if ($orders->count() > 0)
                            @foreach ($orders as $key => $order)
                            <div class="row align-items-center justify-content-between historyItem">
                                <div class="col-md-8">
                                    <div>
                                        {{ trans('vars.Cabinet.order') }} <small>Nr. {{ $order->order_hash }}</small>
                                        <span class="delivered">{{ $order->main_status }}</span>
                                    </div>
                                    <div>{{ trans('vars.Cabinet.atDate') }}: {{ date('d-m-Y', strtotime($order->change_status_at)) }}</div>
                                    <div>{{ trans('vars.Cabinet.amount') }}: <b>{{ $order->amount }} {{ $mainCurrency->abbr }}</b></div>
                                    {{-- <div>{{ trans('vars.Cabinet.trackingNumber') }}: <b>Nr. {{ $order->order_hash }}</b></div> --}}
                                </div>
                                <div class="col-md-4 buttGroup">
                                    <a href="{{ url('/'.$lang->lang.'/account/returns/create/'.$order->id) }}" class="butt">Return</a>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('front.partials.footer')
@stop
