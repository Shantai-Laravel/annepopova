@extends('front.app')
@section('content')
@include('front.partials.header', ['className' => 'oneHeader'])
<div class="registration">
    <div class="container">
        <div class="row justify-content">
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
                            <h4><strong>{{trans('front.ja.signIn')}}</strong></h4>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-12">
                            {{trans('front.ja.dontHaveAccount')}} <a href="{{url($lang->lang.'/registration')}}"> {{trans('front.ja.signUp')}}</a>
                        </div>
                    </div>
                    <form action="{{ url()->current() }}" method="post">
                        {{ csrf_field() }}
                        @if (Session::has('success'))
                        <div class="row">
                            <div class="col-12">
                                <div class="errorPassword">
                                    <p>{{ Session::get('success') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="pwd">{{trans('front.register.pass')}}<b>*</b></label>
                            <input type="password" class="form-control" name="password" id="pwd" >
                            @if ($errors->has('password'))
                            <div class="invalid-feedback" style="display: block">
                                {!!$errors->first('password')!!}
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="confpwd">{{trans('front.register.repeatPass')}}<b>*</b></label>
                            <input type="password" class="form-control" name="passwordRepeat" id="confpwd" >
                            @if ($errors->has('passwordRepeat'))
                            <div class="invalid-feedback" style="display: block">
                                {!!$errors->first('passwordRepeat')!!}
                            </div>
                            @endif
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4 col-sm-5 col-10">
                                <div class="btnGrey">
                                    <input type="submit" value="Recupereaza parola">
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
