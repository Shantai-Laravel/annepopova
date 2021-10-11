@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
<main class="promo">
    <div class="titlePage">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>{{ trans('vars.PagesNames.pagePromotions') }}</h3>
                </div>
            </div>
        </div>
    </div>
    @if ($promotions->count() > 0)
    @foreach ($promotions as $key => $promotion)
    <section>
        @if ($promotion->img)
            <img src="/images/promotions/{{ $promotion->img }}" alt="{{ $promotion->translation->name }}">
        @endif
        <div class="container">
            <div class="row promoInner">
                <div class="col-12">
                    <div class="promoTitle">
                        {{ trans('vars.DetailsProductSet.productsFrom') }} {{ $promotion->translation->name }}
                    </div>
                </div>
                @if ($promotion->products->count() > 0)
                <div class="container">
                    @if ($promotion->products)
                    <div class="row">
                        <div class="col-12" style="padding:0">
                            <div style="display:block">
                                <div class="additional">
                                    @foreach ($promotion->products as $key => $product)
                                    @if ($product->{$site} == 1)
                                    <div class="col-xl-12">
                                        <div class="oneProduct">
                                            <div class="addToWish"></div>
                                            <a href="{{ url('/' . $lang->lang . '/' . $product->type .'/catalog/' . $product->category->alias. '/'. $product->alias) }}" class="imgBlock">
                                                @if ($product->mainImage)
                                                    <img src="/images/products/md/{{ $product->mainImage->src }}"/>
                                                @else
                                                    <img src="/images/no-image-ap.jpg"/>
                                                @endif
                                            </a>
                                            <a href="{{ url('/' . $lang->lang . '/' . $product->type .'/catalog/' . $product->category->alias. '/'. $product->alias) }}">{{ $product->translation->name }}</a>
                                            <div class="price">
                                                <span>
                                                    {{ $product->personalPrice->price }}
                                                    @if ($product->personalPrice->old_price == $product->personalPrice->price)
                                                        {{ $currency->abbr }}
                                                    @endif
                                                </span>
                                                @if ($product->personalPrice->old_price !== $product->personalPrice->price)
                                                    <span>{{ $product->personalPrice->old_price }} {{ $currency->abbr }}</span>
                                                @else
                                                    <span></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </section>
    @endforeach
    @endif
</main>
@include('front.desktop.partials.footer')
@stop
