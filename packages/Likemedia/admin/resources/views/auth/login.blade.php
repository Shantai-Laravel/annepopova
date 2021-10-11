<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Likemedia Admin Panel</title>
    <meta name="description" content="Admin Panel">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,600">
    <link rel="stylesheet" href="{{ asset('admin/css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/app-green.css') }}">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
</head>
<body>
    <div class="auth">
        <div class="auth-container">
            <div class="card">
                <header class="auth-header">
                    <h1 class="auth-title">
                        <div class="logo"> <span class="l l1"></span> <span class="l l2"></span> <span class="l l3"></span> <span class="l l4"></span> <span class="l l5"></span> </div>
                        Like Media Admin
                    </h1>
                </header>
                <div class="auth-content">
                    <p class="text-xs-center">Authorization</p>
                    <form class="login-form" role="form" method="POST" action="{{ url('/auth/login') }}">
                        <div class="form-group">
                            <label for="username">Login</label>
                            <input type="text" class="form-control underlined" name="login" id="username" placeholder="Login" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control underlined" name="password" id="password" placeholder="Password" required>
                        </div>
                        <input type="submit" class="btn btn-block btn-primary" onclick="saveForm(this)" data-form-id="login-form" value="Sing In"/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
            </div>
            <div class="text-xs-center">
                <a href="{{ url('/') }}" class="btn btn-secondary rounded btn-sm"> <i class="fa fa-arrow-left"></i> Go to the site </a>
            </div>
        </div>
    </div>
</body>
</html>
