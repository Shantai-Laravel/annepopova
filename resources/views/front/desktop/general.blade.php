@extends('../front.desktop.app')
@section('content')

<div class="bigContainer">
  <div class="textBanner" style="background-image:url(images/deskbanner.jpg)">
    <img src="images/LogoAPJ.Paper-Texture new3dark..png" alt="" class="logoDesk">
    <h1>{{ trans('vars.General.brandName') }}</h1>
    <h4>{{ trans('vars.General.hpBrandSubtitle') }}</h4>
    <img src="images/Watercolor-Paper-Texture new.2ZnakDark.png" alt="" class="logoDesk">
    <!-- <p>{{ trans('vars.General.hpBrandQuote') }}</p> -->
  </div>
  <div class="startScreen">
    <div class="bijContainer">
      <img src="images/bijoux-desktop.jpg" alt="">
      <a href="{{ url('/'.$lang->lang.'/bijoux') }}">
          bijoux boutique
      </a>
    </div>
    <div class="bijContainer">
      <a href="{{ url('/'.$lang->lang.'/homewear') }}">
          homewear store
      </a>
      <img src="images/apl-desk.jpg" alt="">
    </div>
  </div>
</div>

<!-- <div class="container">
    <div class="row">
        <div class="col-md-6">
            <a href="{{ url('/'.$lang->lang.'/homewear') }}">
                <img src="/images/homewear-general.jpg" height="90%">
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ url('/'.$lang->lang.'/bijoux') }}">
                <img src="/images/bijoux-general.jpg" height="90%">
            </a>
        </div>
    </div>
</div> -->

@stop
