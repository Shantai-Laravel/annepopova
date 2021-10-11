@extends('front.app')
@section('content')
@include('front.partials.header', ['className' => 'oneHeader'])
<div class="registration fullHeight paddTop">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3>{{trans('front.ja.passReset')}}</h3>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-8 col-12 aboutEstel">
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
                            <h4>{{trans('front.ja.passReset')}}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {{trans('front.ja.passResetText')}}
                        </div>
                    </div>
                    <forgot-password />
                </div>
            </div>
        </div>
    </div>
</div>
@include('front.partials.footer')
@stop
