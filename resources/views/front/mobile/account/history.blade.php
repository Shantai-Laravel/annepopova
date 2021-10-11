@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')
<main class="clientArea">
    <div class="container">
        <h3>{{ trans('vars.Cabinet.yourOrderHistory') }}</h3>
        <div class="row">
            <div class="col-12">
                <a href="{{ url()->previous() }}"><div class="undoStatic"></div></a>
            </div>
            <div class="col-12">
                <div class="user">
                    <p>{{ trans('vars.General.hello') }}, {{ $userdata->name }}</p>
                    <p>{{ trans('vars.Cabinet.welcomeTo') }} {{ trans('vars.Cabinet.yourOrderHistory') }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="navArea" id="navArea">
                    <div id="pageSelected">
                        {{ trans('vars.Cabinet.yourOrderHistory') }}
                        <svg
                            width="12px"
                            height="6px"
                            viewBox="0 0 12 6"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            >
                            <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g
                                    id="Cabinet_Mob._375-cos"
                                    transform="translate(-325.000000, -156.000000)"
                                    fill="#B22D00"
                                    fill-rule="nonzero"
                                    >
                                    <polygon
                                        id="Shape"
                                        transform="translate(331.000000, 159.000000) scale(1, -1) translate(-331.000000, -159.000000) "
                                        points="331 156 325 162 330.716323 162 337 162"
                                        ></polygon>
                                </g>
                            </g>
                        </svg>
                    </div>
                    @include('front.mobile.account.accountMenu')
                </div>
            </div>
            <div class="col-12">
                <div class="orderHistory">
                    @if ($orders->count() > 0)
                    @foreach ($orders as $key => $order)
                    @php
                    $status = 'delivered';
                    if ($order->main_status == 'inway') { $status = 'indelivering'; }
                    if ($order->main_status == 'cancelled') { $status = 'notRespond'; }
                    @endphp
                    <div class="row order ">
                        <div class="col-12">
                            <p>
                                <span>{{ trans('vars.Cabinet.order') }} Nr. {{ $order->order_hash }}</span>
                                <span class="status {{ $status }}">{{ $order->main_status }}</span>
                            </p>
                            <p>{{ trans('vars.Cabinet.atDate') }}: {{ date('d-m-Y', strtotime($order->change_status_at)) }}</p>
                            <p>{{ trans('vars.Cabinet.amount') }}: {{ $order->amount }} {{ $mainCurrency->abbr }}</p>
                        </div>
                        <div class="col-12 buttonsClient">
                            <div></div>
                            <a href="{{ url('/'.$lang->lang.'/account/history/order/'.$order->id) }}"><button>{{ trans('vars.Cabinet.ordersDetails') }}</button></a>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
@include('front.mobile.partials.footer')
@stop
