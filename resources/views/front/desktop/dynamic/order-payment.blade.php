@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')

<main class="cartContent">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3>{{ trans('vars.Orders.checkout') }}</h3>
            </div>
            <div class="col-12">
                <div class="stepContainer">
                    <div class="step">
                        <span>1</span>
                    </div>
                    <div class="arrow"></div>
                    <div class="step opac">
                        <span>2</span>
                    </div>
                    <div class="arrow"></div>
                    <div class="step opac">
                        <span>3</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @if ($message = Session::get('success'))
            <div class="w3-panel w3-green w3-display-container">
                <span onclick="this.parentElement.style.display='none'"
                        class="w3-button w3-green w3-large w3-display-topright">&times;</span>
                <p>{!! $message !!}</p>
            </div>
            <?php Session::forget('success');?>
            @endif
        @if ($message = Session::get('error'))
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'"
                        class="w3-button w3-red w3-large w3-display-topright">&times;</span>
                <p>{!! $message !!}</p>
            </div>
            <?php Session::forget('error');?>
            @endif
        </div>

        <div class="row">
            <div class="col">
                <order-payment  :items="{{ $cartProducts }}"
                                :countrydelivery="{{ @$_COOKIE['country_id'] }}"
                                :delivery="{{ @$_COOKIE['delivery_id'] }}"
                                :mode="'{{ Auth::guard('persons')->user() ? "auth" : "guest" }}'"
                                :order_id="{{ $order->id }}"
                                :prods="{{ $carts['products'] }}"
                                :subprods="{{ $carts['subproducts'] }}"
                                :cartSets="{{ $carts['sets'] }}"
                                site="{{ $site }}"
                ></order-payment>

                <div class="col-12 titleCeck">
                    <p>{{ trans('vars.Orders.orderReview') }}</p>
                </div>

                <cart
                    :items="{{ $cartProducts }}"
                    mode="order-payment"
                    site="{{ $site }}"
                ></cart>
            </div>

            <cart-summary
                :prods="{{ $carts['products'] }}"
                :subprods="{{ $carts['subproducts'] }}"
                :cartSets="{{ $carts['sets'] }}"
                mode="order-payment"
                site="{{ $site }}"
            ></cart-summary>

        </div>
    </div>
</main>
@include('front.desktop.partials.footer')
@stop
