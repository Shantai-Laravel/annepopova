
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}">

    <title>{{trans('variables.title_page')}}</title>
    <meta name="description" content="Admin Panel">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,600">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css">

    <link rel="stylesheet" href="{{ asset('admin/css/normalize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/app-green.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/datepicker.css') }}">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script src="{{asset('admin/js/datepicker.js')}}"></script>
    <script src="{{asset('admin/js/jquery-ui.js')}}"></script>
    <script src="{{asset('admin/js/toastr.js')}}"></script>
    <script src="{{asset('admin/js/jquery.tablednd_0_5.js')}}"></script>
    <script src="{{asset('admin/js/custom.js')}}"></script>
    <script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('admin/js/jquery.mjs.nestedSortable.js')}}"></script>
    <script src="{{asset('admin/js/jquery.nestable.js')}}"></script>
    <script src="{{asset('admin/js/validation.js')}}"></script>

</head>
    </head>

    <body>
        <div class="auth">
            <div class="auth-container">
                <div class="card">
                    <header class="auth-header">
                        <h1 class="auth-title">
                            <div class="logo"> <span class="l l1"></span> <span class="l l2"></span> <span class="l l3"></span> <span class="l l4"></span> <span class="l l5"></span> </div> Like Media Admin </h1>
                    </header>
                    <div class="auth-content">
                        <p class="text-xs-center">{{ trans('variables.login') }}</p>

                     	<form class="login-form" role="form" method="POST" action="{{ url('/auth/login') }}" id="login-form">
							    {{-- {{ csrf_field() }}                  --}}
                                <div class="form-group">
                                	<label for="username">{{ trans('variables.login_text') }}</label>
                                	<input type="text" class="form-control underlined" name="login" id="username" placeholder="{{ trans('variables.your_login') }}" required>
                                </div>

                                <div class="form-group">
                                	<label for="password">{{ trans('variables.password_text') }}</label>
                                	<input type="password" class="form-control underlined" name="password" id="password" placeholder="{{ trans('variables.your_password') }}" required>
                                </div>

                            <input type="submit" class="btn btn-block btn-primary" onclick="saveForm(this)" data-form-id="login-form" value="{{ trans('variables.sing_in') }}"/>


                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        </form>

                    </div>
                </div>
                <div class="text-xs-center">
                    <a href="{{ url('/') }}" class="btn btn-secondary rounded btn-sm"> <i class="fa fa-arrow-left"></i> {{ trans('variables.go_to_the_site') }} </a>
                </div>
            </div>
        </div>
        <!-- Reference block for JS -->
        <div class="ref" id="ref">
            <div class="color-primary"></div>
            <div class="chart">
                <div class="color-primary"></div>
                <div class="color-secondary"></div>
            </div>
        </div>

{{--        <!-- <script src="{{ asset('admin/js/vendor.js') }}"></script> -->--}}
        {{--<!-- <script src="{{ asset('admin/js/app.js') }}"></script> -->--}}
    </body>

</html>


@section('footer')
	<footer>
		@include('admin.footer')
	</footer>
@stop
