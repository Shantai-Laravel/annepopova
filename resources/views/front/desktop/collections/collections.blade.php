@extends('front.desktop.app')
@section('content')
@include('front.desktop.partials.header')

<main class="collectionContent">
  <div class="titlePage">
      <div class="container">
          <div class="row">
              <div class="col-12">
                  <h3>{{ $collection->translation->name }} {{ trans('vars.DetailsProductSet.collection') }}</h3>
              </div>
          </div>
      </div>
  </div>
    @if ($collection->sets()->count() > 0)
    @if (!is_null($mainSet))
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4>{{ $mainSet->translation->name }}</h4>
                </div>
                <div class="col-lg-7 col-md-6">
                    <div class="mainSlider">
                        <div class="sliderContainer slideHome">
                            <div class="navCollection">
                                @if ($mainSet->photos()->count() > 0)
                                    @foreach ($mainSet->photos as $key => $photo)
                                        <img src="/images/sets/sm/{{ $photo->src }}"/>
                                    @endforeach
                                @endif
                            </div>
                            <div class="oneCollectionSlider">
                                @if ($mainSet->photos()->count() > 0)
                                    @foreach ($mainSet->photos as $key => $photo)
                                        <div><img src="/images/sets/og/{{ $photo->src }}"/></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <set :set="{{ $mainSet }}" site="{{ $site }}"></set>
                </div>
            </div>
        </div>
    </section>
    @endif
    @foreach ($collection->sets as $key => $set)
    @if ($set->id != Request::get('order'))
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4>{{ $set->translation->name }}</h4>
                </div>
                <div class="col-lg-7 col-md-6">
                    <div class="mainSlider">
                        <div class="sliderContainer slideHome">
                            <div class="navCollection">
                                @if ($set->photos()->count() > 0)
                                    @foreach ($set->photos as $key => $photo)
                                        <img src="/images/sets/sm/{{ $photo->src }}"/>
                                    @endforeach
                                @endif
                            </div>
                            <div class="oneCollectionSlider">
                                @if ($set->photos()->count() > 0)
                                    @foreach ($set->photos as $key => $photo)
                                        <div><img src="/images/sets/og/{{ $photo->src }}"/></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <set :set="{{ $set }}" site="{{ $site }}"></set>
                </div>
            </div>
        </div>
    </section>
    @endif
    @endforeach
    @endif
</main>
@include('front.desktop.partials.footer')
@include('front.desktop.partials.modalsPage')
@stop
