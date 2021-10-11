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
                            <p>
                                {{trans('front.ja.passResetText')}}
                            </p>
                        </div>
                    </div>
                    <form action="{{ url()->current() }}" method="post">
                        {{ csrf_field() }}
                        @if ($errors->has('invalidEmail'))
                        <div class="row">
                            <div class="col-12">
                                <div class="errorPassword">
                                    <p><strong> {{trans('front.ja.errorWas')}}</strong></p>
                                    <p>{!!$errors->first('invalidEmail')!!}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row justify-content-center align-items-center">
                            <div class="col-md-7">
                                <input type="text" name="code" placeholder="code">
                                @if ($errors->has('code'))
                                <div class="invalid-feedback" style="display: block">
                                    {!!$errors->first('code')!!}
                                </div>
                                @endif
                            </div>
                            <div class="col-md-5 col-sm-6 col-12">
                                <div class="btnGrey">
                                    <input type="submit" value="{{trans('front.ja.passwordReset')}}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('front.partials.footer')
@stop
