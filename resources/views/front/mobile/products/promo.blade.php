@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')
<main class="promo">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="undoStatic"></div>
            </div>
            <div class="col-12">
                @if ($site == 'homewear')
                    <h3 class="promoTitle text-center">Promo</h3>
                @else
                    <h3 class="text-center">Promo</h3>
                @endif
            </div>
        </div>
    </div>
    <div class="wrapper">
        @if ($promotions->count() > 0)
        @foreach ($promotions as $key => $promotion)
        @if ($promotion->img_mobile)
        <section class="collection">
            <div class="sliderCollectionHome">
                <a href="{{ url('/' . $lang->lang . '/' . $site .'/catalog/' . $product->category->alias. '/'. $product->alias) }}" class="item">
                    <img src="/images/promotions/{{ $promotion->img_mobile }}" alt="{{ $promotion->translation->name }}"/>
                </a>
                @foreach ($promotion->products as $key => $product)
                    <a  href="{{ url('/' . $lang->lang . '/' . $site .'/catalog/' . $product->category->alias. '/'. $product->alias) }}" class="item">
                        @if ($product->mainImage)
                            <img src="/images/products/md/{{ $product->mainImage->src }}"/>
                        @else
                            <img src="/images/no-image-ap.jpg">
                        @endif
                    </a>
                @endforeach
            </div>
        </section>
        @endif
        @endforeach
        @endif
    </div>
</main>
@include('front.mobile.partials.footer')
@stop
