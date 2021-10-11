@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')

<main class="oneProductContent collectionContent oneProductContentNew">

    @php
        if (is_null($mainSet)) {
            $mainSet = json_encode('empty');
        }
    @endphp


    @if (Request::get('view') == 'dev')
        <collections-mobile-slider-dev
                        :main_set="{{ $mainSet }}"
                        :other_sets="{{ $otherSets }}"
                        site="{{ $site }}"
                        >
        </collections-mobile-slider-dev>
    @else
        {{-- <collections-mobile-slider
                        :main_set="{{ $mainSet }}"
                        :other_sets="{{ $collection->sets }}"
                        site="{{ $site }}"
                        >
        </collections-mobile-slider> --}}

        @php
            if ($otherSets == 'all') {
                $otherSets = $collection->sets;
            }
        @endphp

        <collection-mobile-dev
                        :main_set="{{ $mainSet }}"
                        :other_sets="{{ $otherSets }}"
                        :collection="{{ $collection }}"
                        :similars="{{ $similars }}"
                        site="{{ $site }}"
                        >
        </collection-mobile-dev>
    @endif


    {{-- <div class="fuuckSlider">
        @if ($collection->sets()->count() > 0)
        @if (!is_null($mainSet))
            <div class="productInner oneProduct">
                <div class="swipeup"></div>
                <div class="sliderOneProduct">
                    @if ($mainSet->photos()->count() > 0)
                        @foreach ($mainSet->photos as $key => $photo)
                            <div class="sld">
                                <img src="/images/sets/og/{{ $photo->src }}"/>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="description">
                    <div class="closeInner2">
                        <div class="closeInner"></div>
                    </div>
                    <p class="name collectionName">{{ $mainSet->translation->name }}</p>
                    <div class="countSet">
                        {{ trans('vars.DetailsProductSet.have') }} {{ $mainSet->products->count() }} {{ trans('vars.DetailsProductSet.productsFrom') }} {{ trans('vars.DetailsProductSet.set') }}
                    </div>
                    <div class="innerContainer collectionInner">
                        <div class="descriptionInner">
                            <set-mob :set="{{ $mainSet }}" site="{{ $site }}"></set-mob>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @endif

        @foreach ($collection->sets as $key => $set)
            @if ($set->id != Request::get('order'))
                <div class="productInner oneProduct">
                    <div class="swipeup"></div>
                    <div class="sliderOneProduct">
                        @if ($set->photos()->count() > 0)
                            @foreach ($set->photos as $key => $photo)
                                <div class="sld">
                                    <img src="/images/sets/og/{{ $photo->src }}"/>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="description">
                        <div class="closeInner2">
                            <div class="closeInner"></div>
                        </div>
                        <p class="name collectionName">{{ $set->translation->name }}</p>
                        <div class="countSet">
                            {{ trans('vars.DetailsProductSet.have') }} {{ $set->products->count() }} {{ trans('vars.DetailsProductSet.productsFrom') }} {{ trans('vars.DetailsProductSet.set') }}
                        </div>
                        <div class="innerContainer collectionInner">
                            <div class="descriptionInner">
                                <set-mob :set="{{ $set }}" site="{{ $site }}"></set-mob>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div> --}}

</main>
@include('front.mobile.partials.footer')

@include('front.mobile.partials.modalsPage')
@stop

<style media="screen">
    .oneProductContent{
        height: auto !important;
    }
    /* header #wish span, header #cart span{
        top: -10px !important;
    } */
</style>
