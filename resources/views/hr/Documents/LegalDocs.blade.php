@extends('layouts.app')
@section('css')
    <title>{{__('Legal Documents')}}</title>
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Employee Legal Documents')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Legal Documents')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modaladdLegalDoc"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add Legal Document')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Legal Documents')}} ({{$Employee->first_name}} {{$Employee->last_name}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="legal-docs-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('ID Legal Doc')}}</th>
                        <th class="text-primary">{{__('Document Type')}}</th>
                        <th class="text-primary">{{__('Issued On')}}</th>
                        <th class="text-primary">{{__('Expired On')}}</th>
                        <th class="text-primary">{{__('Controller')}}</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    @foreach ($legal_docs as $legal_doc)
                    <tr style="text-align: center;">
                        <td>{{$legal_doc->id}}</td>
                        <td>
                            @if($legal_doc->doc_type == 1)
                            {{ __('Passport') }}
                            @elseif($legal_doc->doc_type == 2)
                            {{ __('Visa')}}
                            @endif
                        </td>
                        <td><span class="badge badge-info">{{\Carbon\Carbon::parse($legal_doc->start_vaild)->isoFormat('Do MMMM YYYY')}}</span></td>
                        <td><span class="badge badge-warning">{{\Carbon\Carbon::parse($legal_doc->end_valid)->isoFormat('Do MMMM YYYY')}}</span></td>
                        <td>
                            <div class="btn-group mb-2 mb-md-0">
                                <a href="/PreviewLegalDoc/{{$legal_doc->id}}" class="btn btn-primary-gradien" target="_blank"><i class="fa fa-eye"></i></a>
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

<!--modal Add Legal Docs-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <input type="hidden" name="employee_id" value="{{$Employee->id}}">
    <div class="modal fade bs-example-modal-xl" id="modaladdLegalDoc" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Add New Legal Documents')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="doc_type" class="col-form-label text-right">{{__('Document Type')}}</label>
                                    <select class="form-control" style="width: 100%; height:36px;" id="doc_type" name="doc_type" autocomplete="doc_type" autofocus required>
                                        <option selected="selected" value="{{ old('doc_type') }}">
                                            @if( old('doc_type') == "" )
                                            {{__('Select Option')}}
                                                @elseif(old('doc_type') == 1 )
                                                {{ __('Passport') }}
                                                @elseif(old('doc_type') == 2 )
                                                {{ __('Visa')}}
                                            @endif
                                        </option>
                                        <option value="1">{{__('Passport')}}</option>
                                        <option value="2">{{__('Visa')}}</option>
                                    </select>
                                </div> <!-- end col -->
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="start_vaild" class="col-form-label text-right">{{__('Issued On')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                        <input type="text" class="form-control datem" id="start_vaild" name="start_vaild" value="{{ old('start_vaild') }}" autocomplete="start_vaild" placeholder="mm/dd/yyyy" autofocus readonly required >
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="end_valid" class="col-form-label text-right">{{__('Expired On')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                        <input type="text" class="form-control datem" id="end_valid" name="end_valid" value="{{ old('end_valid') }}" autocomplete="end_valid" placeholder="mm/dd/yyyy" autofocus readonly required >
                                    </div>
                                </div> <!-- end col -->
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-6">
                            <div id="legalPic">
                                <h4 class="mt-0 header-title"><b class="mr-2">{{__('Document File Passort')}} / {{__('Visa')}}</b></h4>
                                <p class="text-muted mb-4">{{__('File should be up to 15 Megabytes')}}.</p>
                                <input type="file" class="dropify form-control" id="legal_attach" name="legal_attach" accept="image/*,application/pdf" data-default-file=""  data-height="125" data-max-file-size="15M" data-allowed-file-extensions="png jpg jpeg pdf" required>
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
        $('#legal-docs-table').DataTable({"order":[0, 'desc'],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});

        $( "#legal_attach" ).change(function() {
            if ($('#legal_attach').val() == "") {
                $('#legalPic').children( ".dropify-wrapper" ).css("border", "1px solid red");
            }else {
                $('#legalPic').children( ".dropify-wrapper" ).css("border", "");
            }
        });

        $('#finish').click( function() {
            if ($('#legal_attach').val() == "") {
                $('#legalPic').children( ".dropify-wrapper" ).css("border", "1px solid red");
            }else {
                $('#legalPic').children( ".dropify-wrapper" ).css("border", "");
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
                url: "/LegalDocs",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdLegalDoc").modal("hide");
                    $('#finish').attr('disabled', false);
                    $('#form').trigger("reset");
                    swal({
                        title: 'Success',
                        text: 'Legal Document has been added successfully.',
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
        $('#legal-docs-table tbody').on( 'click', '.btn-delete', function () {
            var table = $('#legal-docs-table').DataTable();
            var LegalID = table.row( $(this).parents('tr') ).data()[0];
            var row = table.row( $(this).parents('tr') );
            swal({
                title: 'Caution',
                text: 'Are you sure you want to delete this legal document?',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary-gradien',
                cancelButtonClass: 'btn btn-danger-gradien',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> No',
                preConfirm: function (){
                    $.ajax({
                        type: "GET",
                        url: " /DeleteLegalDocs/" + LegalID,
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
