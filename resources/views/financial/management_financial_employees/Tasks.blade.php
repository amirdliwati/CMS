@extends('layouts.app')
@section('css')
    <title>{{__('Tasks Employee')}}</title>
    <!-- Plugins css -->
    <link href="{{ asset('css/notebook.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Manage Financial Employee')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Tasks')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modaladdTask"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add New Task')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Payrolls Employee')}} ({{$Employee->first_name}} {{$Employee->middle_name}} {{$Employee->last_name}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="task-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('ID Task')}}</th>
                        <th class="text-primary">{{__('Date')}}</th>
                        <th class="text-primary">{{__('Amount')}}</th>
                        <th class="text-primary">{{__('Notes')}}</th>
                        <th class="text-primary">{{__('Salary')}}</th>
                        <th class="text-primary">{{__('Payroll')}}</th>
                        <th class="text-primary">{{__('Controller')}}</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    @foreach ($Tasks as $Task)
                    <tr style="text-align: center;">
                        <td>{{$Task->id}}</td>
                        <td><span class="badge badge-info">{{\Carbon\Carbon::parse($Task->date)->isoFormat('Do MMMM YYYY')}}</span></td>
                        <td><span class="badge badge-success">{{$Employee->currencie->symbol}}</span> {{$Task->amount}}</td>
                        <td>{{$Task->notes}}</td>
                        <td><span class="badge badge-success">{{$Employee->currencie->symbol}}</span> {{$Task->salary->basic}}</td>

                        @if(empty($Task->payroll->date))
                            <td><span class="badge badge-danger">{{__('Not added to payroll yet')}}</span></td>
                        @else
                            <td><span class="badge badge-success">{{$Task->payroll->date}}</span></td>
                        @endif

                        <td>
                            <div class="btn-group mb-2 mb-md-0">
                                @if(empty($Task->attach))
                                    <button type="button" class="btn btn-light-gradien" onclick="SwalMessage('Error Attach','Sorry no attach for this task.','warning')"><i class="fa fa-file-text-o"></i></button>
                                @else
                                    <a href="/PreviewTask/{{$Task->id}}" target="_blank"><button type="button" class="btn btn-info-gradien" onclick="DeletePayroll('{{$Payroll->id}}')"><i class="mdi mdi-pen"></i></button></a>
                                @endif

                                @if(empty($Task->payroll))
                                    <button type="button" class="btn btn-danger-gradien btn-delete"><i class="fa fa-trash-o"></i></button>
                                @else
                                    <button type="button" class="btn btn-light-gradien" onclick="SwalMessage('Error Delete','Sorry you can not delete this task.','error')"><i class="fa fa-trash-o"></i></button>
                                @endif
                            </div><!-- /btn-group -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table><!--end table-->
        </div> <!-- end row -->
    </div> <!-- end card-body -->
        <div class="card-footer">
            <a href="{{route('ManagementFinancialEmployees')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Employees')}}</button></a>
        </div>
</div> <!-- end card -->

<!--modal Add Task-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <input type="hidden" name="employee_id" value="{{$Employee->id}}">
    <input type="hidden" id="salary_id" name="salary_id" value="{{$Employee->salaries->where('end_date',Null)->first()->id}}">
    <div class="modal fade bs-example-modal-xl" id="modaladdTask" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Add New Task')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-form-label text-right">{{__('Task Date')}}</label>
                                    <div class="input-group">
                                        <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                        <input type="text" class="form-control datem initDate" id="date" name="date" value="{{ old('date') }}" autocomplete="date" placeholder="mm/dd/yyyy" autofocus readonly required >
                                    </div>
                                </div> <!-- end col -->
                                <div class="col-md-6">
                                    <label for="amount" class="col-form-label text-right">{{__('Amount')}}</label>
                                    <input class="form-control ts" type="number" step="0.01" name="amount" id="amount" value="0" placeholder="~0.0" autocomplete="amount" autofocus>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                                <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="paper">
                                        <div class="paper-content">
                                            <label for="notes" class="col-form-label text-right text-info" style="font-weight: bold;font-size: large;text-decoration: underline;">{{__('Description')}}:</label>
                                            <textarea type="text" class="form-control mx" id="notes" name="notes" autocomplete="notes" placeholder="~" autofocus rows="5" maxlength="500" data-toggle="tooltip" data-placement="left" title="Write any thing" required="">{{ old('notes') }}</textarea>
                                        </div> <!-- paper-content -->
                                    </div> <!-- paper -->
                                </div><!--end col-->
                            </div> <!-- end row -->
                        </div> <!-- end col -->
                        <div class="col-md-4 mt-5">
                            <div id="attachPic">
                                <h4 class="mt-0 header-title"><b class="mr-2">{{__('Task Image / File')}}</b>
                                </h4>
                                <p class="text-muted mb-4">{{__('File should be up to 15 Megabytes')}}.</p>
                                <input type="file" class="form-control dropify" id="attach" name="attach" accept="image/*,application/pdf" data-default-file=""  data-height="125" data-max-file-size="15M" data-allowed-file-extensions="png jpg jpeg pdf">
                            </div><!--end card-body-->
                        </div><!--end col-->
                    </div>
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

    <!-- Files Upload -->
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/jquery.form-upload.init.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $('.datem').datepicker();
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $('.ts').TouchSpin({min: 0,max: 9999999999,step: 0.01,decimals: 2,boostat: 5,maxboostedstep: 10,buttondown_class: 'btn btn-info-gradien',buttonup_class: 'btn btn-primary-gradien'});
        $('#task-table').DataTable({"order":[0, 'desc'],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        $('.initDate').val(today);

        $( "#attach" ).change(function() {
            if ($('#attach').val() == "") {
                $('#attachPic').children( ".dropify-wrapper" ).css("border", "1px solid red");
            }else {
                $('#attachPic').children( ".dropify-wrapper" ).css("border", "");
            }
        });

        $('#finish').click( function() {
            if ($('#attach').val() == "") {
                $('#attachPic').children( ".dropify-wrapper" ).css("border", "1px solid red");
            } else {
                $('#attachPic').children( ".dropify-wrapper" ).css("border", "");
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
                url: "/Tasks",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdTask").modal("hide");
                    $('#finish').attr('disabled', false);
                    $('#form').trigger("reset");
                    swal({
                        title: 'Success',
                        text: 'Task has been added successfully.',
                        type: 'success',
                        preConfirm: function (){
                            location.reload(true);
                        }
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
        $('#task-table tbody').on( 'click', '.btn-delete', function () {
            var table = $('#task-table').DataTable();
            var TaskID = table.row( $(this).parents('tr') ).data()[0];
            var row = table.row( $(this).parents('tr') );
            swal({
                title: 'Caution',
                text: 'Are you sure you want to delete this task?',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary-gradien',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                cancelButtonText: '<i class="fa fa-thumbs-down"></i> No',
                preConfirm: function (){
                    $.ajax({
                        type: "GET",
                        url: " /DeleteTask/" + TaskID,
                        contentType: false,
                        success: function (data) {
                            swal({
                                title: 'Success',
                                text: 'The task has been deleted.',
                                type: 'success',
                                preConfirm: function (){
                                    row.remove().draw();
                                }
                            });
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
        } );
    </script>
@endsection
