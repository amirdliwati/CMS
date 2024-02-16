@extends('layouts.app')
@section('css')
    <title>{{__('My Leaves')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
    <style>.select2-container{z-index:100000;}</style>
@endsection
@section('breadcrumb')
    <h3>{{__('My Leaves')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('My Leaves')}}</li>
    </ol>
@endsection
@section('bookmark')
    @if($Leave_roles == '[]' || $Leave_roles->last()->created_at->format('m') != Carbon\Carbon::now()->month)
        <li><button type="button" class="btn btn-outline-secondary" onclick="SwalMessage('Error Request Leave','Sorry you can not request leave because you do not have leave role contact HR department.','error')"><i class="fa fa-plus-square-o mr-2"></i>{{__('Request Leave')}}</button></li>
    @else
        <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modaladdLeave"><i class="fa fa-plus-square-o mr-2"></i>{{__('Request Leave')}}</button></li>
    @endif
    <li><button type="button" class="btn btn-outline-info" data-toggle="modal"  data-target="#modalViewLeaveRole"><i class="fa fa-eye mr-2"></i>{{__('View Leave Role')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Leaves')}} </h5> @include('layouts/button_card') </div>
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
                    <th class="text-primary">{{__('Status')}}</th>
                    <th class="text-primary">{{__('Type Leave')}}</th>
                    <th class="text-primary">{{__('Notes/Reason')}}</th>
                    <th class="text-primary">{{__('Controller')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($Leaves as $Leave)
                <tr style="text-align: center;">
                    <td>{{$Leave->id}}</td>
                    <td><span class="badge badge-info"> {{\Carbon\Carbon::parse($Leave->date)->isoFormat('Do MMMM YYYY')}} </span></td>
                    <td><span class="badge badge-success"> {{\Carbon\Carbon::parse($Leave->start_date)->isoFormat('Do MMMM YYYY')}} </span></td>
                    <td><span class="badge badge-warning"> {{\Carbon\Carbon::parse($Leave->end_date)->isoFormat('Do MMMM YYYY')}} </span></td>
                    @if($Leave->start_date != $Leave->end_date)
                        <td><span class="badge badge-info">{{$Leave->duration}} Day/s</span></td>
                    @else
                        <td><span class="badge badge-primary">{{$Leave->duration}} Hour/s</span></td>
                    @endif

                    @if($Leave->ispaid == 0)
                        <td>{{__('No')}}</td>
                    @else
                        <td>{{__('Yes')}}</td>
                    @endif

                    @if(empty($Leave->approved_by))
                        <td><span class="badge badge-danger">{{__('Not Approved')}}</span></td>
                    @else
                        <td><span class="badge badge-success">{{__('Approved')}}</span></td>
                    @endif

                    <td>{{$Leave->leave_type->name}}</td>
                    <td>{{$Leave->notes}}</td>
                    <td>
                        @if(empty($Leave->approved_by))
                            <div class="btn-group" role="group" aria-label="Controller">
                                <button type="button" class="btn btn-danger-gradien" onclick="DeleteLeave('{{$Leave->id}}')"> <i class="fa fa-trash-o"></i></button>
                            </div>
                        @else
                            <div class="btn-group" role="group" aria-label="Controller">
                                <button type="button" class="btn btn-secondary-gradien" onclick="SwalMessage('Error Delete','Sorry you can not delete this Leave.','error')"> <i class="fa fa-trash-o"></i></button>
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
            <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
        </div>
</div> <!-- end card -->

<!--modal Add Leave-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <!-- Modal view -->
    <div class="modal fade bs-example-modal-lg" id="modaladdLeave" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Add Leave')}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="date" class="col-form-label text-right">{{__('Date')}}</label>
                            <div class="input-group">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                <input type="text" class="form-control datem initDate" placeholder="mm/dd/yyyy" id="date" name="date" value="{{ old('date') }}" autocomplete="date" required>
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label for="start_date" class="col-form-label text-right">{{__('Start Date')}}</label>
                            <div class="input-group">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                <input type="text" class="form-control datem" placeholder="mm/dd/yyyy" id="start_date" name="start_date" value="{{ old('start_date') }}" autocomplete="start_date" readonly required>
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label for="end_date" class="col-form-label text-right">{{__('End Date')}}</label>
                            <div class="input-group">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                <input type="text" class="form-control datem" placeholder="mm/dd/yyyy" id="end_date" name="end_date" value="{{ old('end_date') }}" autocomplete="end_date" readonly required>
                            </div>
                        </div> <!-- end col -->
                    </div><!--end row--> <br>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="duration" class="col-form-label text-right">{{__('Duration')}}</label>
                            <input class="form-control" type="number" step="0.01" name="duration" id="duration" value="1" placeholder="~1" autocomplete="duration" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-1">
                            <div class="media">
                                <label class="col-form-label m-r-10">{{__('Is Paid')}}</label>
                                <div class="media-body text-right icon-state">
                                    <label class="switch">
                                    <input type="checkbox" id="ispaid" name="ispaid" checked=""><span class="switch-state"></span>
                                    </label>
                                </div>
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="type_id" class="col-form-label text-right">{{__('Leave Type')}}</label>
                            <select class="form-control select2" id="type_id" name="type_id" autofocus required>
                                <option selected="selected" value="{{ old('type_id') }}"></option>
                                @foreach($Leave_types as $Leave_type)
                                    <option value="{{$Leave_type->id}}"> {{$Leave_type->name}} </option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-6">
                            <label for="notes" class="col-form-label text-right">{{__('Notes/Reason')}}</label>
                            <input type="text" class="form-control" id="notes" name="notes" placeholder="~Reason" value="{{ old('notes') }}" autocomplete="notes" required>
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

<!--Modal View Leave Role-->
<div class="modal fade bs-example-modal-lg" id="modalViewLeaveRole" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="viewModel">{{__('View Leave Role')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="leave-role-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                            <thead>
                            <tr style="text-align: center;">
                                <th class="text-primary">{{__('Leave Role ID')}}</th>
                                <th class="text-primary">{{__('Leave Role ID')}}</th>
                                <th class="text-primary">{{__('Hours Count')}}</th>
                                <th class="text-primary">{{__('Remain Days')}}</th>
                                <th class="text-primary">{{__('Remain Hours')}}</th>
                                <th class="text-primary">{{__('Created At')}}</th>
                            </tr>
                            </thead>
                            <tbody style="text-align: center;">

                            @foreach ($Leave_roles as $LeaveRole)
                            <tr style="text-align: center;">
                                <td>{{$LeaveRole->id}}</td>
                                <td>{{$LeaveRole->days_count}}</td>
                                <td>{{$LeaveRole->hours_count}}</td>
                                <td>{{$LeaveRole->remain_days}}</td>
                                <td>{{$LeaveRole->remain_hours}}</td>
                                <td><span class="badge badge-info">{{\Carbon\Carbon::parse($LeaveRole->created_at)->isoFormat('Do MMMM YYYY, h:mm:ss a')}}</span></td>
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
    <script src="{{ asset('js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('js/datepicker/date-picker/datepicker.en.js') }}"></script>
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

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        $('.initDate').val(today);

        $('#leaves-table').DataTable({"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
        $('#leave-role-table').DataTable({ "order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
    </script>

    <script type="text/javascript">
        $('#end_date').change(function(){
            var start_date = new Date($('#start_date').val());
            var end_date = new Date($('#end_date').val());

            if (end_date.getDate() < start_date.getDate()) {
                $('#end_date').val('');
                SwalMessage('Sorry!','You can not choose this Date Because it smaller than start date.','error');
            }

            else if(end_date.getDate() != start_date.getDate()) {
                $('#duration').prop('readonly', true);
                var duration = end_date.getDate() - start_date.getDate();
                $('#duration').val(duration);
            }

            else if(end_date.getDate() == start_date.getDate()) {
                $('#duration').prop('readonly', false);
                $('#duration').val(1);
            }

        });

        $('#start_date').change(function(){
            var start_date = new Date($('#start_date').val());
            var end_date = new Date($('#end_date').val());

            if (end_date.getDate() < start_date.getDate()) {
                $('#end_date').val('');
                SwalMessage('Sorry!','You can not choose this Date Because it bigger than end date.','error');
            }

            else if(end_date.getDate() != start_date.getDate()) {
                $('#duration').prop('readonly', true);
                var duration = end_date.getDate() - start_date.getDate();
                $('#duration').val(duration);
            }

            else if(end_date.getDate() == start_date.getDate()) {
                $('#duration').prop('readonly', false);
                $('#duration').val(1);
            }

        });
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/MyLeaves",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdLeave").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'The Leave has been added successfully.',
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
        function DeleteLeave (LeaveID) {
            swal({
                title: 'Caution',
                text: 'Are you sure you want to delete this Leave?',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary-gradien',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> No',
                preConfirm: function (){
                    $.ajax({
                        type: "GET",
                        url: " /Deleteleave/" + LeaveID,
                        contentType: false,
                        success: function (data) {
                            swal({
                                title: 'Success',
                                text: 'The Leave has been deleted.',
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
                }
            })
        };
    </script>
@endsection
