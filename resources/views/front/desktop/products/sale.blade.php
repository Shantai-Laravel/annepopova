@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
<main class="outlet">
  <div class="titlePage">
      <div class="container">
          <div class="row">
              <div class="col-12">
                  <h3>{{ trans('vars.PagesNames.pageNameOutletTitle') }}</h3>
              </div>
          </div>
      </div>
  </div>
    <div class="container">
        <sale site="{{ $site }}"></sale>
    </div>
    <section class="additionalSlide">
        <div class="container">
            <view-recently site="{{ $site }}"></view-recently>
        </div>
    </section>
</main>
@include('front.desktop.partials.footer')
@stop

@section('microdataGoogle')

    <script type="application/ld+json">
    [
      @foreach ($products as $key => $product)
        @php
            $color = getProductColor($product->id, 2);
        @endphp
          {
            "@context": "http://schema.org/",
              "@type": "Product",
              "sku": "{{ $product->code }}",
              "gtin14" : "{{ $product->ean_code }}",
              "mpn": "{{ $product->ean_code }}",
              @if ($product->FBImage) {
                  "image": "/images/producs/fbq/{{ $product->FBImage->src }}",
              @endif
              "name": "{{ $product->translation->name }}",
              "description": "{{ $product->translation->atributes }}",
              "brand": {
                "@type": "Thing",
                "name": "Anne Popova"
              },
              "color" : "{{ $color ?? '' }}",
              "offers": {
                "@type": "Offer",
                "priceCurrency": "{{ $currency->abbr }}",
                "price": "{{ $product->personalPrice->price }}",
                "itemCondition": "http://schema.org/UsedCondition",
                "availability": "in_stock"
              }
         }
         @if ($key !== count($products) - 1)
            ,
         @endif
     @endforeach
    ]
</script>

@stop
