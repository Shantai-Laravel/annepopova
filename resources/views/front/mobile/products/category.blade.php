@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')
<main class="categoryContent">
    <div class="container">
        <h3>{{ $category->translation->name }}</h3>
        <div class="filterContainer">
            <div id="filterButton"><span>{{ trans('vars.DetailsProductSet.filter') }}</span><span></span></div>
            <parameters-filter :category="{{ $category }}" site="{{ $site }}"></parameters-filter>
        </div>
        <category-mob :category="{{ $category }}" :product="0" site="{{ $site }}"></category-mob>
    </div>
</main>
@include('front.mobile.partials.footer')
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
