@extends('layouts.app')
@section('css')
    <title>{{__('Leaves Employees')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Leaves Employees')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Leaves Employees')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modalViewLeaveRole"><i class="fa fa-eye mr-2"></i>{{__('View Leave Roles')}}</button></li>
    @if($Employee->leave_roles == '[]' || $Employee->leave_roles->last()->created_at->format('m') != Carbon\Carbon::now()->month)
        <li><button type="button" class="btn btn-outline-info" data-toggle="modal"  data-target="#modalLeaveRole"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add Leave Role')}}</button></li>
    @else
        <li><button type="button" class="btn btn-outline-warning" data-toggle="modal"  data-target="#modalLeaveRole"><i class="fa fa-edit mr-2"></i>{{__('Edit Leave Role')}}</button></li>
    @endif
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Leaves Employee')}} ({{$Employee->first_name}} {{$Employee->last_name}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="leaves-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Leave ID')}}</th>
                    <th class="text-primary">{{__('Date')}}</th>
                    <th class="text-primary">{{__('Start Date')}}</th>
                    <th class="text-primary">{{__('End Date')}}</th>
                    <th class="text-primary">{{__('Duration')}}</th>
                    <th class="text-primary">{{__('Is Paid')}}</th>
                    <th class="text-primary">{{__('Type Leave')}}</th>
                    <th class="text-primary">{{__('Notes/Reason')}}</th>
                    <th class="text-primary">{{__('Accept')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($Leaves as $Leave)
                <tr style="text-align: center;">
                    <td>{{$Leave->id}}</td>
                    <td><span class="badge badge-info">{{\Carbon\Carbon::parse($Leave->date)->isoFormat('Do MMMM YYYY')}}</span></td>
                    <td><span class="badge badge-success">{{\Carbon\Carbon::parse($Leave->start_date)->isoFormat('Do MMMM YYYY')}}</span></td>
                    <td><span class="badge badge-warning">{{\Carbon\Carbon::parse($Leave->end_date)->isoFormat('Do MMMM YYYY')}}</span></td>
                    @if($Leave->start_date != $Leave->end_date)
                        <td><span class="badge badge-info">{{$Leave->duration}} Day/s</span></td>
                    @else
                        <td><span class="badge badge-primary">{{$Leave->duration}} Hour/s</span></td>
                    @endif

                    <td><div class="mb-3"></div>
                        @if($Leave->ispaid == 0)
                            <div class="media-body icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status_paid{{$Leave->id}}" onclick="StatusPaid('{{$Leave->id}}')"><span class="switch-state"></span>
                                </label>
                            </div>
                        @else
                            <div class="media-body icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status_paid{{$Leave->id}}" checked="" onclick="StatusPaid('{{$Leave->id}}')"><span class="switch-state"></span>
                                </label>
                            </div>
                        @endif
                    </td>

                    <td>{{$Leave->leave_type->name}}</td>
                    <td>{{$Leave->notes}}</td>

                    <td><div class="mb-3"></div>
                        @if(empty($Leave->approved_by))
                            <div class="media-body icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status{{$Leave->id}}" onclick="StatusLeave('{{$Leave->id}}')"><span class="switch-state"></span>
                                </label>
                            </div>
                        @else
                            <div class="media-body icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status{{$Leave->id}}" checked="" onclick="StatusLeave('{{$Leave->id}}')"><span class="switch-state"></span>
                                </label>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div><!-- end row -->
    </div> <!-- end card-body -->
        <div class="card-footer">
            <a href="{{route('ViewEmp')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Employees')}}</button></a>
        </div>
</div> <!-- end card -->

<!--Modal Add/Edit Leave Role-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <!-- Modal view -->
    <input type="hidden" id="employee_id" name="employee_id" value="{{$Employee->id}}">
    @if($Employee->leave_roles == '[]' || $Employee->leave_roles->last()->created_at->format('m') != Carbon\Carbon::now()->month)
        <input type="hidden" id="leave_role_id" name="leave_role_id" value="0">
    @else
        <input type="hidden" id="leave_role_id" name="leave_role_id" value="{{$Employee->leave_roles->last()->id}}">
    @endif
    <div class="modal fade bs-example-modal-lg" id="modalLeaveRole" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    @if($Employee->leave_roles == '[]' || $Employee->leave_roles->last()->created_at->format('m') != Carbon\Carbon::now()->month)
                        <h5 class="modal-title mt-0" id="viewModel">{{__('Add Leave Role')}}</h5>
                    @else
                        <h5 class="modal-title mt-0" id="viewModel"><i class="mdi mdi-circle-edit-outline text-warning mr-1"></i>{{__('Edit Leave Role')}} </h5>
                    @endif
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="days_count" class="col-form-label text-right">{{__('Days Count')}}</label>
                                    @if($Employee->leave_roles == '[]' || $Employee->leave_roles->last()->created_at->format('m') != Carbon\Carbon::now()->month)
                                        <input class="form-control ts" type="number" step="0.01" name="days_count" id="days_count" value="0" placeholder="~0.0" autocomplete="days_count" autofocus required>
                                    @else
                                        <input class="form-control ts" type="number" step="0.01" name="days_count" id="days_count" value="{{$Employee->leave_roles->last()->days_count}}" placeholder="~0.0" autocomplete="days_count" autofocus required>
                                    @endif
                                </div> <!-- end col -->
                                <div class="col-md-4">
                                    <label for="hours_count" class="col-form-label text-right">{{__('Hours Count')}}</label>
                                    @if($Employee->leave_roles == '[]' || $Employee->leave_roles->last()->created_at->format('m') != Carbon\Carbon::now()->month)
                                        <input class="form-control ts" type="number" step="0.01" name="hours_count" id="hours_count" value="0" placeholder="~0.0" autocomplete="hours_count" autofocus required>
                                    @else
                                        <input class="form-control ts" type="number" step="0.01" name="hours_count" id="hours_count" value="{{$Employee->leave_roles->last()->hours_count}}" placeholder="~0.0" autocomplete="hours_count" autofocus required>
                                    @endif
                                </div> <!-- end col -->
                                <div class="col-md-2">
                                    <label for="remain_days" class="col-form-label text-right">{{__('Remain Days')}}</label>
                                    @if($Employee->leave_roles == '[]' || $Employee->leave_roles->last()->created_at->format('m') != Carbon\Carbon::now()->month)
                                        <input class="form-control" type="number" step="0.01" name="remain_days" id="remain_days" value="0" placeholder="~0.0" autocomplete="remain_days" autofocus readonly>
                                    @else
                                        <input class="form-control" type="number" step="0.01" name="remain_days" id="remain_days" value="{{$Employee->leave_roles->last()->remain_days}}" placeholder="~0.0" autocomplete="remain_days" autofocus readonly>
                                    @endif
                                </div> <!-- end col -->
                                <div class="col-md-2">
                                    <label for="remain_hours" class="col-form-label text-right">{{__('Remain Hours')}}</label>
                                    @if($Employee->leave_roles == '[]' || $Employee->leave_roles->last()->created_at->format('m') != Carbon\Carbon::now()->month)
                                        <input class="form-control" type="number" step="0.01" name="remain_hours" id="remain_hours" value="0" placeholder="~0.0" autocomplete="remain_hours" autofocus readonly>
                                    @else
                                        <input class="form-control" type="number" step="0.01" name="remain_hours" id="remain_hours" value="{{$Employee->leave_roles->last()->remain_hours}}" placeholder="~0.0" autocomplete="remain_hours" autofocus readonly>
                                    @endif
                                </div> <!-- end col -->
                            </div><!--end row-->
                        </div><!-- end col -->
                    </div><!--end row-->
                </div> <!--end modal-body-->
                <div class="modal-footer">
                    @if($Employee->leave_roles == '[]' || $Employee->leave_roles->last()->created_at->format('m') != Carbon\Carbon::now()->month)
                        <button type="submit" id="finish" class="btn btn-success-gradien">{{__('Save')}}</button>
                    @else
                        <button type="submit" id="finish" class="btn btn-success-gradien">{{__('Save Changed')}}</button>
                    @endif
                        <button type="button" class="btn btn-secondary-gradien" data-dismiss="modal" onclick="this.form.reset()">{{__('Close')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!--Modal View Leave Role-->
<div class="modal fade bs-example-modal-lg" id="modalViewLeaveRole" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="viewModel"><i class="mdi mdi-credit-card-refund text-primary mr-1"></i>{{__('View Leave Role')}} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="leave-role-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                            <thead>
                            <tr style="text-align: center;">
                                <th class="text-primary">{{__('Leave Role ID')}}</th>
                                <th class="text-primary">{{__('Days Count')}}</th>
                                <th class="text-primary">{{__('Hours Count')}}</th>
                                <th class="text-primary">{{__('Remain Days')}}</th>
                                <th class="text-primary">{{__('Remain Hours')}}</th>
                                <th class="text-primary">{{__('Created At')}}</th>
                            </tr>
                            </thead>
                            <tbody style="text-align: center;">

                            @foreach ($Employee->leave_roles as $LeaveRole)
                            <tr style="text-align: center;">
                                <td>{{$LeaveRole->id}}</td>
                                <td>{{$LeaveRole->days_count}}</td>
                                <td>{{$LeaveRole->hours_count}}</td>
                                <td>{{$LeaveRole->remain_days}}</td>
                                <td>{{$LeaveRole->remain_hours}}</td>
                                <td><span class="badge badge-success">{{\Carbon\Carbon::parse($LeaveRole->created_at)->isoFormat('Do MMMM YYYY, h:mm:ss a')}}</span></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- end col -->
                </div><!--end row-->
            </div> <!--end modal-body-->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-gradien" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection
@section('javascript')
    <!-- Plugins js -->
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
        $('.ts').TouchSpin({min: 0,max: 9999999999,step: 0.01,boostat: 5,maxboostedstep: 10,buttondown_class: 'btn btn-info-gradien',buttonup_class: 'btn btn-primary-gradien'});

        $('#leaves-table').DataTable({"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
        $('#leave-role-table').DataTable({"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/LeaveRole",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modalLeaveRole").modal("hide");
                    if(data == 'ok') {
                            swal({
                            title: 'Success',
                            text: 'The leave role has been added successfully.',
                            type: 'success',
                            preConfirm: function (){location.reload();}
                        });
                    }
                    else {
                            swal({
                            title: 'Success',
                            text: 'The leave role has been changed successfully.',
                            type: 'success',
                            preConfirm: function (){location.reload();}
                        });
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
                        NotifyMessage('Reject','The leave has been rejected.','danger');
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

    <script type="text/javascript">
        function StatusPaid(LeaveID) {
            $.ajax({
                type: "GET",
                url: "{{url('change-employee-leave')}}",
                data: {'LeaveID': LeaveID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'ok') {
                        NotifyMessage('Success','The leave has been changed to paid successfully.','success');
                    } else{
                        NotifyMessage('Changed','The leave has been changed to not paid.','danger');
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
