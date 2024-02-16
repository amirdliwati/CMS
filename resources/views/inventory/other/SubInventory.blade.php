@extends('layouts.app')
@section('css')
    <title>{{__('Sub Inventory')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <style>.select2-container{z-index:100000;}</style>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Sub Inventory')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Sub Inventory')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modalSubInventory"><i class="fa fa-plus-square-o mr-2"></i>{{__('Create New Sub Inventory')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Sub Inventory')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="sub-inventory-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('ID')}}</th>
                        <th class="text-primary" >{{__('Sub Inventory Name')}}</th>
                        <th class="text-primary" >{{__('Sub Inventory Type')}}</th>
                        <th class="text-primary">{{__('Branch Name')}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($SubInventories as $SubInventory)
                    <tr style="text-align: center;">
                        <td>{{$SubInventory->id}}</td>
                        <td>{{$SubInventory->name}}</td>
                        <td>{{$SubInventory->type->name}}</td>
                        <td>{{$SubInventory->branch->name}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div> <!-- end row -->
    </div>
</div>

<!-- Large modal Add New Sub Inventory -->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <div class="modal fade bs-example-modal-lg" id="modalSubInventory" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">{{__('Create New Sub Inventory')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
	                    <div class="col-md-6">
	                    	<label for="name" class="col-form-label text-right">{{__('Sub Inventory Name')}}</label>
	                    	<input type="text" class="form-control mx" placeholder="~Store One" id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="30" autofocus required>
	                    </div>
                        <div class="col-md-3">
                            <label for="inventory_type_id" class="col-form-label text-right">{{__('Choose Type')}}</label>
                            <select class="form-control select2" id="inventory_type_id" name="inventory_type_id" autofocus required>
                                <option selected="selected" value="{{ old('inventory_type_id') }}"></option>
                                @foreach($InventoryTypes as $InventoryType)
                                    <option value="{{$InventoryType->id}}">{{$InventoryType->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
	                    <div class="col-md-3">
                            <label for="branch_id" class="col-form-label text-right">{{__('Choose Branch')}}</label>
                            <select class="form-control select2" id="branch_id" name="branch_id" autofocus required>
                                <option selected="selected" value="{{ old('branch_id') }}"></option>
                                @foreach($Branches as $Branch)
                                    <option value="{{$Branch->id}}">{{$Branch->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
	                </div> <!-- end row -->
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
    <!-- Plugins js -->
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
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
        $('#sub-inventory-table').DataTable({order:[0, 'desc'] , "columnDefs": [{ "visible": false, "targets": 0 } ]});

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
                url: "/ManageSubInventory",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modalSubInventory").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'The Sub Inventory has been created successfully.',
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
