@extends('layouts.app')
@section('css')
    <title>{{__('Experiences')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
    <style>.select2-container{z-index:100000;}</style>
@endsection
@section('breadcrumb')
    <h3>{{__('Employee Experiences')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Experiences')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modaladdExperience"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add New Experience')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Employee Experiences')}} ({{$Employee->first_name}} {{$Employee->last_name}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="experiences-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('ID Experience')}}</th>
                        <th class="text-primary">{{__('Employer')}}</th>
                        <th class="text-primary">{{__('Type of Business or Sector')}}</th>
                        <th class="text-primary">{{__('Occupation or Position')}}</th>
                        <th class="text-primary">{{__('From')}}</th>
                        <th class="text-primary">{{__('To')}}</th>
                        <th class="text-primary">{{__('Job Location')}}</th>
                        <th class="text-primary">{{__('Controller')}}</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    @foreach ($Experiences as $Experience)
                    <tr style="text-align: center;">
                        <td>{{$Experience->id}}</td>
                        <td>{{$Experience->employer}}</td>
                        <td>{{$Experience->sector}}</td>
                        <td>{{$Experience->job_title}}</td>
                        <td>
                            <span class="badge badge-info">{{\Carbon\Carbon::parse($Experience->start_date)->isoFormat('Do MMMM YYYY')}}</span>
                        </td>
                        <td>
                            <span class="badge badge-warning">{{\Carbon\Carbon::parse($Experience->end_date)->isoFormat('Do MMMM YYYY')}}</span>
                        </td>
                        <td>{{$Experience->countrie->name}}</td>
                        <td>
                            <button type="button" class="btn btn-danger-gradien btn-delete"><i class="fa fa-trash-o"></i></button>
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

<!--modal Add Experience-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <input type="hidden" name="employee_id" value="{{$Employee->id}}">
    <div class="modal fade bs-example-modal-xl" id="modaladdExperience" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Add New Experience')}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="col-md-10 offset-md-1">
                                <label class="col-form-label text-right">{{__('Employer')}}</label>
                                <input type="text" class="form-control mx" id="employer" name="employer" value="{{ old('employer') }}" autocomplete="employer" placeholder="Company Name" autofocus maxlength="50" required >
                            </div> <!-- end col -->
                            <br>
                            <div class="col-md-10 offset-md-1">
                                <label class="col-form-label text-right">{{__('Type of Business or Sector')}}</label>
                                <input type="text" class="form-control mx" id="sector" name="sector" value="{{ old('sector') }}" autocomplete="sector" placeholder="Type of Business" autofocus maxlength="25" required >
                            </div> <!-- end col -->
                            <br>
                            <div class="col-md-10 offset-md-1">
                                <label class="col-form-label text-right">{{__('Occupation or Position ')}}</label>
                                <input type="text" class="form-control mx" id="job_title" name="job_title" value="{{ old('job_title') }}" autocomplete="job_title" placeholder="" autofocus maxlength="50" required >
                            </div> <!-- end col -->
                        </div>
                        <div class="col-md-5">
                            <div class="col-md-10">
                                <label class="col-form-label text-right">{{__('From')}}</label>
                                <div class="input-group">
                                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                    <input type="text" class="form-control datem" id="start_date" name="start_date" value="{{ old('start_date') }}" autocomplete="start_date" placeholder="mm/dd/yyyy" autofocus readonly required >
                                </div>
                            </div> <!-- end col --> <br>
                            <div class="col-md-10">
                                <label class="col-form-label text-right">{{__('To')}}</label>
                                <div class="input-group">
                                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                    <input type="text" class="form-control datem" id="end_date" name="end_date" value="{{ old('end_date') }}" autocomplete="end_date" placeholder="mm/dd/yyyy" autofocus readonly required >
                                </div>
                            </div> <!-- end col --> <br>
                            <div class="col-md-10">
                                <label class="col-form-label text-right">{{__('Job Location')}}</label>
                                <select class="form-control select2" id="job_loc" name="job_loc" autofocus required>
                                    <option selected="selected" value="{{ old('job_loc') }}"></option>
                                    @foreach($Countries as $Countrie)
                                        <option value="{{$Countrie->id}}">{{$Countrie->name}}</option>
                                    @endforeach
                                </select>
                            </div> <!-- end col -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->
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

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $('.datem').datepicker();
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        $('#experiences-table').DataTable({"order":[0, 'desc'],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});

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
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/Experience",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdExperience").modal("hide");
                    $('#finish').attr('disabled', false);
                    $('#form').trigger("reset");
                    swal({
                        title: 'Success',
                        text: 'Experience has been added successfully.',
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
        $('#experiences-table tbody').on( 'click', '.btn-delete', function () {
            var table = $('#experiences-table').DataTable();
            var ExperienceID = table.row( $(this).parents('tr') ).data()[0];
            var row = table.row( $(this).parents('tr') );
            swal({
                title: 'Caution',
                text: 'Are you sure you want to delete this experience?',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary-gradien',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> No',
                preConfirm: function (){
                    $.ajax({
                        type: "GET",
                        url: " /DeleteExperiences/" + ExperienceID,
                        contentType: false,
                        success: function (data) {
                            swal({
                                title: 'Success',
                                text: 'The experience has been deleted.',
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
