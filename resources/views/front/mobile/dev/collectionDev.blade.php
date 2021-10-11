@extends('front.mobile.app')
@section('content')
@include('front.mobile.partials.header')

<main class="oneProductContent collectionContent oneProductContentNew">
    @php
        if (is_null($mainSet)) {
            $mainSet = json_encode('empty');
        }
    @endphp

    <collection-mobile-dev
                    :main_set="{{ $mainSet }}"
                    :other_sets="{{ $collection->sets }}"
                    :collection="{{ $collection }}"
                    :similars="{{ $similars }}"
                    site="{{ $site }}"
                    >
    </collection-mobile-dev>

</main>

@include('front.mobile.partials.footer')
@include('front.mobile.partials.modalsPage')

@stop

<style media="screen">
    .oneProductContent{
        height: auto !important;
    }
</style>
