@extends('layouts.app')
@section('css')
    <title>{{__('Role Employee')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb')
    <h3>{{__('Role Employees')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Role Employees')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<form id="form" data-parsley-required-message="">
	@csrf
    <div class="card">
        <div class="card-header b-l-primary border-3"><h5> {{__('Role Employee')}}</h5> @include('layouts/button_card') </div>
            <div class="card-body">
                <div class="ribbon-vertical-left-wrapper card">
                    <div class="card-body">
                        <div class="ribbon ribbon-bookmark ribbon-vertical-left ribbon-info"><i class="fa fa-certificate"></i></div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="employee" class="col-form-label text-right">{{__('Employee')}}</label>
                                <select class="form-control select2 mb-3" id="employee" name="employee" autofocus required>
                                    <option selected="selected" value="{{ old('employee') }}"></option>
                                    @foreach($Employees as $Employee)
                                        <option value="{{$Employee->id}}">{{$Employee->employee->first_name}} {{$Employee->employee->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="col-form-label text-right">{{__('Department')}}</label>
                                <select class="form-control select2 mb-3" id="department" name="department" autofocus required>
                                    <option selected="selected" value="{{ old('department') }}"></option>
                                </select>
                            </div>
                        </div><!-- end row -->
                    </div>
                </div><!-- end Information -->
            </div>
	    <div class="card-footer">
            <button id="finish" form="form" data-action="save-png" type="submit" class="btn btn-success-gradien btn-pill"><i class="mdi mdi-content-save-all mr-1"></i>{{__('Save')}}</button>
            <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
        </div>
	</div>
</form>
@endsection
@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});

        $( ".select2" ).change(function() {
            if ($(this).val() == "") { $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'}); }
            else { $(this).siblings(".select2-container").css({'border': '','border-radius': ''}); }
        });

        $('#finish').click( function() {
            $(".select2").each(function() {
                if ($(this).val() == "") {
                    $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'});
                }else {
                    $(this).siblings(".select2-container").css({'border': '','border-radius': ''});
                }
            });
        });
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event){
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/RoleEmployee",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data == 'error role') {
                        SwalMessage('Error','The Employee has this role, please cheack employee account.','warning');
                        $('#finish').attr('disabled', false);
                    }
                    else {
                        swal({
                            title: 'Success',
                            text: 'The role has been added successfully.',
                            type: 'success',
                            preConfirm: function (){location.reload();}
                        });
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

    <script type="text/javascript">
        $('#employee').change(function() {
            event.preventDefault()
            var UserID = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{url('get-rloes-list')}}",
                data: {'UserID' : UserID},
                success: function (data) {
                    $("#department").empty();
                    $("#department").append('<option value=""></option>');
                    $.each(data,function(key,value){
                        $("#department").append('<option value="'+key+'">'+value+'</option>');
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
@endsection
