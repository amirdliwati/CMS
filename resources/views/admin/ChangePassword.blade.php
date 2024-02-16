@extends('layouts.app')
@section('css')
    <title>{{__('Change Password')}}</title>
@endsection
@section('breadcrumb')
    <h3>{{__('Change Password')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Change Password')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Page Content-->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Change Password')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <form id="form" data-parsley-required-message="">
            @csrf <br>
            <div class="row mb-3">
                <label for="password" class="col-md-3 col-form-label text-right">{{__('Current Password')}}</label>
                <div class="col-md-6">
                    <div class="input-group"><div class="input-group-append bg-custom b-0"><span class="input-group-text"><b class="fa fa-key text-primary"></b></span></div><input type="password" class="form-control" placeholder="~x!82DEge3" id="password" name="password" value="{{ old('password') }}" autofocus required></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="newpassword" class="col-md-3 col-form-label text-right">{{__('New Password')}}</label>
                <div class="col-md-6">
                    <div class="input-group"><div class="input-group-append bg-custom b-0"><span class="input-group-text"><b class="mdi mdi-textbox-password text-primary"></b></span></div><input type="password" class="form-control maxclass" placeholder="~x!82DEge3" id="newpassword" name="newpassword" value="{{ old('newpassword') }}" maxlength="25" autofocus required></div>
                </div>
            </div><!-- row Material -->
            <div class="row mb-3">
                <label for="conewpassword" class="col-md-3 col-form-label text-right">{{__('Confirm New Password')}}</label>
                <div class="col-md-6">
                    <div class="input-group"><div class="input-group-append bg-custom b-0"><span class="input-group-text"><b class="mdi mdi-textbox-password text-primary"></b></span></div><input type="password" class="form-control maxclass" placeholder="~x!82DEge3" id="conewpassword" name="conewpassword" value="{{ old('conewpassword') }}" maxlength="25" autofocus required></div>
                </div>
            </div><!-- row Material -->
            <div class="row">
                <div class="offset-md-3 col-md-6">
                    @if(session()->has('message'))
                        <div class="alert icon-custom-alert alert-outline-success alert-success-shadow" role="alert">
                            <i class="mdi mdi-check-all alert-icon"></i>
                            <div class="alert-text">
                                <strong>{{__('Well done')}}!</strong> {{ session()->get('message') }}
                            </div>

                            <div class="alert-close">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="mdi mdi-close text-danger"></i></span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div><!-- row Material -->
        </form>
    </div><!--end card-body-->
    <div class="card-footer">
        <button id="finish" form="form" type="submit" class="btn btn-success-gradien btn-pill"><i class="mdi mdi-content-save-all mr-1"></i>{{__('Save')}}</button>
        <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-cancel mr-1"></i>{{__('Cancel')}}</button></a>
    </div>
</div><!--end card-->
@endsection

@section('javascript')
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- Other Scripts -->
        <script type="text/javascript">
        $('.maxclass').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $('form').parsley();
    </script>

    <script type="text/javascript">
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>

    <script type="text/javascript">
        const togglenewPassword = document.querySelector('#togglenewPassword');
        const newpassword = document.querySelector('#newpassword');
        togglenewPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = newpassword.getAttribute('type') === 'password' ? 'text' : 'password';
            newpassword.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>

    <script type="text/javascript">
        const toggleconewpassword = document.querySelector('#toggleconewpassword');
        const conewpassword = document.querySelector('#conewpassword');
        toggleconewpassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = conewpassword.getAttribute('type') === 'password' ? 'text' : 'password';
            conewpassword.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event){
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/ChangePassword",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    window.location.replace('/');
                },
                error: function(jqXHR){
                    if(jqXHR.status==0) {
                        SwalMessage('You are offline','Connection to the server has been lost. Please check your internet connection and try again.','error');
                    } else{
                        SwalMessage('Attention','Something went wrong. Please try again later.','warning');
                    }
                    $('#finish').attr('disabled', false);
                }
            });
        });
    </script>
@endsection
