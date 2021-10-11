@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')



<main class="oneProductContent">
    <products-mobile-slider-dev
                    :product="{{ $product }}"
                    :category_id="{{ $category->id }}"
                    :other_products="{{ $otherProducts }}"
                    site="{{ $site }}"
                    >
    </products-mobile-slider-dev>
</main>


@include('front.mobile.partials.modalsPage')
@stop
