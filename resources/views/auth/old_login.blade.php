<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Frontier">
    <meta name="keywords" content="Frontier">
    <meta name="author" content="Frontier">

    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <title>{{ config('app.name') }} - Login</title>

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">

    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fontawesome.css') }}">

    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/icofont.css') }}">

    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/themify.css') }}">

    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/flag-icon.css') }}">

    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/feather-icon.css') }}">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">

    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('css/color-1.css') }}" media="screen">

    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">

    <style>
        .content {
        position: fixed;
        top: 0;
        background: rgba(0, 0, 0, 0.5);
        color: #f1f1f1;
        width: 100%;
        height: 9%;
        padding: 10px;
        }

        .footer {
        position: fixed;
        bottom: 0px;
        background: rgba(0, 0, 0, 0.5);
        color: #f1f1f1;
        width: 100%;
        height: 6%;
        padding: 10px;
        }

        #myBtn {
        width: 100px;
        height: 34px;
        font-size: 14px;
        padding: 2px;
        border: none;
        background: rgb(211, 156, 36);
        color: #fff;
        cursor: pointer;
        }
    </style>
  </head>

  <body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="loader-index"><span></span></div>
        <svg>
        <defs></defs>
        <filter id="goo">
            <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
            <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo">    </fecolormatrix>
        </filter>
        </svg>
    </div>
    <!-- Loader ends-->

    <!-- page-wrapper Start-->
    <div class="page-wrapper">
        <div class="container-fluid p-0">
        <!-- login page start-->
        <div class="authentication-main mt-0">
            <div class="row">
                <div class="col-md-12">
                    <div class="auth-innerright auth-bg">

                    </div> <!-- end auth-innerright-->
                    <form class="theme-form" action="{{ route('login') }}" method="POST" autocomplete="on">
                        @csrf
                        <div class="content">
                            <div class="row" style="margin: 4px;">
                                <div class="col-lg-7">
                                    <a href="https://frontier-ibs.us/" class="logo logo-admin"><img src="{{ asset('images/logo/light-logo.png') }}" height="45" alt="logo"></a>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="form-group m-form__group mb-3">
                                            <div class="input-group">
                                                <input id="email" type="email" placeholder="EMail Address@mail.com" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required />
                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert" style="color: red;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="form-group m-form__group mb-3">
                                            <div class="input-group">
                                                <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required />
                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                        @if(session('info'))
                                            <div class="alert alert-danger" role="alert">
                                                <i class="mdi mdi-alert-outline alert-icon"></i>
                                                {!! session('info') !!}
                                            </div>
                                        @endif
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group row mb-3">
                                        <button id="myBtn" class="btn btn-pill btn-lg" type="submit">Log In </button>
                                        @if (Route::has('password.request'))
                                        <p class="change_link">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="footer">
                        <div class="row" style="margin: 4px;">
                            <div class="col-lg-12">
                                Copyright &copy; <script>document.write(new Date().getFullYear())</script> {{ config('app.company') }} Management System Developed & Powered By <a href="https://frontier-ibs.us/" style="color: rgb(211, 156, 36);"> Frontier IBS Inc. </a><span class="text-muted d-none d-sm-inline-block float-right"><b>Version</b> 1.1.2</span>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col-->
            </div> <!-- end row-->
        </div> <!-- end authentication-->
        <!-- login page end-->
        </div> <!-- end container-fluid-->
    </div> <!-- end page-wrapper-->

    <!-- latest jquery-->
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('js/login.js') }}"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('js/script.js') }}"></script>
    <!-- login js-->

  </body>
</html>
