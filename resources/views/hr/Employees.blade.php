@extends('layouts.app')
@section('css')
    <title>{{__('Employee Management')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
    <style>.select2-container{z-index:100000;}</style>
@endsection
@section('breadcrumb')
    <h3>{{__('Employee Management')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Management')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Page Content-->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Employee Management')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12" >
                <table id="employees-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                    <thead>
                        <tr style="text-align: center;">
                            <th class="text-primary">{{__('ID')}}</th>
                            <th class="text-primary">{{__('Full Name')}}</th>
                            <th class="text-primary">{{__('Hire Date')}}</th>
                            <th class="text-primary">{{__('Job Type')}}</th>
                            <th class="text-primary">{{__('Job Status')}}</th>
                            <th class="text-primary">{{__('Departments')}}</th>
                            <th class="text-primary">{{__('Line Manager')}}</th>
                            @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','ManageUsers') != '[]')
                                <th class="text-primary">{{__('Permissions')}}</th>
                            @endif
                            <th class="text-primary">{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($Users->where('id','>',0)->unique('id') as $User)
                        <tr style="text-align: center;">
                            <td>{{$User->employee->id}}</td>
                            <td>
                                @if( $User->employee->prefix == 1 ) {{ __('Mr.') }}
                                    @elseif($User->employee->prefix == 2 ) {{ __('Ms.')}}
                                    @elseif($User->employee->prefix == 3 ) {{ __('Mrs.') }}
                                    @elseif($User->employee->prefix == 4 ) {{ __('Dr.') }}
                                    @elseif($User->employee->prefix == 5 ) {{ __('Eng.') }}
                                @endif
                                {{$User->employee->first_name}} {{$User->employee->middle_name}} {{$User->employee->last_name}}
                            </td>
                            <td><span class="badge badge-warning">{{\Carbon\Carbon::parse($User->employee->hire_date)->isoFormat('Do MMMM YYYY')}}</span></td>

                            @switch($User->employee->job_type_id)
                                @case(1)
                                    <td><span class="badge badge-info">{{$User->employee->job_type->name}}</span></td>
                                @break
                                @case(2)
                                    <td><span class="badge badge-success">{{$User->employee->job_type->name}}</span></td>
                                @break
                                @case(3)
                                    <td><span class="badge badge-warning">{{$User->employee->job_type->name}}</span></td>
                                @break
                            @endswitch

                            @switch($User->employee->status_id)
                                @case(1)
                                    <td><span class="badge badge-success">{{$User->employee->job_status->name}}</span></td>
                                @break
                                @case(2)
                                    <td><span class="badge badge-warning">{{$User->employee->job_status->name}}</span></td>
                                @break
                                @case(3)
                                    <td><span class="badge badge-info">{{$User->employee->job_status->name}}</span></td>
                                @break
                                @case(4)
                                    <td><span class="badge badge-danger">{{$User->employee->job_status->name}}</span></td>
                                @break
                                @case(5)
                                    <td><span class="badge badge-warning">{{$User->employee->job_status->name}}</span></td>
                                @break
                                @case(6)
                                    <td><span class="badge badge-danger">{{$User->employee->job_status->name}}</span></td>
                                @break
                            @endswitch

                            <td>
                                @foreach($User->roles as $Role)
                                    {{$loop->iteration }} - {{$Role->blug}} <br>
                                @endforeach
                            </td>

                            <td>{{$User->employee->line1->first_name}} {{$User->employee->line1->last_name}} - {{$User->employee->line1->position->code}}</td>

                            @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','ManageUsers') != '[]')
                                @if (Auth::user()->id == $User->id)
                                    <td style="text-align: center;">
                                        <button type="button" class="btn btn-warning-gradien" onclick="SwalMessage('Error','Sorry you can not modify your permissions.','error')"><i class="fa fa-edit mr-1"></i>{{__('Modify')}}</button>
                                    </td>
                                @else
                                    @if(empty($User->id))
                                        <td style="text-align: center;">
                                            <button type="button" class="btn btn-warning-gradien" onclick="SwalMessage('Error','Sorry you can not modify permissions for this employee because dose not have role in system.','error')"><i class="fa fa-edit mr-1"></i>{{__('Modify')}}</button>
                                        </td>
                                    @else
                                        <td style="text-align: center;">
                                            <button  type="button" class="btn btn-warning-gradien" onclick="GetEmployeePermissions({{$User->id}})"><i class="fa fa-edit mr-1"></i>{{__('Modify')}}</button>
                                        </td>
                                    @endif
                                @endif
                            @endif

                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary-gradien dropdown-toggle" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('Select Option')}}</button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">

                                    @if (Auth::user()->employee_id == $User->employee->id)
                                        <a class="dropdown-item" href="/EmployeeProfile/{{$User->employee->id}}"><i class="fa fa-eye font-primary mr-2"></i>{{__('View Profile')}}</a>
                                    </div>
                                </div><!-- /btn-group -->
                            </td>
                        </tr>
                                    @continue
                                    @endif

                                        <a class="dropdown-item" href="/EmployeeProfile/{{$User->employee->id}}"><i class="fa fa-eye font-primary mr-2"></i>{{__('View Profile')}}</a>

                                        @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','EditEmp') != '[]')
                                        <a class="dropdown-item" href="/EditEmployee/{{$User->employee->id}}"><i class="fa fa-edit font-warning mr-2"></i>{{__('Edit Profile')}}</a>
                                        @endif

                                        <div class="dropdown-divider"></div>

                                        @if (!empty(Auth::user()->roles->where('title','HR_role')->first()))
                                        <a class="dropdown-item" href="/Leaves/{{$User->employee->id}}"><i class="mdi mdi-account-arrow-right-outline font-danger mr-2"></i>{{__('Leaves')}}</a>
                                        @else
                                        <a type="button" class="dropdown-item" onclick="GetEmployeeLeaves({{$User->employee->id}})"><i class="mdi mdi-account-arrow-right-outline font-danger mr-2"></i>{{__('Leaves')}}</a>
                                        @endif

                                        @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','EditEmp') != '[]')
                                        <a class="dropdown-item" href="/LegalDocs/{{$User->employee->id}}"><i class="mdi mdi-file-document-box-multiple-outline font-success mr-2"></i>{{__('Legal Docs')}}</a>
                                        @endif

                                        @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','EditEmp') != '[]')
                                        <a class="dropdown-item" href="/Experience/{{$User->employee->id}}"><i class="fa fa-folder-open-o font-info mr-2"></i>{{__('Experiences')}}</a>
                                        @endif

                                        @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','EditEmp') != '[]')
                                        <a class="dropdown-item" href="/Education/{{$User->employee->id}}"><i class="fa fa-graduation-cap font-primary mr-2"></i>{{__('Educations')}}</a>
                                        @endif

                                        @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','EditEmp') != '[]')
                                        <a class="dropdown-item" href="/Documents/{{$User->employee->id}}"><i class="fa fa-file-o mr-2"></i>{{__('Documents')}}</a>
                                        @endif

                                        @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','ChangeStatus') != '[]')
                                        <a type="button" class="dropdown-item" data-toggle="modal"  data-target="#modalChangeStatus" onclick="SetEmployeeID({{$User->employee->id}})"><i class="fa fa-edit font-warning mr-2"></i>{{__('Change Status')}}</a>
                                        @endif

                                        @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','EditEmp') != '[]')
                                        <a class="dropdown-item" href="/TrainingDocs/{{$User->employee->id}}"><i class="fa fa-graduation-cap font-danger mr-2"></i>{{__('Training Docs')}}</a>
                                        @endif

                                        @if (Auth::user()->roles->whereIn('title',['Admin_role','HR_role'])->first()->permissions->where('title','AddSignEmp') != '[]')
                                        <a class="dropdown-item" href="/EmployeeSignature/{{$User->employee->id}}"><i class="mdi mdi-pen font-info mr-2"></i>{{__('Add Signature')}}</a>
                                        @endif
                                    </div>
                                </div><!-- /btn-group -->
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div><!--end card-body-->
    <div class="card-footer">
        <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
    </div>
</div><!--end card-->

<!-- Modal Change Status -->
<form id="form" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="employee_id" name="employee_id" value="">
    <div class="modal fade bs-example-modal-md" id="modalChangeStatus" tabindex="-1" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Change Employee Status')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="container col-md-12">

                            <label for="status_id" class="col-form-label text-right">{{__('Job Status')}}</label>
                            <select class="select2 form-control custom-select" style="width: 100%; height:36px;" id="status_id" name="status_id" autocomplete="status_id" autofocus required>
                                <option selected="selected" value="">{{__('Select Job Status')}}</option>
                                @foreach($job_statuses as $job_status)
                                    <option value="{{$job_status->id}}">{{$job_status->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div><!--end row-->
                </div>
                <div class="modal-footer">
                    <button type="submit" id="finish" class="btn btn-success-gradien">{{__('Save')}}</button>
                    <button type="button" class="btn btn-secondary-gradien " data-dismiss="modal" onclick="this.form.reset();">{{__('Close')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!-- modal Permissions -->
<div class="modal fade bd-example-modal-xl" id="modalPermissions" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="viewModel">{{__('Modifiy Permissions')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="permissions-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                            <thead>
                                <tr style="text-align: center;">
                                    <th class="text-primary">{{__('ID Permission')}}</th>
                                    <th class="text-primary">{{__('Permission')}}</th>
                                    <th class="text-primary">{{__('Department')}}</th>
                                    <th class="text-primary">{{__('Status')}}</th>
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
                                    <th class="text-primary">{{__('Accept')}}</th>
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
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        $('#employees-table').DataTable();
        $('#permissions-table').DataTable({"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
        $('#leaves-table').DataTable({"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});

        function SetEmployeeID(EmployeeID) {$("#employee_id").val(EmployeeID);}
        $('#finish').click( function(){
            if ($('#status_id').val() == "") {
                $('#status_id').siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'});
            }
        });

        $("#status_id").change(function()
        {
            if ($('#status_id').val() == "") {
                $('#status_id').siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'});
            }else {
                $('#status_id').siblings(".select2-container").css({'border': '','border-radius': ''});
            }
        });
    </script>

    <!-- Get Employee Permissions -->
    <script type="text/javascript">
        function GetEmployeePermissions(UserID){
            $.ajax({
                type: "GET",
                url: "/GetEmployeePermissions",
                data: {'UserID' : UserID},
                cache:false,
                contentType: false,
                success: function (data) {
                    $('#permissions-table').DataTable( {
                    "destroy": true,
                    "data": data,
                    "columns": [
                        { "data": "id" },
                        { "data": "content" },
                        { "data": "department" },
                        { "data": "permission" }],
                    "order": [[ 0, "desc" ]],
                    "columnDefs": [{"targets": [ 0 ],"visible": false},
                                    {"targets": [ 3 ],"render": function ( data, type, row, meta ) {
                                        if (data['permission_status'] == '') {
                                            return '<div class="media-body icon-state"><label class="switch"><input type="checkbox" id="status'+data['permission_id']+'" onclick="StatusPer('+data['permission_id']+','+data['user_id']+')"><span class="switch-state"></span></label></div>';

                                        }
                                        else {
                                            return '<div class="media-body icon-state"><label class="switch"><input type="checkbox" id="status'+data['permission_id']+'" checked="" onclick="StatusPer('+data['permission_id']+','+data['user_id']+')"><span class="switch-state"></span></label></div>';
                                        }

                                    }}
                                ]
                    })

                    $("#modalPermissions").modal("show");

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

    <!-- Get Employee Leaves -->
    <script type="text/javascript">
        function GetEmployeeLeaves(EmployeeID){
            $.ajax({
                type: "GET",
                url: "/GetEmployeeLeaves",
                data: {'EmployeeID' : EmployeeID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'You do not have permission') {
                        SwalMessage('Permission Denied','You do not have permission.','error');
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
                            { "data": "notes" },
                            { "data": "status" }],
                        "order": [[ 0, "desc" ]],
                        "columnDefs": [{"targets": [ 0 ],"visible": false},
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

                                        }},
                                        {"targets": [ 9 ],"render": function ( data, type, row, meta ) {
                                            if (data['approved_by'] === null) {
                                                return '<div class="media-body icon-state"><label class="switch"><input type="checkbox" id="status'+data['id']+'" onclick="StatusLeave('+data['id']+')"><span class="switch-state"></span></label></div>';
                                            }
                                            else {
                                                return '<div class="media-body icon-state"><label class="switch"><input type="checkbox" id="status'+data['id']+'" checked="" onclick="StatusLeave('+data['id']+')"><span class="switch-state"></span></label></div>';
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
                }
            });
        };
    </script>

    <!-- Status Permitions -->
    <script type="text/javascript">
        function StatusPer(PermID,RoleID) {
            $.ajax({
                type: "GET",
                url: "{{url('change-user-Permission')}}",
                data: {'PermID': PermID , 'RoleID': RoleID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'ok') {
                        NotifyMessage('Success','Permission has been granted successfully.','success');
                    } else{
                        NotifyMessage('Attention!','Permission has been removed.','danger');
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

    <!-- Status Leave -->
    <script type="text/javascript">
        function StatusLeave(LeaveID) {
            $.ajax({
                type: "GET",
                url: "{{url('change-employee-leave')}}",
                data: {'LeaveID': LeaveID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'ok') {
                        NotifyMessage('Success','The leave has been accepted successfully.','success');
                    } else{
                        NotifyMessage('Success','The leave has been rejected.','danger');
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

    <!-- Submit form -->
    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/ChangeStatus",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modalChangeStatus").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'Employee status has been changed successfully.',
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
