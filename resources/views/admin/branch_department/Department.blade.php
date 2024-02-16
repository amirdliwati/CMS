@extends('layouts.app')
@section('css')
    <title>{{__('Departments')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Department Managment')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Departments')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
    {{-- <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modalDepartment">{{__('Add New')}}</button></li> --}}
@endsection
@section('content')
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Departments')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="departbl" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th>{{__('id')}}</th>
                        <th class="text-primary">{{__('Branch')}}</th>
                        <th class="text-primary">{{__('Department Code')}}</th>
                        <th class="text-primary">{{__('Department Name')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                    <tr style="text-align: center;">
                        <td>{{$department->id}}</td>
                        <td>{{$department->branch->name}}</td>
                        <td>{{$department->code}}</td>
                        <td>{{$department->name}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('home') }}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
    </div>
</div>

<!-- Large modal Add New Department -->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <div class="modal fade bs-example-modal-xl" id="modalDepartment" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">{{__('Add New Department')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
	                    <div class="col-md-3">
	                    	<label for="branch_id" class="col-form-label text-right">{{__('Choose Branch')}}</label>
	                    	<select class="form-control" id="branch_id" name="branch_id" autocomplete="branch_id" required>
                                <option selected="selected" value="{{ old('branch_id') }}">
                                    @if( old('branch_id') == "" ) {{__('Select Branch')}} @else
                                    {{ $branchs->find(old('branch_id'))->name}} @endif
                                </option>
                                @foreach($branches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
	                    </div>
	                    <div class="col-md-3">
	                    	<label for="code" class="col-form-label text-right">{{__('Department Code')}}</label>
	                    	<input type="text" class="form-control mx" placeholder="code" id="code" name="code" value="{{ old('code') }}" autocomplete="code" maxlength="6" autofocus required>
	                    </div>
	                    <div class="col-md-6">
	                    	<label for="name" class="col-form-label text-right">{{__('Department Name')}}</label>
	                    	<input type="text" class="form-control mx" placeholder="name" id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="50" autofocus required>
	                    </div>
	                </div>
                </div><!-- end modal-body -->
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
        $('#departbl').DataTable({order:[0, 'desc'] , "columnDefs": [{ "visible": false, "targets": 0 } ]});
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/Departments",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modalDepartment").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'The Department has been added successfully.',
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
