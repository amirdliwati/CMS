@extends('layouts.app')
@section('css')
    <title>{{__('Employee Signature')}}</title>

    <!-- signature-pad -->
    <link href="{{ asset('libs/signature_pad/css/signature-pad.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb')
    <h3>{{__('Employee Signature')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Signature')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Page Content-->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Employee Signature')}} ({{request()->Employee_id}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <form id="form" enctype="multipart/form-data" data-parsley-required-message="">
            @csrf
            <div class="row">
                <div class="col-md-12" align="center">
                    <div id="signature-pad" class="signature-pad">
                    <div class="signature-pad--body">
                        <canvas></canvas>
                    </div>
                    <div class="signature-pad--footer">
                        <div class="signature-pad--actions">
                        <div>
                            <button type="button" class="btn btn-danger-gradien btn-round clear" data-action="clear"><i class="mdi mdi-eraser mr-1"></i></button>
                            <button type="button" class="btn btn-info-gradien btn-round" data-action="undo"><i class="mdi mdi-undo-variant mr-1"></i></button>
                            <button type="button" class="btn btn-info-gradien btn-round" data-action="change-color" hidden="">{{__('Change color')}}</button>
                        </div>
                        <input type="hidden" id="image" name="image" value="">
                        </div> <!-- end signature-pad--actions -->
                    </div> <!-- end signature-pad--footer -->
                    </div> <!-- end signature-pad -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </form>
    </div><!--end card-body-->
    <div class="card-footer">
        <button id="finish" form="form" data-action="save-png" type="submit" class="btn btn-success-gradien btn-pill"><i class="mdi mdi-content-save-all mr-1"></i>{{__('Save')}}</button>
        <a href="{{route('ViewEmp')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Employees')}}</button></a>
    </div>
</div><!--end card-->
@endsection
@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- signature_pad js -->
    <script src="{{ asset('libs/signature_pad/js/signature_pad.js') }}"></script>
    <script src="{{ asset('libs/signature_pad/js/app.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            if (signaturePad.isEmpty()) {
                SwalMessage('Sorry!','Please provide a signature first.','error');
                $('#finish').attr('disabled', false);
                return false;
            } else {
                var dataURL = signaturePad.toDataURL();
                $('#image').val(dataURL);
                //download(dataURL, "signature.png");
                event.preventDefault()
                var formData = new FormData(this)
                $.ajax({
                    type: "POST",
                    url: "/AddEmployeeSignature",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        window.location.replace("/Employees");
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
            }
        });
    </script>
@endsection
