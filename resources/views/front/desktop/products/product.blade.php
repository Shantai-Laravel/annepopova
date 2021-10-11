@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
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

{{-- schema.org --}}
<div itemscope itemtype="http://schema.org/Product">
    <meta itemprop="brand" content="Anne Popova">
    <meta itemprop="name" content="{{ $product->translation->name }}">
    <meta itemprop="description" content="{{ $product->translation->body }}">
    <meta itemprop="productID" content="{{ $product->code }}">
    <meta itemprop="url" content="{{ url('/' . $lang->lang . '/' . $site . '/catalog/' . $product->category->alias .'/'. $product->alias) }}">
    @if ($product->imagesFB()->get())
        @foreach ($product->imagesFB()->get() as $key => $photo)
            <meta itemprop="image" content="https://annepopova.com/images/producs/fbq/{{ $photo->src }}">
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
        <meta itemprop="priceCurrency" content="{{ $mainCurrency->abbr }}">
    </div>
</div>


<main class="oneProductContent">
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12"><span class="undo"> <a href="{{ url()->previous() }}"> go back</a></span></div>
                <div class="col-8">
                    <div class="slideContainer">
                        <div class="mainSlider">
                            <div class="sliderContainer slideHome">
                                <div class="navCollection">

                                    @if ($images)
                                        @foreach ($images as $key => $photo)
                                            <img src="/images/products/sm/{{ $photo }}"/>
                                        @endforeach
                                    @endif
                                    @if ($product->video)
                                        <img src="/images/products/sm/{{ $product->mainImage ? $product->mainImage->src : ""}}" alt="product" />
                                    @endif
                                </div>
                                <div class="oneCollectionSlider slick-after-load">
                                    @if ($images)
                                        @foreach ($images as $key => $photo)
                                            <div class="mainImg">
                                                <img src="/images/products/og/{{ $photo }}" data-toggle="modal" data-target="#zoomModal"/>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if ($product->video)
                                    <div class="mainImg" id="video-block">
                                        <div class="videoContainer">
                                            <div class="soundButton" id="sound"></div>
                                            <video id="myVideo" autoplay loop muted  data-toggle="modal" data-target="#zoomModal">
                                                <source src="/videos/{{ $product->video }}" type="video/mp4" />
                                                Your browser does not support HTML5 video.
                                            </video>
                                        </div>
                                        @if ($product->mainImage)
                                            <img src="/images/products/sm/{{ $product->mainImage ? $product->mainImage->src : "" }}"/>
                                        @else
                                            <img src="/images/no-image-ap.jpg">
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4" style="position: relative;">
                    <aside style="position: sticky;top:200px">
                        <div class="name">{{ $product->translation->name }}</div>
                        <div class="price">
                            <span>{{ $product->personalPrice->price }} {{ $currency->abbr }}</span>
                            @if ($product->discount > 0)
                                <span>{{ $product->personalPrice->old_price }} {{ $currency->abbr }}</span>
                            @endif
                        </div>
                        @php
                            $color = getProductColor($product->id, 2);
                        @endphp
                        @if ($color)
                            <div class="color">{{ trans('vars.DetailsProductSet.color') }}: {{ $color }}</div>
                        @endif
                        <add-to-cart-button :product="{{ $product }}" site="{{ $site }}"></add-to-cart-button>
                        <div class="moreDetails">
                            @if ($product->translation->body)
                                <div class="description">
                                    <div class="title">{{ trans('vars.DetailsProductSet.description') }}</div>
                                    <div>
                                        {!! $product->translation->body !!}
                                    </div>
                                </div>
                            @endif
                        </div>

                        @php
                            $compozition = getProductCompozition($product->id, 11);
                        @endphp

                        @if ($compozition !== null)
                            <div class="moreDetails">
                                <div class="description">
                                    <div class="title">Compozition</div>
                                    <div>
                                        {{ $compozition }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <a href="#" data-toggle="modal" data-target="#modalShipping">{{ trans('vars.DetailsProductSet.shippingPaymentReturns') }}</a>
                    </aside>
                </div>
            </div>
        </div>
    </section>
    <section class="additionalSlide">
        <div class="container">

            @if ($similarProducts)
            <div class="row">
                <div class="col-12">
                    <div class="title">
                        {{ trans('vars.DetailsProductSet.similarProducts') }}
                    </div>
                </div>
                <div class="col-12" style="padding:0">
                    <div style="display:block">
                        <div class="additional">
                            @foreach ($similarProducts as $key => $prod)
                              <div class="oneProduct">
                                <a href="{{ url('/'.$lang->lang.'/'.$prod->type.'/catalog/'.$prod->category->alias.'/'.$prod->alias) }}" class="imgBlock">
                                    @if ($prod->mainImage)
                                        <img src="/images/products/sm/{{ $prod->mainImage->src }}"/>
                                    @else
                                        <img src="/images/no-image-ap.jpg">
                                    @endif
                                </a>
                                <a href="{{ url('/'.$lang->lang.'/'.$prod->type.'/catalog/'.$prod->category->alias.'/'.$prod->alias) }}">{{ $prod->translation->name }}</a>
                                <div class="price">
                                  <span>{{ $prod->mainPrice->price }} {{ $currency->abbr }}</span>
                                  <span></span>
                                </div>
                              </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <section class="additionalSlide">
        <div class="container">
            <view-recently site="{{ $site }}"></view-recently>
        </div>
    </section>
</main>
<div class="modal" id="zoomModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modalContainer">
                <div class="closeZoom" data-dismiss="modal">
                    <svg width="16px" height="16px" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Prod_One_375---Swipe" transform="translate(-19.000000, -118.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <path d="M27.6226706,126.612626 L37.3985243,126.612631 C37.7323875,126.606822 38,126.334254 38,126.000017 C38,125.665781 37.732388,125.393213 37.3985247,125.387405 L27.6226711,125.3874 L27.6227094,115.602057 C27.6169068,115.267871 27.3446029,115 27.0106892,115 C26.6767755,115 26.4044715,115.267872 26.3986685,115.602058 L26.3986302,125.3874 L16.6227505,125.387369 C16.4015614,125.383521 16.1955089,125.499436 16.0837896,125.690561 C15.9720702,125.881686 15.9720701,126.118279 16.0837893,126.309404 C16.1955085,126.500529 16.4015609,126.616443 16.62275,126.612595 L26.3986297,126.612626 L26.3986173,136.397943 C26.4044199,136.732129 26.6767238,137 27.0106375,137 C27.3445512,137 27.6168552,136.732128 27.6226582,136.397942 L27.6226706,126.612626 Z" id="Shape-Copy-3" transform="translate(27.000000, 126.000000) rotate(-315.000000) translate(-27.000000, -126.000000) "></path>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class="zoomNav">
                    @if ($images)
                        @foreach ($images as $key => $photo)
                            <img src="/images/products/sm/{{ $photo }}"/>
                        @endforeach
                    @endif
                    @if ($product->video)
                        <img src="/images/products/sm/{{ $product->mainImage ? $product->mainImage->src : ""}}" alt="product" />
                    @endif
                </div>
                <div class="zoomSlider">
                  @if ($images)
                      @foreach ($images as $key => $photo)
                          <div class="mainImg"><img src="/images/products/og/{{ $photo }}" data-toggle="modal" data-target="#zoomModal"/></div>
                      @endforeach
                  @endif
                  @if ($product->video)
                  <div class="mainImg">
                      <div class="videoContainer">
                          <div class="soundButton" id="sound"></div>
                          <video id="myVideo" autoplay loop muted  data-toggle="modal" data-target="#zoomModal">
                              <source src="/videos/{{ $product->video }}" type="video/mp4" />
                              Your browser does not support HTML5 video.
                          </video>
                      </div>
                      @if ($product->mainImage)
                          <img src="/images/products/sm/{{ $product->mainImage ? $product->mainImage->src : "" }}"/>
                      @else
                          <img src="/images/no-image-ap.jpg">
                      @endif
                  </div>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('front.desktop.partials.footer')
@include('front.desktop.partials.modalsPage')
@stop


{{-- Open Graph --}}
@section('microdataFacebook')
    <meta property="og:site_name" content="Anne Popova" />
    <meta property="og:title" content="{{ $product->translation->name }}">
    <meta property="og:description" content="{{ $product->translation->body }}">
    <meta property="og:url" content="{{ url('/' . $lang->lang . '/' . $site . '/catalog/' . $product->category->alias .'/'. $product->alias) }}">
    <meta property="og:product:catalog_id" content="{{ $product->code }}">
    <meta property="og:type" content="product" />
    <meta property="og:size" content="S-M-L-XL">
    <meta property="og:age_group" content="18 - 65">

    @if ($product->imagesFB()->get())
        @foreach ($product->imagesFB()->get() as $key => $photo)
            <meta property="og:image" content="https://annepopova.com/images/products/fbq/{{ $photo->src }}">
        @endforeach
    @endif

    <meta property="og:price:amount" content="{{ number_format((float)$product->personalPrice->old_price, 2, '.', '') }}">
    <meta property="og:price:currency" content="{{ @$currency->abbr }}">
    <meta property="og:video" content="https://annepopova.com/videos/{{ $product->video }}">

    <meta property="product:category" content="Apparel & Accessories > Clothing > {{ $product->category->translation->seo_text ?? $product->category->translation->name }}">
    <meta property="product:google_product_category" content="{{ $product->category->number }}">
    <meta property="product:brand" content="Anne Popova">
    <meta property="product:availability" content="in stock">
    <meta property="product:condition" content="new">
    <meta property="product:gender" content="female">
    <meta property="product:gtin" content="{{ $product->ean_code }}">
    <meta property="product:price:amount" content="{{ number_format((float)$product->mainPrice->old_price, 2, '.', '') }}">

    @if ($product->discount)
        <meta property="product:sale_price:amount" content="{{ number_format((float)$product->mainPrice->price, 2, '.', '') }}">
        <meta property="product:sale_price:currency" content="{{ @$mainCurrency->abbr }}">
    @endif

    <meta property="product:price:currency" content="{{ @$mainCurrency->abbr }}">
    <meta property="product:retailer_item_id" content="{{ $product->code }}">

    @if ($lang->lang == 'ro')
        <meta property="og:locale" content="ro_{{ $country->iso }}">
        <meta property="product:locale" content="ro_{{ $country->iso }}">
    @elseif ($lang->lang == 'en')
        <meta property="og:locale" content="en_{{ $country->iso }}">
        <meta property="product:locale" content="en_{{ $country->iso }}">
    @elseif ($lang->lang == 'ru')
        <meta property="og:locale" content="ru_{{ $country->iso }}">
        <meta property="product:locale" content="ru_{{ $country->iso }}">
    @elseif ($lang->lang == 'fr')
        <meta property="og:locale" content="fr_{{ $country->iso }}">
        <meta property="product:locale" content="fr_{{ $country->iso }}">
    @elseif ($lang->lang == 'nl')
        <meta property="og:locale" content="nl_{{ $country->iso }}">
        <meta property="product:locale" content="nl_{{ $country->iso }}">
    @endif
@stop


{{-- LD+JSON --}}
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
            "image": "https://annepopova.com/images/producs/fbq/{{ $photo->src }}",
          @endforeach
      @endif
      "brand":"Anne Popova",
      "offers": [
        {
            "@type": "Offer",
            "priceCurrency": "{{ $mainCurrency->abbr }}",
            "price": "{{ $product->mainPrice->price }}",
            "itemCondition": "new",
            "availability": "in_stock"
        }
      ],
  };
</script>

<style media="screen">
    .mainImg{
        opacity: 0;
    }
    /* .slick-after-load .videoContainer{ opacity: 0 }; */
    /* .slick-initialized .videoContainer{ opacity: 1 !important; }; */
</style>
@stop

@section('scripts')

<script>
    setTimeout(function(){$('.mainImg').css('opacity', 1);}, 1500);
</script>

@stop
