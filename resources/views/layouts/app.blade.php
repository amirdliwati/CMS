<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Frontier">
        <meta name="keywords" content="Frontier">
        <meta name="author" content="Frontier">

        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">

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
        <!-- material design icon-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/material-design-icon.css') }}">
        <!-- Plugins css start-->
        <link href="{{ asset('libs/pace/themes/purple/pace-theme-flash.css') }}" rel="stylesheet">
        <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/parsley.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/prism.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/date-picker.css') }}" rel="stylesheet">
            @yield('css')
        <!-- Plugins css Ends-->
        <!-- Bootstrap css-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
        <!-- App css-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
        <link id="color" rel="stylesheet" href="{{ asset('css/color-1.css') }}" media="screen">
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
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
        </div> <!-- Loader ends-->

        <!-- page-wrapper Start-->
        <div class="page-wrapper compact-wrapper" id="pageWrapper">
            <!-- Page Header Start-->
            <div class="page-main-header">
                <div class="main-header-right row m-0">
                    <div class="main-header-left">
                        <div class="logo-wrapper"><a href="{{ route('home') }}"><img class="img-fluid" src="{{ asset('images/logo/logo.png') }}" alt=""></a></div>
                    </div>

                    <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="grid" id="sidebar-toggle"></i></div>

                    <div class="left-menu-header col">
                        <ul>
                            <li>
                                <i class="fa fa-calendar font-primary mr-2"></i><span>{{\Carbon\Carbon::parse(Auth::User()->last_login_at)->isoFormat('Do MMMM YYYY, h:mm:ss a')}}</span>
                            </li>
                        </ul>
                    </div> <!-- left-menu-header End-->

                    <div class="nav-right col pull-right right-menu">
                        <ul class="nav-menus">

                            <!-- Notifications -->
                            @include('layouts/notifications')

                            <!-- Messages -->
                            {{-- @include('layouts/messages') --}}

                            <li>
                                <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a>
                            </li>

                            @include('layouts/profile')
                        </ul>
                    </div> <!-- nav-right End-->

                    <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>

                </div> <!-- main-header-right End-->
            </div> <!-- Page Header Ends -->


            <!-- Page Body Start-->
            <div class="page-body-wrapper sidebar-icon">

                <!-- Page Sidebar Start-->
                <header class="main-nav">
                    <div class="logo-wrapper">
                        <a href="{{ route('home') }}">
                            <img class="img-fluid" src="{{ asset('images/logo/logo.png') }}" alt="" style="width:200px;height:70px;">
                        </a>
                    </div>

                    <div class="logo-icon-wrapper">
                        <a href="{{ route('home') }}">
                            <img class="img-fluid" src="{{ asset('images/logo/logo-icon.png') }}" alt="" style="width:63px;height:63px;">
                        </a>
                    </div>

                    <nav>
                        <div class="main-navbar">
                            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                            <div id="mainnav">
                                <ul class="nav-menu custom-scrollbar">
                                    @include('layouts/navigation')
                                </ul>
                            </div>
                            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                        </div>
                    </nav>
                </header>
                <!-- Page Sidebar Ends-->

                <div class="page-body">
                    <div class="container-fluid">
                        <div class="page-header">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    @yield('breadcrumb')
                                </div>
                                <div class="col-lg-6">
                                    <!-- Bookmark Start-->
                                    <div class="bookmark pull-right">
                                        <ul>
                                            @yield('bookmark')
                                        </ul>
                                    </div> <!-- Bookmark Ends-->
                                </div> <!-- col End-->
                            </div> <!-- row End-->
                        </div> <!-- page-header End-->
                    </div> <!-- container-fluid End-->

                    <!-- Container-fluid starts-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                @yield('content')
                            </div> <!-- col End-->
                        </div> <!-- row End-->
                    </div> <!-- Container-fluid Ends-->
                </div> <!-- page-body End-->

                <!-- footer start-->
                @include('layouts/footer')

            </div> <!-- page-body-wrapper End-->
        </div> <!-- page-wrapper End-->

        <!-- latest jquery-->
        <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>

        <!-- Sidebar custom-->
        @if (!empty(Auth::user()->roles->where('title','Admin_role')->first()))
            <script type="text/javascript">
                $('.nav-link-custom').removeClass('nav-link');
                $('.li-custom').removeClass('dropdown');

                $('.submenu-title-custom').removeClass('nav-link menu-title');
                $('.submenu-title-custom').addClass('submenu-title');

                $('.nav-sub-childmenu-custom').removeClass('nav-submenu menu-content');
                $('.nav-sub-childmenu-custom').addClass('nav-sub-childmenu submenu-content');
            </script>
        @elseif (Auth::user()->roles->count() > 1)
            <script type="text/javascript">
                $('.nav-link-custom').removeClass('nav-link');
                $('.li-custom').removeClass('dropdown');

                $('.submenu-title-custom').removeClass('nav-link menu-title');
                $('.submenu-title-custom').addClass('submenu-title');

                $('.nav-sub-childmenu-custom').removeClass('nav-submenu menu-content');
                $('.nav-sub-childmenu-custom').addClass('nav-sub-childmenu submenu-content');
            </script>
        @endif

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
        @yield('javascript')
        <script src="{{ asset('libs/pace/pace.js') }}"></script>
        <script src="{{ asset('js/tooltip-init.js') }}"></script>
        <script src="{{ asset('js/prism/prism.min.js') }}"></script>
        <script src="{{ asset('js/sweet-alert/sweetalert.min.js') }}"></script>
        <script src="{{ asset('js/clipboard/clipboard.min.js') }}"></script>
        <script src="{{ asset('js/custom-card/custom-card.js') }}"></script>
        <script src="{{ asset('js/notify/bootstrap-notify.min.js') }}"></script>
        <!-- Plugins JS Ends-->

        <!-- Theme js-->
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="{{ asset('js/theme-customizer/customizer.js') }}"></script>

        <!-- JS used-->
        @include('layouts/appJS')
    </body>
</html>
