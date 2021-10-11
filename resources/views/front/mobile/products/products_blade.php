@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')

@php
$images = [];
    if ($product->images()->get()){
    foreach ($product->images()->get() as $key => $photo){
        $images[] = $photo->src;
    }
}
if (isMobile()) {
    $device = 'sm';
}else{
    $device = 'og';
}
@endphp

<div id="sniper">
    {{-- <img src="/images/spinner.svg"> --}}
    <img src="/images/loading-logo.gif">
</div>
{{-- schema.org --}}
<div itemscope itemtype="http://schema.org/Product">
    <meta itemprop="brand" content="Anne Popova">
    <meta itemprop="name" content="{{ $product->translation->name }}">
    <meta itemprop="description" content="{{ $product->translation->body }}">
    <meta itemprop="productID" content="{{ $product->id }}">
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
        <meta itemprop="price" content="{{ $product->personalPrice->price }}">
        <meta itemprop="priceCurrency" content="{{ $currency->abbr }}">
    </div>
</div>

<main class="oneProductContent">
    <div class="fuuckSlider">
        <div class="productInner oneProduct">
            <div class="sliderOneProduct">
                @if ($images)
                @foreach ($images as $key => $photo)
                    <div class="sld">
                        <img data-src="/images/products/og/{{ $photo }}" src="/images/bg-white.jpg" class="lazy-load"/>
                    </div>
                @endforeach
                @endif
            </div>
            <sizes-mob :product="{{ $product }}" site="{{ $site }}"></sizes-mob>
            <div class="description">
                <add-to-cart-button-mob :product="{{ $product }}" site="{{ $site }}"></add-to-cart-button-mob>
                <div class="innerContainer">
                    <div class="descriptionInner">
                        <p class="name">{{ $product->translation->name }}</p>
                        <div class="price">
                            <span>{{ $product->mainPrice->price }} {{ $mainCurrency->abbr }}</span>
                            @if ($product->discount > 0)
                                <span>{{ $product->mainPrice->old_price }} {{ $mainCurrency->abbr }}</span>
                            @endif
                        </div>
                        {{-- @php
                            $color = getProductColor($product->id, 2);
                        @endphp --}}
                        {{-- @if ($color)
                            <div class="color">{{ trans('vars.DetailsProductSet.color') }}: {{ $color }}  <span class="swipeup"></span></div>
                        @endif --}}
                        <div class="moreDetails">
                            @if ($product->translation->body)
                            <div class="descriptionInner">
                                <div class="title">{{ trans('vars.DetailsProductSet.description') }}</div>
                                <div>
                                    {!! $product->translation->body !!}
                                </div>
                            </div>
                            @endif
                        </div>
                        <a href="#" class="box">{{ trans('vars.DetailsProductSet.shippingPaymentReturns') }}</a>
                        <div class="sliderContainer">
                            @if ($similarProducts)
                            <h3>{{ trans('vars.DetailsProductSet.discoverLook') }}</h3>
                            <div class="row sliderLook">
                                @foreach ($similarProducts as $key => $similarProduct)
                                <div class="item lookItem">
                                    <div class="oneProduct">
                                        <div class="addToWish"></div>
                                        <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'.$similarProduct->category->alias.'/'.$similarProduct->alias) }}" class="imgBlock">
                                        @if ($similarProduct->mainImage)
                                            <img src="/images/bg-white.jpg" class="lazy-load" data-src="/images/products/og/{{ $similarProduct->mainImage->src }}"/>
                                        @else
                                            <img src="/images/no-image-ap.jpg">
                                        @endif
                                        </a>
                                        <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'.$similarProduct->category->alias.'/'.$similarProduct->alias) }}">{{ $similarProduct->translation->name }}</a>
                                        <div class="price">
                                            <span>{{ $similarProduct->mainPrice->price }} {{ $mainCurrency->abbr }}</span>
                                            <span>{{ $similarProduct->mainPrice->old_price }} {{ $mainCurrency->abbr }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @if ($otherProducts)
        @foreach ($otherProducts as $key => $prod)
        <div class="productInner oneProduct">
            <div class="sliderOneProduct">
                @if ($prod->images)
                    @foreach ($prod->images as $key => $image)
                        @if ($image)
                            <div class="sld">
                                <img src="/images/bg-white.jpg" class="lazy-load" data-src="/images/products/og/{{ $image->src }}"/>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <sizes-mob :product="{{ $prod }}" site="{{ $site }}"></sizes-mob>
            <div class="description">
                <add-to-cart-button-mob :product="{{ $prod }}" site="{{ $site }}"></add-to-cart-button-mob>
                <div class="innerContainer">
                    <div class="descriptionInner">
                        <p class="name">{{ $prod->translation->name }}</p>
                        <div class="price">
                            <span>{{ $prod->mainPrice->price }} {{ $mainCurrency->abbr }}</span>
                            @if ($prod->discount > 0)
                                <span>{{ $prod->mainPrice->old_price }} {{ $mainCurrency->abbr }}</span>
                            @endif
                        </div>
                        {{-- @php
                            $color = getProductColor($prod->id, 2);
                        @endphp
                        @if ($color)
                            <div class="color">{{ trans('vars.DetailsProductSet.color') }}: {{ $color }}  <span class="swipeup"></span></div>
                        @endif --}}
                        <div class="moreDetails">
                            @if ($prod->translation->body)
                            <div class="descriptionInner">
                                <div class="title">{{ trans('vars.DetailsProductSet.description') }}</div>
                                <div>
                                    {!! $prod->translation->body !!}
                                </div>
                            </div>
                            @endif
                        </div>

                        @php
                            $compozition = getProductCompozition($product->id, 11);
                        @endphp
                        @if ($compozition !== null)
                            <div class="moreDetails">
                                <div class="descriptionInner">
                                    <div class="title">Compozition</div>
                                    <div>
                                        {{ $compozition }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <a href="#" class="box">{{ trans('vars.DetailsProductSet.shippingPaymentReturns') }}</a>
                        <div class="sliderContainer">
                            @if ($similarProducts)
                            <h3>{{ trans('vars.DetailsProductSet.discoverLook') }}</h3>
                            <div class="row sliderLook">
                                @foreach ($similarProducts as $key => $similarProduct)
                                <div class="item lookItem">
                                    <div class="oneProduct">
                                        <div class="addToWish"></div>
                                        <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'.$similarProduct->category->alias.'/'.$similarProduct->alias) }}" class="imgBlock">
                                        @if ($similarProduct->mainImage)
                                            <img src="/images/bg-white.jpg" class="lazy-load" data-src="/images/products/og/{{ $similarProduct->mainImage->src }}"/>
                                        @else
                                            <img src="/images/no-image-ap.jpg">
                                        @endif
                                        </a>
                                        <a href="{{ url('/'.$lang->lang.'/'.$site.'/catalog/'.$similarProduct->category->alias.'/'.$similarProduct->alias) }}">{{ $similarProduct->translation->name }}</a>
                                        <div class="price">
                                            <span>{{ $similarProduct->mainPrice->price }}</span>
                                            <span>{{ $similarProduct->mainPrice->old_price }} {{ $mainCurrency->abbr }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

</main>
@include('front.mobile.partials.modalsPage')
@stop

{{-- Sniper code --}}
<style>
    #sniper{
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: #FFF;
        z-index: 999;
    }
    #sniper img{
        width: 70px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
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
    <meta property="product:brand" content="Anne Popova">
    <meta property="product:availability" content="in stock">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format((float)$product->personalPrice->old_price, 2, '.', '') }}">
    @if ($product->discount)
        <meta property="product:sale_price:amount" content="{{ number_format((float)$product->personalPrice->price, 2, '.', '') }}">
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
                "price": "{{ $product->personalPrice->price }}",
                "itemCondition": "new",
                "availability": "in_stock"
            }
          ],
        }
    </script>
@stop
