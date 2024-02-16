@extends('layouts.app')
@section('css')
    <title>{{__('Management Financial Employees')}}</title>
    <!-- Plugins css -->
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
    <style>.select2-container{z-index:100000;}</style>
@endsection
@section('breadcrumb')
    <h3>{{__('Management Financial Employees')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employees Finance')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Management Financial Employees')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="employees-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Employee ID')}}</th>
                    <th class="text-primary">{{__('Employee Name')}}</th>
                    <th class="text-primary">{{__('Department')}}</th>
                    <th class="text-primary">{{__('Status')}}</th>
                    <th class="text-primary">{{__('E-mail')}}</th>
                    <th class="text-primary">{{__('Hire Date')}}</th>
                    <th class="text-primary">{{__('Job Types')}}</th>
                    <th class="text-primary">{{__('Position')}}</th>
                    <th class="text-primary">{{__('Controller')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($Employees as $Employee)
                <tr style="text-align: center;">
                    <td>{{$Employee->id}}</td>
                    <td>{{$Employee->first_name}} {{$Employee->middle_name}} {{$Employee->last_name}}</td>
                    <td>{{$Employee->position->department->name}}</td>

                    @switch($Employee->status_id)
                        @case(1)
                            <td><span class="badge badge-success">{{$Employee->job_status->name}}</span></td>
                        @break
                        @case(2)
                            <td><span class="badge badge-warning">{{$Employee->job_status->name}}</span></td>
                        @break
                        @case(3)
                            <td><span class="badge badge-info">{{$Employee->job_status->name}}</span></td>
                        @break
                        @case(4)
                            <td><span class="badge badge-danger">{{$Employee->job_status->name}}</span></td>
                        @break
                        @case(5)
                            <td><span class="badge badge-warning">{{$Employee->job_status->name}}</span></td>
                        @break
                        @case(6)
                            <td><span class="badge badge-danger">{{$Employee->job_status->name}}</span></td>
                        @break
                    @endswitch

                    <td>{{$Employee->naya_email}}</td>
                    <td>
                        <span class="badge badge-info">{{\Carbon\Carbon::parse($Employee->hire_date)->isoFormat('Do MMMM YYYY')}}</span>
                    </td>

                    @switch($Employee->job_type_id)
                        @case(1)
                            <td><span class="badge badge-success">{{$Employee->job_type->name}}</span></td>
                        @break
                        @case(2)
                            <td><span class="badge badge-info">{{$Employee->job_type->name}}</span></td>
                        @break
                        @case(3)
                            <td><span class="badge badge-warning">{{$Employee->job_type->name}}</span></td>
                        @break
                    @endswitch
                    <td>{{$Employee->position->name}}</td>
                    <td>
                        @if($Employee->currencies_id == Null)
                            <button type="button" class="btn btn-danger-gradien btn-xs" data-toggle="modal"  data-target="#modaladdCurrency" onclick="EmployeeID({{$Employee->id}})">{{__('Add Currency')}}</button>
                        @else
                            <div class="btn-group mb-2 mb-md-0">
                                <button class="btn btn-primary-gradien dropdown-toggle" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('Select Option')}}</button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">

                                    <a class="dropdown-item" href="/Payrolls/{{$Employee->id}}"><i class="mdi mdi-currency-usd text-primary mr-2"></i>{{__('Payrolls')}}</a>

                                    <a type="button" class="dropdown-item" onclick="GetEmployeeLeaves({{$Employee->id}})"><i class="mdi mdi-account-arrow-right-outline font-danger mr-2"></i>{{__('Leaves')}}</a>

                                    <a class="dropdown-item" href="/Tasks/{{$Employee->id}}"><i class="mdi mdi-check-box-outline text-success mr-2"></i>{{__('Tasks')}}</a>

                                    <a class="dropdown-item" href="/Deductions/{{$Employee->id}}"><i class="mdi mdi-account-minus-outline text-danger mr-2"></i>{{__('Deductions')}}</a>

                                    <a class="dropdown-item" href="/Increments/{{$Employee->id}}"><i class="mdi mdi-account-plus-outline text-success mr-2"></i>{{__('Increments')}}</a>

                                    <a class="dropdown-item" href="/Loans/{{$Employee->id}}"><i class="mdi mdi-account-convert text-warning mr-2"></i>{{__('Loans')}}</a>

                                    @if (Auth::user()->roles->whereIn('title',['Admin_role','Financial_role'])->first()->permissions->where('title','ChangeSalary') != '[]')
                                        <a type="button" class="dropdown-item" onclick="ChangeSalary({{$Employee->id}})"><i class="mdi mdi-currency-usd text-primary mr-2"></i>{{__('Change Salary')}}</a>
                                    @endif

                                </div>
                            </div><!-- /btn-group -->
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div><!-- end row -->
    </div> <!-- end card-body -->
        <div class="card-footer">
            <a href="{{ route('home') }}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
        </div>
</div> <!-- end card -->

<!--modal Add Currency-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
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
                                <option selected="selected" value="{{ old('currency') }}"></option>
                                @foreach($Currencies as $Currency)
                                    <option value="{{$Currency->id}}"> {{$Currency->name}} -> {{$Currency->code}} -> {{$Currency->symbol}} </option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
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

<!-- modal Leaves -->
<div class="modal fade bd-example-modal-xl" id="modalLeaves" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="viewModel">{{__('Leaves')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="leaves-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                            <thead>
                                <tr style="text-align: center;">
                                    <th class="text-primary">{{__('Leave ID')}}</th>
                                    <th class="text-primary">{{__('Date')}}</th>
                                    <th class="text-primary">{{__('Start Date')}}</th>
                                    <th class="text-primary">{{__('End Date')}}</th>
                                    <th class="text-primary">{{__('Duration')}}</th>
                                    <th class="text-primary">{{__('Is Paid')}}</th>
                                    <th class="text-primary">{{__('Status')}}</th>
                                    <th class="text-primary">{{__('Type Leave')}}</th>
                                    <th class="text-primary">{{__('Notes/Reason')}}</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                            </tbody>
                        </table>
                    </div>
                </div><!--end row-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-gradien" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/moment/moment.js') }}"></script>
    <script src="{{ asset('js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
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

        $('#employees-table').DataTable({"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});

        function EmployeeID(EmployeeID) { $('#employee_id2').val(EmployeeID); }
    </script>

    <!-- Change Salary -->
    <script type="text/javascript">
        function ChangeSalary(EmployeeID) {
            // CSRF Token
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Change Salary!',
                text: 'Insert new salary into the text box...',
                input: 'number',
                type: 'info',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Confirm',
                showLoaderOnConfirm: true,
                confirmButtonClass: 'btn btn-primary-gradien',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> Cancel',
                preConfirm: function (salary) {
                    return new Promise(function () {
                        setTimeout(function () {
                            $.ajax({
                                type: "POST",
                                url: "/ChangeSalary/"+EmployeeID,
                                data: {_token: CSRF_TOKEN,'salary' : salary},
                                success: function (response) {
                                    SwalMessage('Success','Employee salary has been changed successfully.','success');
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
                        }, 2000)
                    })
                },
                allowOutsideClick: false
            })
        }
    </script>

    <!-- Add Currency Employee -->
    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
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
                    if(data == 'ok') {
                        SwalMessage('Success','Currency has been added successfully.','success');
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
                    $('#finish').attr('disabled', false);
                }
            });
        });
    </script>

    <!-- Get Employee Leaves -->
    <script type="text/javascript">
        function GetEmployeeLeaves(EmployeeID) {
            $.ajax({
                type: "GET",
                url: "/GetEmployeeLeaves",
                data: {'EmployeeID' : EmployeeID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'You do not have permission') {
                        SwalMessage('Access Denied','You do not have permission.','error');
                    }
                    else {
                        $('#leaves-table').DataTable( {
                        "destroy": true,
                        "data": data,
                        "columns": [
                            { "data": "id" },
                            { "data": "date" },
                            { "data": "start_date" },
                            { "data": "end_date" },
                            { "data": "duration_leave" },
                            { "data": "ispaid" },
                            { "data": "approved_by" },
                            { "data": "type" },
                            { "data": "notes" }],
                        "order": [[ 0, "desc" ]],
                        "columnDefs": [{"targets": [ 0 ],"visible": false},
                                        {"targets": [ 1 ],"render": function ( data, type, row, meta ) {
                                                return '<span class="badge badge-info"> ' + moment(data).format('Do MMMM YYYY') + '</span>';
                                        }},
                                        {"targets": [ 2 ],"render": function ( data, type, row, meta ) {
                                                return '<span class="badge badge-success"> ' + moment(data).format('Do MMMM YYYY') + '</span>';
                                        }},
                                        {"targets": [ 3 ],"render": function ( data, type, row, meta ) {
                                                return '<span class="badge badge-warning"> ' + moment(data).format('Do MMMM YYYY') + '</span>';
                                        }},
                                        {"targets": [ 4 ],"render": function ( data, type, row, meta ) {
                                            if (data['start_date'] != data['end_date']) {
                                                return '<span class="badge badge-info"> '+data['duration']+' Day/s</span>';
                                            }
                                            else {
                                                return '<span class="badge badge-primary"> '+data['duration']+' Hour/s</span>';
                                            }

                                        }},
                                        {"targets": [ 5 ],"render": function ( data, type, row, meta ) {
                                            if (data == 0) {
                                                return 'No';
                                            }
                                            else {
                                                return 'Yes';
                                            }

                                        }},
                                        {"targets": [ 6 ],"render": function ( data, type, row, meta ) {
                                            if (data === null) {
                                                return '<span class="badge badge-danger">Not Approved</span>';
                                            }
                                            else {
                                                return '<span class="badge badge-success">Approved</span>';
                                            }

                                        }}
                                    ]
                        })

                        $("#modalLeaves").modal("show");

                    }
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
        };
    </script>
@endsection
