@extends('layouts.app')
@section('css')
    <title>{{__('Job Positions')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
    <style>.select2-container{z-index:100000;}</style>
@endsection
@section('breadcrumb')
    <h3>{{__('Job Positions')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Job Positions')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modaladdPositions"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add New Position')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Job Positions')}}</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="positions-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('Branch')}}</th>
                        <th class="text-primary">{{__('Department')}}</th>
                        <th class="text-primary">{{__('code')}}</th>
                        <th class="text-primary">{{__('Position')}}</th>
                        <th class="text-primary">{{__('Description')}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($positions as $position)
                    <tr style="text-align: center;">
                        <td>{{$position->department->branch->name}}</td>
                        <td>{{$position->department->name}}</td>
                        <td>{{$position->code}}</td>
                        <td>{{$position->name}}</td>
                        <td>{{$position->description}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--modal Add Positions-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <!-- Modal view -->
    <div class="modal fade" id="modaladdPositions" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Add New Position')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
	                    	<label for="department_id" class="col-form-label text-right">{{__('Choose Department')}}</label>
	                    	<select class="form-control select2 mb-3" id="department_id" name="department_id" autofocus required>
                                <option selected="selected" value="{{ old('department_id') }}"></option>
                                @foreach ($departments as $department)
                                    <option value="{{$department->id}}">{{$department->name}} - {{$department->branch->code}}</option>
                                @endforeach
                            </select>
	                    </div>
	                    <div class="col-md-2">
	                    	<label for="code" class="col-form-label text-right">{{__('Position Code')}}</label>
	                    	<input type="text" class="form-control mx" placeholder="code" id="code" name="code" value="{{ old('code') }}" autocomplete="code" maxlength="10" autofocus required>
	                    </div>
	                    <div class="col-md-6">
	                    	<label for="name" class="col-form-label text-right">{{__('Position Title')}}</label>
	                    	<input type="text" class="form-control mx" placeholder="name" id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="50" autofocus required>
	                    </div>
                    </div><!--end row-->
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
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        var table = $('#positions-table').DataTable({"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});

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
                url: "/Positions",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdPositions").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'The Position has been added successfully.',
                        type: 'success',
                        preConfirm: function (){location.reload();}
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
@endsection
