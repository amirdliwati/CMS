<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Frontier-IBS">
        <meta name="keywords" content="Frontier-IBS">
        <meta name="author" content="Frontier-IBS">

        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
        <title>{{ config('app.name') }} - {{__('Login')}}</title>

        <!-- Google font-->
        <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="{{asset('css/auth/font-awesome.css')}}">
        <!-- ico-font-->
        <link rel="stylesheet" type="text/css" href="{{asset('css/auth/vendors/icofont.css')}}">
        <!-- Themify icon-->
        <link rel="stylesheet" type="text/css" href="{{asset('css/auth/vendors/themify.css')}}">
        <!-- Flag icon-->
        <link rel="stylesheet" type="text/css" href="{{asset('css/auth/vendors/flag-icon.css')}}">
        <!-- Feather icon-->
        <link rel="stylesheet" type="text/css" href="{{asset('css/auth/vendors/feather-icon.css')}}">
        <!-- Plugins css start-->

        <!-- Plugins css Ends-->
        <!-- Bootstrap css-->
        <link rel="stylesheet" type="text/css" href="{{asset('css/auth/vendors/bootstrap.css')}}">
        <!-- App css-->
        <link rel="stylesheet" type="text/css" href="{{asset('css/auth/style.css')}}">
        <link id="color" rel="stylesheet" href="{{asset('css/auth/color-1.css')}}" media="screen">
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="{{asset('css/auth/responsive.css')}}">
    </head>

    <body>
        <!-- login page start-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-7 order-1">
                    <img class="bg-img-cover bg-center" src="{{asset('images/auth-bg/1.jpg')}}" alt="looginpage">
                </div>
                <div class="col-xl-5 p-0">
                    <div class="login-card">
                        <div>
                            <div>
                                <a class="logo text-start" href="/">
                                    <img style="max-width:50%" class="img-fluid for-light" src="{{ asset('images/logo/logo.png') }}" alt="looginpage">
                                    <img style="max-width:50%" class="img-fluid for-dark" src="{{ asset('images/logo/light-logo.png') }}" alt="looginpage">
                                </a>
                            </div>
                            <div class="login-main">
                                <form class="theme-form needs-validation" action="{{ route('login') }}" method="POST" novalidate="">
                                    @csrf
                                    <h4>{{__('Sign in to account')}}</h4>
                                    <p>{{__('Enter your email & password to login')}}</p>
                                    <div class="form-group">
                                        <label class="col-form-label">{{__('Email Address')}}</label>
                                        <input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" id="email" type="email" required="" placeholder="example@mail.com">
                                        <div class="invalid-feedback">{{__('Please enter proper email')}}.</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">{{__('Password')}}</label>
                                        <input class="form-control @error('password') is-invalid @enderror" name="password" id="password" type="password" required="" placeholder="*********">
                                        <div class="invalid-feedback">{{__('Please enter password')}}.</div>

                                    </div>
                                    <div class="form-group mb-0">

                                        <button id="myBtn" class="btn btn-primary btn-block" type="submit">{{__('Sign in')}}</button>
                                        @if (Route::has('password.request'))
                                            <p class="change_link">
                                        @endif
                                    </div>


                                    <script>
                                        (function() {
                                        'use strict';
                                        window.addEventListener('load', function() {
                                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                        var forms = document.getElementsByClassName('needs-validation');
                                        // Loop over them and prevent submission
                                        var validation = Array.prototype.filter.call(forms, function(form) {
                                        form.addEventListener('submit', function(event) {
                                        if (form.checkValidity() === false) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                        }
                                        form.classList.add('was-validated');
                                        }, false);
                                        });
                                        }, false);
                                        })();

                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{asset('js/auth/jquery-3.5.1.min.js')}}"></script>
        <!-- Bootstrap js-->
        <script src="{{asset('js/auth/bootstrap/bootstrap.bundle.min.js')}}"></script>
        <!-- feather icon js-->
        <script src="{{asset('js/auth/icons/feather-icon/feather.min.js')}}"></script>
        <script src="{{asset('js/auth/icons/feather-icon/feather-icon.js')}}"></script>
        <!-- scrollbar js-->
        <!-- Sidebar jquery-->
        <script src="{{asset('js/auth/config.js')}}"></script>
        <!-- Plugins JS start-->
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="{{asset('js/auth/script.js')}}"></script>
        <!-- Plugin used-->
    </body>
</html>



