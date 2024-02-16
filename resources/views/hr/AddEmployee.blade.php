@extends('layouts.app')
@section('css')
    <title>{{__('Add Employee')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <h3>{{__('Add Employee')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Add Employee')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Page Content-->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Add New Employee')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form id="form" class="f1" enctype="multipart/form-data" data-parsley-required-message="">
                    @csrf
                    <div class="f1-steps">
                        <div class="f1-progress">
                            <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3"></div>
                        </div>
                        <div class="f1-step active">
                            <div class="f1-step-icon" style="line-height: normal;"><i class="fa fa-user"></i></div>
                            <p>{{__('Personal Details')}}</p>
                        </div>
                        <div class="f1-step">
                            <div class="f1-step-icon" style="line-height: normal;"><i class="fa fa-suitcase"></i></div>
                            <p>{{__('Job')}}</p>
                        </div>
                        <div class="f1-step">
                            <div class="f1-step-icon" style="line-height: normal;"><i class="fa fa-address-book"></i></div>
                            <p>{{__('Contact')}}</p>
                        </div>
                    </div>

                    <fieldset>
                        @include('hr/AddEmp/Personal')
                        <div class="f1-buttons mt-5">
                            <button class="btn btn-primary-gradien btn-next" type="button">{{__('Next')}} <i class="fa fa-hand-o-right ml-2"></i></button>
                        </div>
                    </fieldset>
                    <fieldset>
                        @include('hr/AddEmp/Job')
                        <div class="f1-buttons mt-5">
                            <button class="btn btn-warning-gradien btn-previous" type="button"><i class="fa fa-hand-o-left mr-2"></i>{{__('Previous')}}</button>
                            <button class="btn btn-primary-gradien btn-next" type="button">{{__('Next')}} <i class="fa fa-hand-o-right ml-2"></i></button>
                        </div>
                    </fieldset>
                    <fieldset>
                        @include('hr/AddEmp/Phones')
                        @include('hr/AddEmp/Mails')
                        @include('hr/AddEmp/Address')
                        @include('hr/AddEmp/Emergency')
                        <div class="f1-buttons mt-5">
                            <button class="btn btn-warning-gradien btn-previous" type="button"><i class="fa fa-hand-o-left mr-2"></i>{{__('Previous')}}</button>
                            <button class="btn btn-success-gradien btn-submit" type="submit" id="finish"><i class="fa fa-save mr-2"></i>{{__('Finish')}}</button>
                        </div>
                    </fieldset>
                </form>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end card-body-->
    <div class="card-footer">
        <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
    </div>
</div><!--end card-->
@endsection
@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/touchspin/touchspin.js') }}"></script>
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- Form Wizard -->
    <script src="{{ asset('js/form-wizard/form-wizard-three.js') }}"></script>
    <script src="{{ asset('js/form-wizard/jquery.backstretch.min.js') }}"></script>

    <!-- Files Upload -->
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/jquery.form-upload.init.js') }}"></script>

    <!-- Repeater -->
    <script src="{{ asset('js/repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('js/repeater/jquery.form-repeater.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $('.datem').datepicker();
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        $('.money').TouchSpin({ min: 0,max: 10000000, maxboostedstep: 10, initval: 0, buttondown_class: 'btn btn-secondary-gradien', buttonup_class: 'btn btn-info-gradien' });

        $( ".select2" ).change(function() {
            if ($(this).val() == "") { $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'}); }
            else { $(this).siblings(".select2-container").css({'border': '','border-radius': ''}); }
        });

        $( "#emp_image" ).change(function() {
            if ($('#emp_image').val() == "") {
                $('#perpic').children( ".dropify-wrapper" ).css("border", "1px solid red");
            }else {
                $('#perpic').children( ".dropify-wrapper" ).css("border", "");
            }
        });
    </script>

    <!-- Country -->
    <script type="text/javascript">
        $('#country').change(function() {
            event.preventDefault()
            var countryID = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{url('get-states-list')}}",
                data: {'country_id' : countryID},
                success: function (data) {
                    $("#state").empty();
                    $("#state").append('<option value=""></option>');
                    $.each(data,function(key,value){
                        $("#state").append('<option value="'+key+'">'+value+'</option>');
                    });
                },
                error: function(jqXHR){
                    if(jqXHR.status==0) {
                        SwalMessage('You are offline','Connection to the server has been lost. Please check your internet connection and try again.','error');
                    } else{
                        SwalMessage('Attention','Something went wrong. Please try again later.','warning');
                    }
                }
            });
        });

        $('#country2').change(function() {
            event.preventDefault()
            var countryID = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{url('get-states-list')}}",
                data: {'country_id' : countryID},
                success: function (data) {
                    $("#state2").empty();
                    $("#state2").append('<option value=""></option>');
                    $.each(data,function(key,value){
                        $("#state2").append('<option value="'+key+'">'+value+'</option>');
                    });
                },
                error: function(jqXHR){
                    if(jqXHR.status==0) {
                        SwalMessage('You are offline','Connection to the server has been lost. Please check your internet connection and try again.','error');
                    } else{
                        SwalMessage('Attention','Something went wrong. Please try again later.','warning');
                    }
                }
            });
        });
    </script>

    <!-- Department -->
    <script type="text/javascript">
        $('#department').change(function() {
            var departmentID = $(this).val();
            if(departmentID)
            {
                $.ajax({type:"GET", url:"{{url('get-position-list')}}?department_id="+departmentID, success:function(res)
                {
                    if(res){$("#position").empty(); $("#position").append('<option value=""></option>'); $.each(res,function(key,value) {
                        $("#position").append('<option value="'+key+'">'+value+'</option>'); });
                    } else { $("#position").empty();}
                }
            });
                $.ajax({
                    type:"GET",
                    url:"{{url('get-manager-list')}}?department_id="+departmentID,
                    success:function(res){
                        if(res){
                            $("#line").empty();
                            $("#line").append('<option value=""></option>');
                            $.each(res,function(key,value){
                                $("#line").append('<option value="'+key+'">'+value+'</option>');
                            });

                        } else{ $("#line").empty(); }
                    }
                });
            } else{ $("#position").empty(); $("#line").empty(); }
        });
    </script>

    <!-- Submit Form -->
    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/AddEmployee",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data == 'Email Error') {
                        SwalMessage('System Email','System Email is Exists.','error');
                    } else {
                        window.location.replace('/EmployeeProfile/'+data);
                    }
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
