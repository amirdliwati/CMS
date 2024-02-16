@extends('layouts.app')
@section('css')
    <title>{{__('Training Documents')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
    <style>.select2-container{z-index:100000;}</style>
@endsection
@section('breadcrumb')
    <h3>{{__('Employee Training Documents')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Training Documents')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modaladdTrainingDoc"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add Training Document')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Training Documents')}} ({{$Employee->first_name}} {{$Employee->last_name}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="trining-docs-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('ID Training Doc')}}</th>
                        <th class="text-primary">{{__('Institution')}}</th>
                        <th class="text-primary">{{__('Course Name')}}</th>
                        <th class="text-primary">{{__('Result')}}</th>
                        <th class="text-primary">{{__('Study Location')}}</th>
                        <th class="text-primary">{{__('Issue Date')}}</th>
                        <th class="text-primary">{{__('Controller')}}</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    @foreach ($Training_docs as $Training_doc)
                    <tr style="text-align: center;">
                        <td>{{$Training_doc->id}}</td>
                        <td>{{$Training_doc->institute}}</td>
                        <td>{{$Training_doc->course}}</td>
                        <td>{{$Training_doc->result}}</td>
                        <td>{{$Training_doc->countrie->name}}</td>
                        <td><span class="badge badge-info">{{\Carbon\Carbon::parse($Training_doc->issue_cer)->isoFormat('Do MMMM YYYY')}}</span></td>
                        <td>
                            <div class="btn-group mb-2 mb-md-0">
                                <a href="/PreviewTrainingDoc/{{$Training_doc->id}}" class="btn btn-primary-gradien" target="_blank"><i class="fa fa-eye"></i></a>
                                <button type="button" class="btn btn-danger-gradien btn-delete"><i class="fa fa-trash-o"></i></button>
                            </div><!-- /btn-group -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table><!--end table-->
        </div> <!-- end row -->
    </div> <!-- end card-body -->
        <div class="card-footer">
            <a href="{{route('ViewEmp')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Employees')}}</button></a>
        </div>
</div> <!-- end card -->

<!--modal Add Training Docs-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <input type="hidden" name="employee_id" value="{{$Employee->id}}">
    <div class="modal fade bs-example-modal-xl" id="modaladdTrainingDoc" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Add New Training Documents')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row form-material">
                        <div class="col-md-6">
                            <div class="row form-material">
                                <div class="col-md-12">
                                    <label class="col-form-label text-right">{{__('Official name of Institution')}}</label>
                                    <input type="text" class="form-control mb-2 mx" id="institute" name="institute" value="{{ old('institute') }}" autocomplete="institute" placeholder="Institute / Organization" autofocus maxlength="50" required >
                                </div> <!-- end col -->
                                <div class="col-md-12">
                                    <label class="col-form-label text-right">{{__('Course Name')}}</label>
                                    <input type="text" class="form-control mb-2 mx" id="course" name="course" value="{{ old('course') }}" autocomplete="course" placeholder="Programme" autofocus maxlength="50" required >
                                </div> <!-- end col -->
                            </div>
                            <div class="row form-material">
                                <div class="col-md-6">
                                    <label class="col-form-label text-right">{{__('Result')}}</label>
                                    <input type="text" class="form-control mx" id="result" name="result" value="{{ old('result') }}" autocomplete="result" placeholder="Score" autofocus maxlength="5" required >
                                </div> <!-- end col -->
                                <div class="col-md-6">
                                    <label class="col-form-label text-right">{{__('Study Location')}}</label>
                                    <select class="form-control select2" id="course_loc" name="course_loc" autofocus required>
                                        <option selected="selected" value="{{ old('course_loc') }}"></option>
                                        @foreach($Countries as $Countrie)
                                            <option value="{{$Countrie->id}}">{{$Countrie->name}}</option>
                                        @endforeach
                                    </select>
                                </div> <!-- end col -->
                                <div class="col-md-12">
                                    <label class="col-form-label text-right">{{__('Issue Date')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                        <input type="text" class="form-control datem" id="issue_cer" name="issue_cer" value="{{ old('issue_cer') }}" autocomplete="issue_cer" placeholder="mm/dd/yyyy" autofocus readonly required >
                                    </div>
                                </div> <!-- end col -->
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-6 mt-5">
                            <div id="trainingPic">
                                <h4 class="mt-0 header-title"><b class="mr-2">{{__('Certificate Image / File')}}</b></h4>
                                <p class="text-muted mb-4">{{__('File should be up to 15 Megabytes')}}.</p>
                                <input type="file" class="dropify form-control @error('train_cer') is-invalid @enderror" id="train_cer" name="train_cer" accept="image/*,application/pdf" data-default-file=""  data-height="125" data-max-file-size="15M" data-allowed-file-extensions="png jpg jpeg pdf" required>
                            </div><!--end card-body-->
                        </div><!--end col-->
                    </div>
                </div> <!--end modal-body-->
                <div class="modal-footer">
                    <button type="submit" id="finish" class="btn btn-success-gradien">{{__('Save')}}</button>
                    <button type="button" class="btn btn-secondary-gradien" data-dismiss="modal" onclick="this.form.reset()">{{__('Close')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

@endsection

@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>

    <!-- Files Upload -->
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/jquery.form-upload.init.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $('.datem').datepicker();
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        $('#trining-docs-table').DataTable({"order":[0, 'desc'],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});

        $( ".select2" ).change(function() {
            if ($(this).val() == "") { $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'}); }
            else { $(this).siblings(".select2-container").css({'border': '','border-radius': ''}); }
        });

        $( "#train_cer" ).change(function() {
            if ($('#train_cer').val() == "") {
                $('#trainingPic').children( ".dropify-wrapper" ).css("border", "1px solid red");
            }else {
                $('#trainingPic').children( ".dropify-wrapper" ).css("border", "");
            }
        });

        $('#finish').click( function() {
            $(".select2").each(function() {
                if ($(this).val() == "") {
                    $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'});
                }else {
                    $(this).siblings(".select2-container").css({'border': '','border-radius': ''});
                }
            });

            if ($('#train_cer').val() == "") {
                $('#trainingPic').children( ".dropify-wrapper" ).css("border", "1px solid red");
            } else {
                $('#trainingPic').children( ".dropify-wrapper" ).css("border", "");
            }
        });
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/TrainingDocs",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdTrainingDoc").modal("hide");
                    $('#finish').attr('disabled', false);
                    $('#form').trigger("reset");
                    swal({
                        title: 'Success',
                        text: 'Training Document has been added successfully.',
                        type: 'success',
                        preConfirm: function (){
                            location.reload(true);
                        }
                    });
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
        $('#trining-docs-table tbody').on( 'click', '.btn-delete', function () {
            var table = $('#trining-docs-table').DataTable();
            var TrainingID = table.row( $(this).parents('tr') ).data()[0];
            var row = table.row( $(this).parents('tr') );
            swal({
                title: 'Caution',
                text: 'Are you sure you want to delete this legal document?',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary-gradien',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> No',
                preConfirm: function (){
                    $.ajax({
                        type: "GET",
                        url: " /DeleteTrainingDocs/" + TrainingID,
                        contentType: false,
                        success: function (data) {
                            swal({
                                title: 'Success',
                                text: 'The legal Documents has been deleted.',
                                type: 'success',
                                preConfirm: function (){
                                    row.remove().draw();
                                }
                            });
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
        });
    </script>
@endsection
