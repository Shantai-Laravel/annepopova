@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')
<main class="clientArea">
    <div class="container">
        <h3>{{ trans('vars.Cabinet.yourFavorites') }}</h3>
        <div class="row">
            <div class="col-12">
                <a href="{{ url()->previous() }}"><div class="undoStatic"></div></a>
            </div>
            <div class="col-12">
                <div class="user">
                    <p>{{ trans('vars.General.hello') }}, {{ $userdata->name }}</p>
                    <p>{{ trans('vars.Cabinet.welcomeTo') }} {{ trans('vars.Cabinet.yourFavorites') }}</p>
                </div>
            </div>
            <div class="col-12">
                <div class="navArea" id="navArea">
                    <div id="pageSelected">
                        {{ trans('vars.Cabinet.yourFavorites') }}
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
            <div class="wishItems col-12">
                <div class="row justify-content-center">
                    @if (count($wishs['products']) > 0)
                    @foreach ($wishs['products'] as $key => $wishProduct)
                    <div class="col-sm-10 col-12">
                        <div class="item">
                            <a href="{{ url('/' . $lang->lang . '/catalog/'. $wishProduct->product->category->alias . '/' . $wishProduct->product->alias) }}">
                                @if ($wishProduct->product->mainImage)
                                <img src="/images/products/sm/{{ $wishProduct->product->mainImage->src }}" alt="{{ $wishProduct->product->translation->name }}" />
                                @else
                                <img src="/fronts/img/jrw.jpg" alt="" />
                                @endif
                            </a>
                            <div class="descr">
                                <a href="{{ url('/' . $lang->lang . '/catalog/'. $wishProduct->product->category->alias . '/' . $wishProduct->product->alias) }}" class="nameProduct">{{ $wishProduct->product->translation->name }}</a>
                                <div class="price">
                                    <span>{{ $wishProduct->product->mainPrice->price }} {{ $mainCurrency->abbr }}</span>
                                    <span></span>
                                </div>
                                <div class="sizeContainer"></div>
                                <div class="buttonsSet"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                @if (count($wishs['products']) == 0)
                <div class="orderHistory">
                    <p class="text-center">{{ trans('vars.Cabinet.wishListEmpty') }}</p>
                </div>
                @else
                <div class="col">
                    <a href="{{ url('/'.$lang->lang.'/wish') }}"><button>{{ trans('vars.TehButtons.btnViewAllFavorites') }}</button></a>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@include('front.mobile.partials.footer')
@stop
