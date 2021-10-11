@extends('../front.mobile.app')
@section('content')

<div class="startScreen">
  <div class="bijContainer">
    <img src="images/bijoux-mobile.jpg" alt="">
    <a href="{{ url('/'.$lang->lang.'/bijoux') }}">
        bijoux boutique
    </a>
  </div>
  <div class="bijContainer">
    <a href="{{ url('/'.$lang->lang.'/homewear') }}">
        homewear store
    </a>
    <img src="images/apl-mobile.jpg" alt="">
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
