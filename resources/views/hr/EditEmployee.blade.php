@extends('layouts.app')
@section('css')
    <title>{{__('Modifiy Employee')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <h3>{{__('Modifiy Employee')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Modifiy Employee')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Page Content-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="Employee_id" name="Employee_id" value="{{$Employee->id}}">
    <div class="card">
        <div class="card-header b-l-warning border-3"><h5> {{__('Modifiy Employee')}} ({{$Employee->first_name}} {{$Employee->last_name}})</h5> @include('layouts/button_card') </div>
        <div class="card-body">
            <ul class="nav nav-pills nav-justified" id="pills-tab" role="tablist">
                <li class="nav-item btn-round">
                    <a class="nav-link active" id="Personal-tab" data-toggle="pill" href="#Personal" aria-selected="true"><i class="fa fa-user"></i><b id="apers"></b>{{__('Personal')}}</a>
                </li>
                <li class="nav-item btn-round">
                    <a class="nav-link" id="Job-tab" data-toggle="pill" href="#Job"  aria-selected="false"><i class="fa fa-suitcase"></i><b id="ajobs"></b>{{__('Job')}}</a>
                </li>
                <li class="nav-item btn-round">
                    <a class="nav-link" id="Contact-tab" data-toggle="pill" href="#Contact" aria-selected="false"><i class="fa fa-address-book"></i><b id="acont"></b>{{__('Contact')}}</a>
                </li>
            </ul>
            <div class="tab-content mt-4" id="pills-tabContent">

                <div class="tab-pane fade show active" id="Personal">
                    @include('hr/EditEmp/PersonalE')
                </div><!-- end Personal step-1 -->

                <div class="tab-pane fade mt-4" id="Job">
                    @include('hr/EditEmp/JobE')
                </div><!-- /.Job -->

                <div class="tab-pane fade mt-4" id="Contact">
                    @include('hr/EditEmp/PhonesE')
                    @include('hr/EditEmp/MailsE')
                    @include('hr/EditEmp/AddressE')
                    @include('hr/EditEmp/EmergencyE')
                </div><!-- /.Contact -->

            </div><!-- /.tab-content -->
        </div><!--end card-body-->
        <div class="card-footer">
            <button id="finish" form="form" data-action="save-png" type="submit" class="btn btn-success-gradien btn-pill"><i class="mdi mdi-content-save-all mr-1"></i>{{__('Save')}}</button>
            <a href="{{route('ViewEmp')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Employees')}}</button></a>
        </div>
    </div><!--end card-->
</form>
@endsection
@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/moment/moment.js') }}"></script>
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
        $('.datem').datepicker({language: 'en', autoClose: true, dateFormat: 'yyyy-mm-dd' });
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});

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
        $('#country').change(function(){
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

    <!-- Validation -->
    <script type="text/javascript">
        $('#finish').click( function() {
            $(".select2").each(function() {
                if ($(this).val() == "") {
                    $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'});
                }else {
                    $(this).siblings(".select2-container").css({'border': '','border-radius': ''});
                }
            });

            if ($('#prefix').val() == "" || $('#first_name').val() == "" || $('#last_name').val() == "" || $('#gender').val() == "" || $('#marital_status').val() == "" || $('#birthdate').val() == "" || $('#blood').val() == "" || $('#Nationality').val() == "") {
                $('#apers').css("color","red");
                $('#apers').text("* ");
            }else {
                $('#apers').css("color","");
                $('#apers').text("");
            }

            if ($('#hire_date').val() == "" || $('#job_type_id').val() == "" || $('#status_id').val() == "" || $('#department').val() == "" || $('#position').val() == "" || $('#line').val() == "") {
                $('#ajobs').css("color","red");
                $('#ajobs').text("* ");
            }else {
                $('#ajobs').css("color","");
                $('#ajobs').text("");
            }

            if ($('#email').val() == "" || $('#system_email').val() == "" || $('#country').val() == "" || $('#state').val() == "" || $('#address').val() == "" || $('#relationship').val() == "" || $('#fname_emer').val() == "" || $('#lname_emer').val() == "" || $('#house_phone').val() == "" || $('#mobile_phone').val() == "") {
                $('#acont').css("color","red");
                $('#acont').text("* ");
            }else {
                $('#acont').css("color","");
                $('#acont').text("");
            }

            var r = $('.repeater-custom-show-hide').repeaterVal();
            for (a = 0; a < r['phones'].length; a++) {
                if ($('select[name="phones['+a+'][phone_type]"]').val() == "" || $('input[name="phones['+a+'][number]"]').val() == "") {
                    $('#acont').css("color","red");
                    $('#acont').text("* ");
                }else {
                    $('#acont').css("color","");
                    $('#acont').text("");
                }
            }
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
                url: "/EditEmployee",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    window.location.replace('/EmployeeProfile/'+data);
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
