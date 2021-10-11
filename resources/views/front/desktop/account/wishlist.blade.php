@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
<main class="clientArea">
    <div class="container">
        <h3>{{ trans('vars.Cabinet.yourFavorites') }}</h3>
        <div class="row">
            <div class="col-auto">
                <div class="navArea" id="navArea">
                    <div class="user">
                        <p>{{ trans('vars.General.hello') }}, {{ $userdata->name }}</p>
                        <p>{{ trans('vars.Cabinet.welcomeTo') }} {{ trans('vars.Cabinet.yourFavorites') }}</p>
                    </div>
                    @include('front.desktop.account.accountMenu')
                </div>
            </div>
            <div class="col">
                <div class="myWish">
                    @if (count($wishs['products']) > 0)
                    @foreach ($wishs['products'] as $key => $wishProduct)
                    <div class="row lngwItem jrwItem">
                        <a class="col-auto" href="{{ url('/' . $lang->lang . '/catalog/'. $wishProduct->product->category->alias . '/' . $wishProduct->product->alias) }}">
                            @if ($wishProduct->product->mainImage)
                            <img src="/images/products/sm/{{ $wishProduct->product->mainImage->src }}" alt="{{ $wishProduct->product->translation->name }}" />
                            @else
                            <img src="/fronts/img/jrw.jpg" alt="" />
                            @endif
                        </a>
                        <div class="description col-md">
                            <a href="product.html">
                            {{ $wishProduct->product->translation->name }}
                            </a>
                            <div class="price">
                                <span>{{ $wishProduct->product->mainPrice->price }} {{ $mainCurrency->abbr }}</span>
                                <span></span>
                            </div>
                            <div class="methods">
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    @if (count($wishs['products']) == 0)
                    <div class="orderHistory">
                        <p class="text-center">{{ trans('vars.Cabinet.wishListEmpty') }}</p>
                    </div>
                    @else
                    <div class="col">
                        <a href="{{ url('/'.$lang->lang.'/cart') }}"><button>{{ trans('vars.TehButtons.btnViewAllFavorites') }}</button></a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
@include('front.desktop.partials.footer')
@stop
