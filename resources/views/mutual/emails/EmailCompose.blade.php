@extends('layouts.app')
@section('css')
    <title>{{__('Email Compose')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb')
    <h3>{{__('Email Compose')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Email Compose')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <div class="card">
        <div class="card-header b-l-success border-3"><h5> {{__('New Message')}} </h5> @include('layouts/button_card') </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <label for="trainees" class="col-form-label text-right">{{__('To')}}</label>
                    <select class="form-control select2" name="trainees[]" id="trainees" multiple="multiple" autofocus required>
                        @foreach($Trainees as $Trainee)
                            <option value="{{$Trainee->id}}">{{$Trainee->first_name}} {{$Trainee->last_name}}</option>
                        @endforeach
                    </select>
                </div><!--end col-->
            </div><!--end row--> <br>

            <div class="row">
                <div class="col-md-12">
                    <label for="subject" class="col-form-label text-right">{{__('Subject')}}</label>
                    <input type="text" class="form-control mx" id="subject" name="subject" value="{{ old('subject') }}" autocomplete="subject" placeholder="~" autofocus maxlength="50" required>
                </div><!--end col-->
            </div><!--end row--> <br>

            <div class="row">
                <div class="col-md-12">
                    <label for="message" class="col-form-label text-right">{{__('Message')}}</label>
                    <textarea id="message" name="message" class="form-control" cols="10" rows="2" required>{{ old('message') }}</textarea>
                </div><!--end col-->
            </div><!--end row-->
        </div> <!-- end card-body -->
            <div class="card-footer">
                <button id="finish" form="form" data-action="save-png" type="submit" class="btn btn-success-gradien btn-pill"><i class="fa fa-paper-plane mr-2"></i>{{__('SEND')}}</button>
                <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
            </div>
    </div> <!-- end card -->
</form>
@endsection
@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- ckeditor js -->
    <script src="{{ asset('js/editor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/editor/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ asset('js/ckeditor-custom/email-app.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $(".select2").select2({placeholder: 'Select trainees'});
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
            for ( instance in CKEDITOR.instances ) {CKEDITOR.instances[instance].updateElement();}
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/EmailCompose",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    window.location.replace('/Emails');
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
