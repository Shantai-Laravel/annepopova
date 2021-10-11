@extends('admin.app')

<!-- @section('nav-bar')
	<ul>
		<li class="language-list">
			@foreach($lang_list as $one_lang)
				@if(!empty(Request::segment(6)))
					<a href="{{urlForLanguage($one_lang->lang, Request::segment(5).'/'. Request::segment(6))}}">{{$one_lang->lang}}</a>
				@elseif(!empty(Request::segment(5)))
					<a href="{{urlForLanguage($one_lang->lang, Request::segment(5))}}">{{$one_lang->lang}}</a>
				@elseif(!empty(Request::segment(4)))
					<a href="{{urlForLanguage($one_lang->lang, '')}}">{{$one_lang->lang}}</a>
				@elseif(!empty(Request::segment(3)))
					<a href="{{urlForFunctionLanguage($one_lang->lang, '')}}">{{$one_lang->lang}}</a>
				@else
					<a href="{{url($one_lang->lang, 'back')}}">{{$one_lang->lang}}</a>
				@endif
			@endforeach
		</li>
		<li><a class="tosite-button" target="_blank" href="/">{{trans('variables.go_to_the_site')}}</a></li>
	</ul>
@stop -->

@section('left-menu')
	<aside id="nav" style="z-index: 99;">

		<div class="logo-section-auth">
			<a><img src="/images/logo.png" height="36" width="138" alt="">{{trans('variables.title_page')}}</a>
		</div>

		<div class="navigation">
			<form class="login-form" role="form" method="POST" action="{{ url($lang.'/back/auth/register') }}" id="register-form">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="text" name="name" placeholder="name">
				<input type="text" name="login" placeholder="login">
				<input type="email" name="email" placeholder="email">
				<input type="password" name="password" placeholder="pass">
				<input type="password" name="repeat_password" placeholder="repeat pass">
				{{-- <script src="https://www.google.com/recaptcha/api.js?render=reCAPTCHA_site_key"></script>
				 <script>
				 grecaptcha.ready(function() {
					 grecaptcha.execute('reCAPTCHA_site_key', {action: 'homepage'}).then(function(token) {
						...
					 });
				 });
				 </script> --}}
				<input type="submit" class="submit-label" onclick="saveForm(this)" data-form-id="register-form" value="{{trans('variables.register')}}">
			</form>
		</div>
	</aside>
@stop


@section('footer')
	<footer>
		@include('admin.footer')
	</footer>
@stop
