@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')
<main>
    <div class="productsPageContent sale">
        <div class="titleFixed">
            <span>{{ trans('vars.PagesNames.pageNameNewTitle') }}</span>
        </div>
        <div class="productsPageHeader">
            <div class="headerText text-center">
                <div><span>{{ trans('vars.PagesNames.pageNameNewTitle') }}</span></div>
            </div>
        </div>
        <new :wish="{{ json_encode($wishListIds) }}"></new>
        <view-recently><view-recently/>
    </div>
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
