<!DOCTYPE html>
<html class="no-js" lang="en">

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
    <link rel="stylesheet" href="{{ asset('admin/css/style.css?'.uniqid() ) }}">
    <link rel="stylesheet" href="{{ asset('admin/css/vendor.css?'.uniqid()) }}">
    <link rel="stylesheet" href="{{ asset('admin/css/app-green.css?'.uniqid()) }}">
    <link rel="stylesheet" href="{{ asset('admin/css/datepicker.css?'.uniqid()) }}">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{asset('admin/js/jquery-ui.js')}}"></script>
    <script src="{{asset('admin/js/toastr.js')}}"></script>
    <script src="{{asset('admin/js/jquery.tablednd_0_5.js')}}"></script>
    <script src="{{asset('admin/js/custom.js?'.uniqid())}}"></script>
    <script src="{{asset('admin/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('admin/js/jquery.mjs.nestedSortable.js')}}"></script>
    <script src="{{asset('admin/js/jquery.nestable.js')}}"></script>
    <script src="{{asset('admin/js/validation.js')}}"></script>
</head>

<body>

    <div class="main-wrapper">
        <div class="app" id="app">

            <header class="header">
                @yield('nav-bar')
            </header>

        @yield('left-menu')

        <div class="sidebar-overlay" id="sidebar-overlay"></div>
        <article class="content items-list-page">
            @yield('content')
        </article>

        @yield('footer')

        </div>
    </div>

</body>
</html>
