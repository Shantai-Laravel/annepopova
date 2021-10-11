@extends('../front.mobile.app')
@section('content')
@include('front.mobile.partials.header')
<main class="cartContent cartClass">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ url()->previous() }}"><div class="undoStatic"></div></a>
            </div>
            <div class="col-md-12">
                <h3>{{ trans('vars.Cart.cartYour') }}</h3>
            </div>
            <div class="col-12">
                <div class="row productsList">
                    <div class="col-12">
                        @if (Auth::guard('persons')->user())
                        <cart-mob :items="{{ $cartProducts }}"
                            :mode="'cart'"
                            site="{{ $site }}"
                            ></cart-mob>
                        @else
                        @if (!is_null($unloggedUser))
                        <cart-mob :items="{{ $cartProducts }}"
                            :mode="'guest'"
                            site="{{ $site }}"
                            ></cart-mob>
                        @else
                        <cart-mob :items="{{ $cartProducts }}"
                            :mode="'auth'"
                            site="{{ $site }}"
                            ></cart-mob>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('front.mobile.partials.footer')
@stop
