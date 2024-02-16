@extends('layouts.app')
@section('css')
    <title>{{__('Payrolls Employee')}}</title>
    <!-- Plugins css -->
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Manage Financial Employee')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Payrolls')}}</li>
    </ol>
@endsection
@section('bookmark')
    @if($Employee->currencies_id == Null)
        <li><button type="button" class="btn btn-outline-danger" data-toggle="modal"  data-target="#modaladdCurrency"><i class="mdi mdi-currency-usd mr-2"></i>{{__('Add Currency for Employee')}}</button></li>
    @else
        <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modaladdPayroll"><i class="fa fa-plus-square-o mr-2"></i>{{__('Create Payroll')}}</button></li>
    @endif
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Payrolls Employee')}} ({{$Employee->first_name}} {{$Employee->middle_name}} {{$Employee->last_name}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="payrolls-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Payroll ID')}}</th>
                    <th class="text-primary">{{__('Date')}}</th>
                    <th class="text-primary">{{__('Total')}}</th>
                    <th class="text-primary">{{__('Salary')}}</th>
                    <th class="text-primary">{{__('Notes')}}</th>
                    <th class="text-primary">{{__('Controller')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($Payrolls as $Payroll)
                <tr style="text-align: center;">
                    <td>{{$Payroll->id}}</td>
                    <td><span class="badge badge-info">{{\Carbon\Carbon::parse($Payroll->date)->isoFormat('Do MMMM YYYY')}}</span></td>
                    <td><span class="badge badge-success">{{$Employee->currencie->symbol}}</span> {{$Payroll->total}}</td>
                    <td><span class="badge badge-success">{{$Employee->currencie->symbol}}</span> {{$Payroll->salary->basic}}</td>
                    <td>{{$Payroll->notes}}</td>
                    <td>
                        @if(empty($Payroll->signature))
                            <a href="/EmployeeSignaturePayroll/{{$Payroll->id}}" target="_blank"><button type="button" class="btn btn-success-gradien"><i class="mdi mdi-pen"></i></button></a>

                            <button type="button" class="btn btn-danger-gradien" onclick="DeletePayroll('{{$Payroll->id}}')"><i class="fa fa-trash-o"></i></button>
                        @else
                            <a href="/PreviewPayroll/{{$Payroll->id}}" target="_blank"><button type="button" class="btn btn-primary-gradien"><i class="fa fa-eye"></i></button></a>

                            <button type="button" class="btn btn-dark-gradien" onclick="SwalMessage('Error Delete','Sorry you can not delete this Payroll.','error')"><i class="fa fa-trash-o"></i></button>
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

<!--modal Add Payroll-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <!-- Modal view -->
    <input type="hidden" id="employee_id" name="employee_id" value="{{$Employee->id}}">
    <input type="hidden" id="salary_id" name="salary_id" value="{{$Employee->salaries->where('end_date',Null)->first()->id}}">
    <input type="hidden" id="salary_basic" name="salary_basic" value="{{$Employee->salaries->where('end_date',Null)->first()->basic}}">
    <div class="modal fade" id="modaladdPayroll" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Create Payroll')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="date" class="col-form-label text-right">{{__('Payroll Date')}}</label>
                            <div class="input-group">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                <input type="text" class="form-control datem initDate" id="date" name="date" value="{{ old('date') }}" autocomplete="date" placeholder="mm/dd/yyyy" autofocus readonly required >
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-8">
                            <label for="notes" class="col-form-label text-right">{{__('Notes')}}</label>
                            <input type="text" class="form-control" id="notes" name="notes" placeholder="~Notes" value="{{ old('notes') }}" autocomplete="note">
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!-- end col -->
                <div class="modal-footer">
                    <button type="submit" id="finish" class="btn btn-success-gradien">{{__('Save')}}</button>
                    <button type="button" class="btn btn-secondary-gradien" data-dismiss="modal" onclick="this.form.reset()">{{__('Close')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!--modal Add Currency-->
<form id="form2" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <!-- Modal view -->
    <input type="hidden" id="employee_id2" name="employee_id2" value="{{$Employee->id}}">
    <div class="modal fade" id="modaladdCurrency" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Add Currency')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="currency" class="col-form-label text-right">{{__('Choose Currency')}}</label>
                            <select class="form-control select2" id="currency" name="currency" autofocus required>
                                @foreach($Currencies as $Currency)
                                    <option value="{{$Currency->id}}"> {{$Currency->name}} -> {{$Currency->code}} -> {{$Currency->symbol}} </option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                    </div><!--end row-->
                </div> <!--end modal-body-->
                <div class="modal-footer">
                    <button type="submit" id="finish2" class="btn btn-success-gradien">{{__('Save')}}</button>
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
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});

        $( ".select2" ).change(function() {
            if ($(this).val() == "") { $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'}); }
            else { $(this).siblings(".select2-container").css({'border': '','border-radius': ''}); }
        });

        $('#finish2').click( function() {
            $(".select2").each(function() {
                if ($(this).val() == "") {
                    $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'});
                }else {
                    $(this).siblings(".select2-container").css({'border': '','border-radius': ''});
                }
            });
        });

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        $('.initDate').val(today);

        $('#payrolls-table').DataTable({"columnDefs": [{"targets": [ 0 ], "visible": false, "searchable": false},]});
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/Payrolls",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdPayroll").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'Payroll has been created successfully.',
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
        function DeletePayroll (PayrollID) {
            swal({
                title: 'Caution',
                text: 'Are you sure you want to delete this Payroll?',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary-gradien',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> No',
                preConfirm: function (){
                    $.ajax({
                        type: "GET",
                        url: "/DeletePayroll/" + PayrollID,
                        contentType: false,
                        success: function (data) {
                            if(data == 'ok') {
                                swal({
                                    title: 'Success',
                                    text: 'Payroll has been deleted.',
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
            })
        };
    </script>

    <script type="text/javascript">
        $('#form2').on('submit', function(event) {
            $('#finish2').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/AddCurrencyEmployee",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdCurrency").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'Currency has been added successfully.',
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
                    $('#finish2').attr('disabled', false);
                }
            });
        });
    </script>
@endsection