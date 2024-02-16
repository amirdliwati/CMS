@extends('layouts.app')
@section('css')
    <title>{{__('Report Payrolls')}}</title>
    <!-- Plugins css -->
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Report Payrolls')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Payrolls')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-success border-3">
        <div class="row">
            <div class="col-md-3">
                <label for="employeess_account" class="col-form-label text-right">{{__('Choose Employee Account')}}</label>
                <select class="form-control select2" onchange ="SearchPayroll(this.value)" id="employeess_account" name="employeess_account" autofocus required>
                    <option selected="selected" value=""></option>
                    @foreach($Employees as $Employee)
                        <option value="{{$Employee->account->id}}">{{$Employee->account->account_number}} - {{$Employee->account->name}}</option>
                    @endforeach
                </select>
            </div> <!-- end col -->
            <div class="col-md-3">
                <label class="col-form-label text-right">{{__('Status')}}</label><br>
                <div class="checkbox checkbox-success form-check-inline">
                    <input type="checkbox" id="check-status1" value="Done" onchange="FilterStatus()" checked>
                    <label for="check-status1"> {{__('Done')}} </label>
                </div>
                <div class="checkbox checkbox-danger form-check-inline">
                    <input type="checkbox" id="check-status2" value="Not Done" onchange="FilterStatus()" checked>
                    <label for="check-status2"> {{__('Not Done')}} </label>
                </div>
            </div> <!-- end col -->
            <div class="col-md-3">
                <label for="payroll_date" class="col-form-label text-right">{{__('Payroll Date')}}</label>
                <div class="input-group">
                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                    <input type="text" class="form-control datem" id="payroll_date" placeholder="mm/dd/yyyy" readonly>
                </div><!-- input-group -->
            </div> <!-- end col -->
            <div class="col-md-3">
                <label for="serial_number" class="col-form-label text-right">{{__('Serial Number')}}</label>
                    <input class="form-control mx" type="search" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" placeholder="~search" autocomplete="serial_number" maxlength="25" autofocus required>
            </div> <!-- end col -->
        </div><!-- end row -->
            <br>
        <div class="row">
            <div class="col-md-4">
                <label for="employees_accounts" class="col-form-label text-right">{{__('Choose Employee Account')}}</label>
                <select class="form-control select2" id="employees_accounts" name="employees_accounts" autofocus required>
                    <option selected="selected" value="0">{{__('All')}}</option>
                    @foreach($Employees as $Employee)
                        <option value="{{$Employee->account->id}}">{{$Employee->account->account_number}} - {{$Employee->account->name}}</option>
                    @endforeach
                </select>
            </div> <!-- end col -->
            <div class="col-md-3">
                <label for="payrolls_from" class="col-form-label text-right">{{__('From Date')}}</label>
                <div class="input-group">
                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                    <input type="text" class="form-control datem" id="payrolls_from" placeholder="mm/dd/yyyy" readonly>
                </div><!-- input-group -->
            </div> <!-- end col -->
            <div class="col-md-3">
                <label for="payrolls_to" class="col-form-label text-right">{{__('To Date')}}</label>
                <div class="input-group">
                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                    <input type="text" class="form-control datem" id="payrolls_to" placeholder="mm/dd/yyyy" readonly>
                </div><!-- input-group -->
            </div> <!-- end col -->
            <div class="col-md-2">
                <button type="button" class="btn btn-success-gradien mt-5" style="width:100%;" onclick="Report()"><i class="mdi mdi-arrow-right-bold-hexagon-outline mr-2"></i>{{__('Go')}}</button>
            </div> <!-- end col -->
        </div><!-- end row -->
        @include('layouts/button_card')
    </div>
    <div class="card-body">
        <div class="row">
            <table id="payrolls-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Payroll ID')}}</th>
                    <th class="text-primary">{{__('Date')}}</th>
                    <th class="text-primary">{{__('Total Payroll')}}</th>
                    <th class="text-primary">{{__('Salary')}}</th>
                    <th class="text-primary">{{__('Employee')}}</th>
                    <th class="text-primary">{{__('Notes')}}</th>
                    <th class="text-primary">{{__('Employee Issued')}}</th>
                    <th class="text-primary">{{__('Status')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                </tbody>
            </table>
        </div><!-- end row -->
    </div> <!-- end card-body -->
        <div class="card-footer">
            <div class="float-right d-flex justify-content-between">
                <label for="credit" class="col-form-label text-right mr-2">{{__('Credit')}}</label>
                <input type="text" class="form-control" name="credit" id="credit" value="" disabled>
            </div><!-- end div -->
            <div class="float-right d-flex justify-content-between">
                <label for="debit" class="col-form-label text-right mr-2">{{__('Debit')}}</label>
                <input type="text" class="form-control mr-4" name="debit" id="debit" value="" disabled>
            </div><!-- end div -->
            <a href="{{ route('home') }}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
        </div>
</div> <!-- end card -->
@endsection

@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/moment/moment.js') }}"></script>
    <script src="{{ asset('js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>

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
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        $('.datem').datepicker();

        var table = $('#payrolls-table').DataTable({buttons: ['copy','excel','colvis'],"order": [[ 0, "desc" ]]});
        table.buttons().container().prependTo('#payrolls-table_length');
        table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');
    </script>

    <!-- Search Employee By ID -->
    <script type="text/javascript">
        function SearchPayroll(employeeAccountID) {
            $.ajax({
                type: "GET",
                url: "{{url('ReportSearchPayrolls')}}",
                data: {'employee_account': employeeAccountID},
                contentType: 'application/json',
                cache:false,
                success: function (data) {
                    if (data['response'] == ''){
                        $('#payrolls-table').DataTable().clear().draw();
                        SwalMessage('Sorry!','No data available for this employee.','info');
                    }
                    else {
                        DataTable(data['response']);
                        $('#credit').val(data['credit'] +'  $');
                        $('#debit').val(data['debit'] +'  $');
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
        };
    </script>

    <!-- Report -->
    <script type="text/javascript">
        function Report(){
            $.ajax({
                type: "GET",
                url: "{{url('ReportsPayrolls')}}/"+$('#employees_accounts').val()+"/"+$('#payrolls_from').val()+"/"+$('#payrolls_to').val(),
                contentType: 'application/json',
                cache:false,
                success: function (data) {
                    if (data['response'] == ''){
                        $('#payrolls-table').DataTable().clear().draw();
                        SwalMessage('Sorry!','No data available for this employee.','info');
                    }
                    else {
                        DataTable(data['response']);
                        $('#credit').val(data['credit'] +'  $');
                        $('#debit').val(data['debit'] +'  $');
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
        };
    </script>

    <!-- Table Payrolls -->
    <script type="text/javascript">
        function DataTable(data){
            var table = $('#payrolls-table').DataTable( {
            buttons: ['copy','excel','colvis'],
            "destroy": true,
            "data": data,
            "columns": [
                {"data": "id" },
                {"data": "payroll_date" },
                {"data": "total_payroll" },
                {"data": "salary" },
                {"data": "employee_name" },
                {"data": "notes" },
                {"data": "employee_name_issued" },
                {"data": "status" }],
            "order": [[ 0, "desc" ]],
            "columnDefs": [{"targets": [ 1 ],"render": function ( data, type, row, meta ) {
                                return '<span class="badge badge-success">'+moment(data).format('Do MMMM YYYY')+'</span>';
                            }},
                            {"targets": [ 5 ],"render": function ( data, type, row, meta ) {
                            if (data === null){ return 'N/A'; }
                            else { return data; }
                            }},
                            {"targets": [ 7 ],"render": function ( data, type, row, meta ) {
                            if (data === null){
                                return '<span class="badge badge-danger">Not Done</span>'; }
                            else {
                                return '<span class="badge badge-success">Done</span>';}
                            }},
                        ]
        });

        table.buttons().container().prependTo('#payrolls-table_length');
        table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');
        }
    </script>

    <!-- Search Offline -->
    <script type="text/javascript">
        $('#serial_number').on( 'keyup', function () {$('#payrolls-table').DataTable().columns( 0 ).search( this.value ).draw();} );

        $('#payroll_date').on( 'change', function () {
            if (!$(this).val()) {
                $('#payrolls-table').DataTable().columns( 1 ).search('').draw();
            }
            else { $('#payrolls-table').DataTable().columns( 1 ).search( moment(this.value).format('Do MMMM YYYY') ).draw(); }
        } );

        function FilterStatus() {
            let query = '';
            for (let i = 1; i < 3; i++) {
                if ($('#check-status'+i).prop("checked") == true) {
                    query = query + "|" + $('#check-status'+i).val();
                }
            }

            $('#payrolls-table').DataTable().columns( 7 ).search( query.substring(1), true, false).draw();
        }
    </script>
@endsection
