@extends('layouts.app')
@section('css')
    <title>{{__('Management Accounts')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
    <style>.select2-container{z-index:100000;}</style>
@endsection
@section('breadcrumb')
    <h3>{{__('Management Accounts')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Management Accounts')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Management Accounts')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <ul class="nav nav-pills mb-0 nav-justified" id="pills-tab" role="tablist">
            <li class="nav-item btn-round">
                <a class="nav-link active" id="NotCompleted-a" data-toggle="pill" href="#NotCompleted-tab" aria-selected="true"><b id="NotCompleted-b"></b>{{__('Not Has Account')}}</a>
            </li>
            <li class="nav-item btn-round">
                <a class="nav-link" id="Completed-a" data-toggle="pill" href="#Completed-tab"  aria-selected="false"><b id="Completed-b"></b>{{__('Has Account')}}</a>
            </li>
        </ul>
        <div class="tab-content mt-4" id="form-tabContent">
            <div class="tab-pane fade show active" id="NotCompleted-tab"> <br>
                <table id="accounts-not-completed-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                    <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('ID')}}</th>
                        <th class="text-primary">{{__('Name')}} ({{__('Employee')}})</th>
                        <th class="text-primary">{{__('Type of Account')}}</th>
                        <th class="text-primary">{{__('Controller')}}</th>
                    </tr>
                    </thead>
                    <tbody style="text-align: center;">
                        @foreach ($Employees->where('account_id',NULL) as $Employee)
                        <tr style="text-align: center;">
                            <td>{{$Employee->id}}</td>
                            <td>{{$Employee->first_name}} {{$Employee->last_name}}</td>
                            <td>Employee</td>
                            <td>
                                <button type="button" class="btn btn-warning-gradien" data-toggle="modal"  data-target="#modalChangeAccount" onclick="SetParameterAccount('{{$Employee->id}}','Employee')"><i class="mdi mdi-circle-edit-outline"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- end Not Completed -->

            <div class="tab-pane fade mt-4" id="Completed-tab"> <br>
                <table id="accounts-completed-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                    <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('ID')}}</th>
                        <th class="text-primary">{{__('Name')}} ({{__('Employee')}})</th>
                        <th class="text-primary">{{__('Type of Account')}}</th>
                        <th class="text-primary">{{__('Account Number')}}</th>
                        <th class="text-primary">{{__('Account Name')}}</th>
                        <th class="text-primary">{{__('Controller')}}</th>
                    </tr>
                    </thead>
                    <tbody style="text-align: center;">
                        @foreach ($Employees->where('account_id','<>',NULL) as $Employee)
                        <tr style="text-align: center;">
                            <td>{{$Employee->id}}</td>
                            <td>{{$Employee->first_name}} {{$Employee->last_name}}</td>
                            <td>Employee</td>
                            <td>{{$Employee->account->account_number}}</td>
                            <td>{{$Employee->account->name}}</td>
                            <td>
                                <button type="button" class="btn btn-warning-gradien" data-toggle="modal"  data-target="#modalChangeAccount" onclick="SetParameterAccount('{{$Employee->id}}','Employee')"><i class="mdi mdi-circle-edit-outline"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- end Completed -->
        </div><!-- /.tab-content -->
    </div> <!-- end card-body -->
        <div class="card-footer">
            <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
        </div>
</div> <!-- end card -->

<!-- Modal Change Account -->
<form id="form" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="parameter_id" name="parameter_id" value="">
    <input type="hidden" id="parameter_type" name="parameter_type" value="">
    <div class="modal fade bs-example-modal-md" id="modalChangeAccount" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Modify Account')}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="container col-md-12">
                            <label for="account_id" class="col-form-label text-right">{{__('Account Name')}}</label>
                            <select class="form-control select2" id="account_id" name="account_id" autofocus required>
                                @foreach($Accounts->where('type',0) as $Account)
                                    <option value="{{$Account->id}}">{{$Account->account_number}} - {{$Account->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div><!--end row-->
                </div>
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
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.autoFill.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});

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

        var table = $('#accounts-not-completed-table').DataTable({
        buttons: ['copy','excel','colvis'],"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
        table.buttons().container().prependTo('#accounts-not-completed-table_length');
        table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');

        var table2 = $('#accounts-completed-table').DataTable({ buttons: ['copy','excel','colvis'],"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ], "visible": false,"searchable": false},]});
        table2.buttons().container().prependTo('#accounts-completed-table_length');
        table2.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');

        function SetParameterAccount(ParameterID , ParameterType) {
            $("#parameter_id").val(ParameterID);
            $("#parameter_type").val(ParameterType);
        }
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/ChangeAccount",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modalChangeAccount").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'Account has been changed successfully.',
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
