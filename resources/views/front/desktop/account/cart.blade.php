@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
<main class="clientArea">
    <div class="container">
        <h3>{{ trans('vars.Cabinet.yourCart') }}</h3>
        <div class="row">
            <div class="col-auto">
                <div class="navArea" id="navArea">
                    <div class="user">
                        <p>{{ trans('vars.General.hello') }}, {{ $userdata->name }}</p>
                        <p>{{ trans('vars.Cabinet.welcomeTo') }} {{ trans('vars.Cabinet.yourCart') }}</p>
                    </div>
                    @include('front.desktop.account.accountMenu')
                </div>
            </div>
            <div class="col">
                <div class="myCart">
                    <div class="row productsList">
                        <div class="col-md-12">
                            @if ($carts['products'])
                            @foreach ($carts['products'] as $key => $cartProd)
                            <div class="row cartItem">
                                <a class="col-auto" href="{{ url('/'.$lang->lang.'/catalog/'.$cartProd->product->category->alias.'/'.$cartProd->product->alias) }}">
                                @if ($cartProd->product->mainImage)
                                    <img src="/images/products/sm/{{ $cartProd->product->mainImage->src }}" alt="" />
                                @else
                                    <img src="/fronts/img/prod/oneProduct.jpg" alt="" />
                                @endif
                                </a>
                                <div class="description col-md">
                                    <a href="{{ url('/'.$lang->lang.'/catalog/'.$cartProd->product->category->alias.'/'.$cartProd->product->alias) }}">
                                        {{ $cartProd->product->translation->name }}
                                    </a>
                                    <div class="params"></div>
                                    <div class="methods"></div>
                                </div>
                                <div class="price col-auto">
                                    <span>{{ $cartProd->product->mainPrice->price }} {{ $mainCurrency->abbr }}</span>
                                    <span></span>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            @if ($carts['subproducts'])
                            @foreach ($carts['subproducts'] as $key => $cart)
                            <div class="row cartItem">
                                <a class="col-auto" href="{{ url('/'.$lang->lang.'/catalog/'.$cart->subproduct->product->category->alias.'/'.$cart->subproduct->product->alias) }}">
                                    @if ($cart->subproduct->product->mainImage)
                                        <img src="/images/products/sm/{{ $cart->subproduct->product->mainImage->src }}" alt="" />
                                    @else
                                        <img src="/fronts/img/prod/oneProduct.jpg" alt="" />
                                    @endif
                                </a>
                                <div class="description col-md">
                                    <a href="{{ url('/'.$lang->lang.'/catalog/'.$cart->subproduct->product->category->alias.'/'.$cart->subproduct->product->alias) }}">
                                    {{ $cart->subproduct->product->translation->name }}
                                    </a>
                                    <div class="params">
                                        <span>{{ trans('vars.Cabinet.size') }}: {{ $cart->subproduct->parameterValue->translation->name }}</span>
                                        <span>{{ trans('vars.Cabinet.qty') }}: {{ $cart->qty }}</span>
                                    </div>
                                    <div class="methods"></div>
                                </div>
                                <div class="price col-auto">
                                    <span>{{ $cart->subproduct->product->mainPrice->price }} {{ $mainCurrency->abbr }}</span>
                                    <span></span>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            @if (count($carts['subproducts']) == 0 && count($carts['products']) > 0)
                            <div class="orderHistory">
                                <p class="text-center">{{ trans('vars.Cabinet.cartEmpty') }}</p>
                            </div>
                            @else
                            <div class="col">
                                <a href="{{ url('/'.$lang->lang.'/cart') }}"><button>{{ trans('vars.TehButtons.btnViewAllCarts') }}</button></a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('front.desktop.partials.footer')
@stop
