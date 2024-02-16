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
        <title>@yield('title')</title>

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

    </head>
    <body class="antialiased">
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
        <div class="page-wrapper compact-wrapper" id="pageWrapper">
          <!-- error-400 start-->
          <div class="error-wrapper">
            <div class="container"><img class="img-100" src="{{ asset('images/other-images/sad.png') }}" alt="">
              <div class="error-heading">
                <h2 class="headline font-warning">@yield('code')</h2>
              </div>
              <div class="col-md-8 offset-md-2">
                <p class="sub-content">@yield('message')</p>
              </div>
              <div><a class="btn btn-warning-gradien btn-lg" href="{{ route('home') }}">BACK TO HOME PAGE</a></div>
            </div>
          </div>
          <!-- error-400 end-->
        </div>

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
