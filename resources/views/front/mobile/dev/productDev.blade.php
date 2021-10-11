@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')
{{-- schema.org --}}
<div itemscope itemtype="http://schema.org/Product">
    <meta itemprop="brand" content="Anne Popova">
    <meta itemprop="name" content="{{ $product->translation->name }}">
    <meta itemprop="description" content="{{ $product->translation->body }}">
    <meta itemprop="productID" content="{{ $product->code }}">
    <meta itemprop="url" content="{{ url('/' . $lang->lang . '/' . $site . '/catalog/' . $product->category->alias .'/'. $product->alias) }}">
    @if ($product->imagesFB()->get())
        @foreach ($product->imagesFB()->get() as $key => $photo)
            <meta itemprop="image" content="/images/producs/fbq/{{ $photo->src }}">
        @endforeach
    @endif
    <div itemprop="value" itemscope itemtype="http://schema.org/PropertyValue">
        <span itemprop="name">item_group_id</span>
        <meta itemprop="value" content="True">fb_tshirts</meta>
    </div>
    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <link itemprop="availability" href="http://schema.org/InStock">
        <link itemprop="itemCondition" href="http://schema.org/NewCondition">
        <meta itemprop="price" content="{{ $product->mainPrice->price }}">
        <meta itemprop="priceCurrency" content="{{ $currency->abbr }}">
    </div>
</div>


<main class="oneProductContent oneProduct2ContentNew">
    <product-mobile-dev
                    :product="{{ $product }}"
                    :category_id="{{ $category->id }}"
                    :other_products="{{ $otherProducts }}"
                    :similars="{{ $similarProducts }}"
                    site="{{ $site }}"
                    >
    </product-mobile-dev>
</main>

@include('front.mobile.partials.modalsPage')
@include('front.mobile.partials.footer')

@stop
<style media="screen">
    .oneProductContent{
        height: auto !important;
    }
    .oneProductContent .productInner.oneProduct{
        height: auto !important;
    }
</style>


@section('microdataFacebook')
    @php
        $additionaImages = '';
    @endphp
    <meta property="og:site_name" content="Anne Popova" />
    <meta property="og:title" content="{{ $product->translation->name }}">
    <meta property="og:description" content="{{ $product->translation->body }}">
    <meta property="og:url" content="{{ url('/' . $lang->lang . '/' . $site . '/catalog/' . $product->category->alias .'/'. $product->alias) }}">
    <meta property="og:type" content="product" />
    <meta property="og:size" content="S-M-L-XL">
    <meta property="og:age_group" content="18 - 65">

    @if ($product->imagesFB()->get())
        @foreach ($product->imagesFB()->get() as $key => $photo)
            <meta property="og:image" content="https://annepopova.com/images/products/fbq/{{ $photo->src }}">
        @endforeach
    @endif

    <meta property="og:video" content="https://annepopova.com/videos/{{ $product->video }}">
    <meta property="product:category" content="Apparel & Accessories > Clothing > {{ $product->category->translation->seo_text ?? $product->category->translation->name }}">
    <meta property="product:google_product_category" content="{{ $product->category->number }}">
    <meta property="product:brand" content="Anne Popova">
    <meta property="product:availability" content="in stock">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format((float)$product->mainPrice->old_price, 2, '.', '') }}">
    @if ($product->discount)
        <meta property="product:sale_price:amount" content="{{ number_format((float)$product->mainPrice->price, 2, '.', '') }}">
    @endif
    <meta property="product:price:currency" content="{{ @$mainCurrency->abbr }}">
    <meta property="product:retailer_item_id" content="{{ $product->code }}">
    @if ($lang->lang == 'ro')
        <meta property="og:locale" content="ro_{{ $country->iso }}">
    @elseif ($lang->lang == 'en')
        <meta property="og:locale" content="en_{{ $country->iso }}">
    @elseif ($lang->lang == 'ru')
        <meta property="og:locale" content="ru_{{ $country->iso }}">
    @elseif ($lang->lang == 'fr')
        <meta property="og:locale" content="fr_{{ $country->iso }}">
    @elseif ($lang->lang == 'nl')
        <meta property="og:locale" content="nl_{{ $country->iso }}">
    @endif
@stop

@section('microdataGoogle')
    @section('microdataGoogle')
        <script type="application/ld+json">
        {
          "@context":"https://schema.org",
          "@type":"Product",
          "productID":"{{ $product->code }}",
          "name":"{{ $product->translation->name }}",
          "description":"{{ $product->translation->body }}",
          "url":"{{ url('/' . $lang->lang . '/' . $site . '/catalog/' . $product->category->alias .'/'. $product->alias) }}",
          @if ($product->imagesFB()->get())
              @foreach ($product->imagesFB()->get() as $key => $photo)
                "image": "/images/producs/fbq/{{ $photo->src }}",
              @endforeach
          @endif
          "brand":"Anne Popova",
          "offers": [
            {
                "@type": "Offer",
                "priceCurrency": "{{ $currency->abbr }}",
                "price": "{{ $product->mainPrice->price }}",
                "itemCondition": "new",
                "availability": "in_stock"
            }
          ],
        }
    </script>
@stop
