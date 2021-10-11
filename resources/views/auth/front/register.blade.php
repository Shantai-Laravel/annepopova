@extends('front.app')
@section('content')
@include('front.partials.header', ['className' => 'oneHeader'])
<div class="registration">
    <div class="container">
        <div class="row">
            <div class="col-12 socialMobile">
              <div class="row justify-content-center">
                <div class="col-8 text-center">
                  <b>Conecteazate cu</b>
                </div>
                <div class="col-10 face face2 text-center">
                    <a href="{{ url($lang->lang.'/login/facebook') }}"><img src="{{asset('fronts/img/icons/facebook.png')}}" alt="">Conecteazate cu facebook</a>
                </div>
                <div class="col-10 face face2 text-center">
                    <a href="{{ url($lang->lang.'/login/google') }}"><img src="{{asset('fronts/img/icons/chrome.png')}}" alt="">Conecteazate cu chrome</a>
                </div>
                {{-- <div class="col-10 face face2 text-center">
                    <a href="{{ url($lang->lang.'/login/instagram') }}"><img src="{{asset('fronts/img/icons/insta.png')}}" alt="">Conecteazate cu instagram</a>
                </div> --}}
              </div>
            </div>
            <div class="col-12 pad">
                <h3 class="text-center">{{trans('front.ja.signUp')}}</h3>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 col-12 aboutEstel">
                <div class="row">
                    <div class="col-12 face face2">
                        <a href="{{ url($lang->lang.'/login/facebook') }}"><img src="{{asset('fronts/img/icons/facebook.png')}}" alt="">Conecteazate cu facebook</a>
                    </div>
                    <div class="col-12 face face2">
                        <a href="{{ url($lang->lang.'/login/google') }}"><img src="{{asset('fronts/img/icons/chrome.png')}}" alt="">Conecteazate cu chrome</a>
                    </div>
                    {{-- <div class="col-12 face face2">
                        <a href="{{ url($lang->lang.'/login/instagram') }}"><img src="{{asset('fronts/img/icons/insta.png')}}" alt="">Conecteazate cu instagram</a>
                    </div> --}}
                </div>
                <h4>{{trans('front.ja.about')}} Julia Alert</h4>
                <ul>
                    <li><a href="{{url($lang->lang.'/about')}}">{{trans('front.ja.aboutUs')}}</a></li>
                    <li><a href="{{url($lang->lang.'/condition')}}">{{trans('front.ja.conditions')}}</a></li>
                    <li><a href="{{url($lang->lang.'/cookie')}}">{{trans('front.ja.cookie')}}</a></li>
                    <li><a href="{{url($lang->lang.'/privacy')}}">{{trans('front.ja.privacy')}}</a></li>
                </ul>
            </div>
            <div class="col-lg-6 col-sm-8 col-12 regBoxBorder">
                <div class="regBox">
                    <div class="row">
                        <div class="col-12">
                            <h4>{{trans('front.ja.signUp')}}</h4>
                        </div>
                    </div>
                    <register />
              </div>
        </div>
    </div>
</div>
</div>
@include('front.partials.footer')
@stop
