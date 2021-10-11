@extends('../front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
<main class="cartContent">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>{{ trans('vars.Cart.cartYour') }}</h3>
            </div>
            <div class="col-12">
                @if (Auth::guard('persons')->user())
                <cart :items="{{ $cartProducts }}"
                    :mode="'cart'"
                    site="{{ $site }}"
                    ></cart>
                @else
                @if (!is_null($unloggedUser))
                <cart :items="{{ $cartProducts }}"
                    :mode="'guest'"
                    site="{{ $site }}"
                    ></cart>
                @else
                <cart :items="{{ $cartProducts }}"
                    :mode="'auth'"
                    site="{{ $site }}"
                    ></cart>
                @endif
                @endif
            </div>
        </div>
    </div>
</main>
@include('front.desktop.partials.footer')
@stop
