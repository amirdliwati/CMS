@extends('layouts.app')
@section('css')
    <title>{{__('Deductions Employee')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Manage Financial Employee')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Deductions')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modaladdDeduction"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add New Deduction')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Deductions Employee')}} ({{$Employee->first_name}} {{$Employee->middle_name}} {{$Employee->last_name}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row form-material">
            <table id="deductions-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Deduction ID')}}</th>
                    <th class="text-primary">{{__('Date')}}</th>
                    <th class="text-primary">{{__('Amount')}}</th>
                    <th class="text-primary">{{__('Salary')}}</th>
                    <th class="text-primary">{{__('Payroll')}}</th>
                    <th class="text-primary">{{__('Notes/Reason')}}</th>
                    <th class="text-primary">{{__('Controller')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($Deductions as $Deduction)
                <tr style="text-align: center;">
                    <td>{{$Deduction->id}}</td>
                    <td><span class="badge badge-info">{{\Carbon\Carbon::parse($Deduction->date)->isoFormat('Do MMMM YYYY')}}</span></td>
                    <td><span class="badge badge-success">{{$Employee->currencie->symbol}}</span> {{$Deduction->amount}}</td>
                    <td><span class="badge badge-success">{{$Employee->currencie->symbol}}</span> {{$Deduction->salary->basic}}</td>
                    @if(empty($Deduction->payroll->date))
                    <td><span class="badge badge-danger">{{__('Not added to payroll yet')}}</span></td>
                    @else
                        <td><span class="badge badge-success">{{$Deduction->payroll->date}}</span></td>
                    @endif
                    <td>{{$Deduction->notes}}</td>
                    <td>
                        @if(empty($Deduction->payroll))
                            <button type="button" class="btn btn-danger-gradien" onclick="DeleteDeduction('{{$Deduction->id}}')"><i class="fa fa-trash-o"></i></button>
                        @else
                            <button type="button" class="btn btn-light-gradien" onclick="SwalMessage('Error Delete','Sorry you can not delete this Deduction.','error')"><i class="fa fa-trash-o"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div><!-- end row -->
    </div> <!-- end card-body -->
        <div class="card-footer">
            <a href="{{route('ManagementFinancialEmployees')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Employees')}}</button></a>
        </div>
</div> <!-- end card -->

<!--modal Add Deduction-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <!-- Modal view -->
    <input type="hidden" id="employee_id" name="employee_id" value="{{$Employee->id}}">
    <input type="hidden" id="salary_id" name="salary_id" value="{{$Employee->salaries->where('end_date',Null)->first()->id}}">
    <div class="modal fade bs-example-modal-lg" id="modaladdDeduction" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Add New Deduction')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="col-form-label text-right">{{__('Deduction Date')}}</label>
                            <div class="input-group">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                <input type="text" class="form-control datem initDate" id="date" name="date" value="{{ old('date') }}" autocomplete="date" placeholder="mm/dd/yyyy" autofocus readonly required >
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label for="amount" class="col-form-label text-right">{{__('Amount')}}</label>
                            <input class="form-control ts" type="number" step="0.01" name="amount" id="amount" value="0" placeholder="~0.0" autocomplete="amount" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-5">
                            <label for="notes" class="col-form-label text-right">{{__('Notes/Reason')}}</label>
                            <input type="text" class="form-control mx" id="notes" name="notes" placeholder="~Reason" value="{{ old('notes') }}" maxlength="350" autocomplete="note" required>
                        </div><!--end col-->
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
    <script src="{{ asset('js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/touchspin/touchspin.js') }}"></script>
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $('.datem').datepicker();
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $('.ts').TouchSpin({min: 0,max: 9999999999,step: 0.01,decimals: 2,boostat: 5,maxboostedstep: 10,buttondown_class: 'btn btn-info-gradien',buttonup_class: 'btn btn-primary-gradien'});
        $('#deductions-table').DataTable({"order":[0, 'desc'],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        $('.initDate').val(today);
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event){
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/Deductions",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdDeduction").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'The Deduction has been added successfully.',
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

    <script type="text/javascript">
        function DeleteDeduction (DeductionID) {
            swal({
                title: 'Caution',
                text: 'Are you sure you want to delete this Deduction?',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary-gradien',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> No',
                preConfirm: function (){
                    $.ajax({
                        type: "GET",
                        url: "/DeleteDeduction/" + DeductionID,
                        contentType: false,
                        success: function (data) {
                            if(data == 'ok') {
                                swal({
                                    title: 'Success',
                                    text: 'The Deduction has been deleted.',
                                    type: 'success',
                                    preConfirm: function (){location.reload();}
                                });
                            } else {
                                SwalMessage('Access Denied','You do not have permission.','error');
                            }
                        },
                        error: function(jqXHR){
                            if(jqXHR.status==0) {
                                SwalMessage('You are offline','Connection to the server has been lost. Please check your internet connection and try again.','error');
                            } else{
                                SwalMessage('Attention','Something went wrong. Please try again later.','warning');
                            }
                        }
                    });
                }
            });
        };
    </script>
@endsection
