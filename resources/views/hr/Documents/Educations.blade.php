@extends('layouts.app')
@section('css')
    <title>{{__('Educations')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
    <style>.select2-container{z-index:100000;}</style>
@endsection
@section('breadcrumb')
    <h3>{{__('Employee Educations')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Educations')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modaladdEducation"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add New Education')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Employee Educations')}} ({{$Employee->first_name}} {{$Employee->last_name}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="educations-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('ID Education')}}</th>
                        <th class="text-primary">{{__('Level of Education')}}</th>
                        <th class="text-primary">{{__('Official name of School / University / Institution')}}</th>
                        <th class="text-primary">{{__('Study Location')}}</th>
                        <th class="text-primary">{{__('Programme Name')}}</th>
                        <th class="text-primary">{{__('Subfield')}}</th>
                        <th class="text-primary">{{__('Start of Studies')}}</th>
                        <th class="text-primary">{{__('Graduation Date')}}</th>
                        <th class="text-primary">{{__('GPA / Average grade')}}</th>
                        <th class="text-primary">{{__('Controller')}}</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    @foreach ($Educations as $Education)
                    <tr style="text-align: center;">
                        <td>{{$Education->id}}</td>
                        <td>
                            @if($Education->level == 1 )
                                {{ __('Secondary Education')}}
                                @elseif($Education->level == 2 )
                                {{ __('Bachelor Degree') }}
                                @elseif($Education->level == 3 )
                                {{ __('Master Degree') }}
                                @elseif($Education->level == 4 )
                                {{ __('Doctoral Degree') }}
                            @endif
                        </td>
                        <td>{{$Education->school}}</td>
                        <td>{{$Education->countrie->name}}</td>
                        <td>{{$Education->degree}}</td>
                        <td>{{$Education->field}}</td>
                        <td>
                            <span class="badge badge-info">{{\Carbon\Carbon::parse($Education->start)->isoFormat('Do MMMM YYYY')}}</span>
                        </td>
                        <td>
                            <span class="badge badge-success">{{\Carbon\Carbon::parse($Education->end)->isoFormat('Do MMMM YYYY')}}</span>
                        </td>
                        <td>{{$Education->grade}}</td>
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

<!--modal Add Education-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <input type="hidden" name="employee_id" value="{{$Employee->id}}">
    <div class="modal fade bs-example-modal-xl" id="modaladdEducation" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Add New Education')}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="col-form-label text-right">{{__('Level of Education')}}</label>
                            <select class="form-control"  id="level" name="level" autofocus required>
                                <option selected="selected" disabled selected value="{{ old('level') }}">{{__('Select an option')}}</option>
                                <option value="1">{{__('Secondary Education')}}</option>
                                <option value="2">{{__('Bachelor Degree')}}</option>
                                <option value="3">{{__('Master Degree')}}</option>
                                <option value="4">{{__('Doctoral Degree')}}</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-5">
                            <label class="col-form-label text-right">{{__('Official name of School / University / Institution')}}</label>
                            <input type="text" class="form-control mx" id="school" name="school" value="{{ old('school') }}" autocomplete="school" placeholder="School / University" autofocus maxlength="25" required >
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label class="col-form-label text-right">{{__('Study Location')}}</label>
                            <select class="form-control select2" id="location" name="location" autofocus required>
                                <option selected="selected" value="{{ old('location') }}"></option>
                                @foreach($Countries as $Countrie)
                                    <option value="{{$Countrie->id}}">{{$Countrie->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-form-label text-right">{{__('Programme Name')}}</label>
                            <input type="text" class="form-control mx" id="degree" name="degree" value="{{ old('degree') }}" autocomplete="degree" placeholder="Programme" autofocus maxlength="25" required >
                        </div> <!-- end col -->
                        <div class="col-md-6">
                            <label class="col-form-label text-right">{{__('Subfield')}}</label>
                            <input type="text" class="form-control mx" id="field" name="field" value="{{ old('field') }}" autocomplete="field" placeholder="Subfield" autofocus maxlength="25" required >
                        </div> <!-- end col -->
                    </div> <!-- end row --> <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-form-label text-right">{{__('Start of Studies')}}</label>
                            <div class="input-group">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                <input type="text" class="form-control datem" id="start" name="start" value="{{ old('start') }}" autocomplete="start" placeholder="mm/dd/yy" autofocus readonly required >
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label class="col-form-label text-right">{{__('Graduation Date')}}</label>
                            <div class="input-group">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                <input type="text" class="form-control datem " id="end" name="end" value="{{ old('end') }}" autocomplete="end" placeholder="mm/dd/yy" autofocus readonly required >
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label class="col-form-label text-right">{{__('GPA / Average grade')}}</label>
                            <input type="text" class="form-control mx" id="grade" name="grade" value="{{ old('grade') }}" autocomplete="grade" placeholder="Grade" autofocus maxlength="5" required >
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
        $('#educations-table').DataTable({"order":[0, 'desc'],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});

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
                url: "/Education",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdEducation").modal("hide");
                    $('#finish').attr('disabled', false);
                    $('#form').trigger("reset");
                    swal({
                        title: 'Success',
                        text: 'Education has been added successfully.',
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
        $('#educations-table tbody').on( 'click', '.btn-delete', function () {
            var table = $('#educations-table').DataTable();
            var EducationID = table.row( $(this).parents('tr') ).data()[0];
            var row = table.row( $(this).parents('tr') );
            swal({
                title: 'Caution',
                text: 'Are you sure you want to delete this education?',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary-gradien',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> No',
                preConfirm: function (){
                    $.ajax({
                        type: "GET",
                        url: " /DeleteEducations/" + EducationID,
                        contentType: false,
                        success: function (data) {
                            swal({
                                title: 'Success',
                                text: 'The education has been deleted.',
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
