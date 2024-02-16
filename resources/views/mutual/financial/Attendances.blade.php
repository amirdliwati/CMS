@extends('layouts.app')
@section('css')
    <title>{{__('My Attendances')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('My Attendances')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('My Attendances')}}</li>
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
            <div class="col-md-2">
                <label for="start_date" class="col-form-label text-right">{{__('Start Date')}}</label>
                <div class="input-group">
                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                    <input name="start_date" type="text" class="form-control" placeholder="mm/dd/yyyy" id="start_date" readonly>
                </div><!-- input-group -->
            </div> <!-- end col -->

            <div class="col-md-2">
                <label for="end_date" class="col-form-label text-right">{{__('End Date')}}</label>
                <div class="input-group">
                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                    <input name="end_date" type="text" class="form-control" placeholder="mm/dd/yyyy" id="end_date" readonly>
                </div><!-- input-group -->
            </div> <!-- end col -->

            <div class="col-md-8">
                <label class="col-form-label text-right">{{__('Punch State')}}</label>
                    <br>
                <div class="checkbox checkbox-success form-check-inline">
                    <input type="checkbox" id="check-status0" value="Check IN" onchange="FilterState()" checked>
                    <label for="check-status0"> {{__('Check IN')}} </label>
                </div>
                <div class="checkbox checkbox-danger form-check-inline">
                    <input type="checkbox" id="check-status1" value="Check Out" onchange="FilterState()" checked>
                    <label for="check-status1"> {{__('Check Out')}} </label>
                </div>
                <div class="checkbox checkbox-warning form-check-inline">
                    <input type="checkbox" id="check-status3" value="Break IN" onchange="FilterState()" checked>
                    <label for="check-status3"> {{__('Break IN')}} </label>
                </div>
                <div class="checkbox checkbox-danger form-check-inline">
                    <input type="checkbox" id="check-status2" value="Break Out" onchange="FilterState()" checked>
                    <label for="check-status2"> {{__('Break Out')}} </label>
                </div>
                <div class="checkbox checkbox-info form-check-inline">
                    <input type="checkbox" id="check-status4" value="Overtime IN" onchange="FilterState()" checked>
                    <label for="check-status4"> {{__('Overtime IN')}} </label>
                </div>
                <div class="checkbox checkbox-danger form-check-inline">
                    <input type="checkbox" id="check-status5" value="Overtime Out" onchange="FilterState()" checked>
                    <label for="check-status5"> {{__('Overtime Out')}} </label>
                </div>
            </div> <!-- end col -->
        </div><!-- end row -->
        @include('layouts/button_card')
    </div>
    <div class="card-body">
        <div class="row">
            <table id="attendances-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Attendance ID')}}</th>
                    <th class="text-primary">{{__('Punch State')}}</th>
                    <th class="text-primary">{{__('Punch Time')}}</th>
                    <th class="text-primary">{{__('Device S/N')}}</th>
                    <th class="text-primary">{{__('Device Name')}}</th>
                    <th class="text-primary">{{__('Status')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($Attendances as $Attendance)
                <tr style="text-align: center;">
                    <td>{{$Attendance->id}}</td>

                    @switch($Attendance->punch_state)
                        @case(0)
                            <td><span class="badge badge-success">{{__('Check IN')}}</span></td>
                        @break
                        @case(1)
                            <td><span class="badge badge-danger">{{__('Check Out')}}</span></td>
                        @break
                        @case(3)
                            <td><span class="badge badge-warning">{{__('Break IN')}}</span></td>
                        @break
                        @case(2)
                            <td><span class="badge badge-danger">{{__('Break Out')}}</span></td>
                        @break
                        @case(4)
                            <td><span class="badge badge-info">{{__('Overtime IN')}}</span></td>
                        @break
                        @case(5)
                            <td><span class="badge badge-danger">{{__('Overtime Out')}}</span></td>
                        @break
                    @endswitch

                    <td><span class="badge badge-info">{{\Carbon\Carbon::parse($Attendance->punch_time)->isoFormat('Do MMMM YYYY, h:mm:ss a')}}</span></td>

                    <td>{{$Attendance->terminal_sn}}</td>
                    <td>{{$Attendance->terminal_alias}}</td>

                    @if($Attendance->sync == 1)
                        <td><span class="badge badge-success"> {{__('Accepted')}} </span></td>
                    @else
                        <td><span class="badge badge-danger"> {{__('Not Accepted')}} </span></td>
                    @endif

                </tr>
                @endforeach
                </tbody>
            </table>
        </div> <!-- end row -->
    </div> <!-- end card-body -->
        <div class="card-footer">
            <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
        </div>
</div> <!-- end card -->
@endsection
@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/moment/moment.js') }}"></script>
    <script src="{{ asset('js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>

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
        $('#end_date').datepicker({onSelect: function () {SearchEmployee($('#end_date').val());}});
        $('#start_date').datepicker();
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});

        var table = $('#attendances-table').DataTable({buttons: ['copy','excel','colvis'],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
        table.buttons().container().prependTo('#attendances-table_length');
        table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');

        function FilterState() {
            let query = '';
            for (let i = 0; i < 6; i++) {
                if ($('#check-status'+i).prop("checked") == true) {
                    query = query + "|" + $('#check-status'+i).val();
                }
            }

            $('#attendances-table').DataTable().columns( 1 ).search( query.substring(1), true, false).draw();
        }
    </script>

    <script type="text/javascript">
        function SearchEmployee(EndDate) {
           var employeeID = "{{ $Employee->id}}";
           var start_date = formatDate($('#start_date').val());
            $.ajax({
                type: "GET",
                url: "{{url('SearchMyAttendances')}}",
                data:{employee_id:employeeID, start_date:start_date, end_date:EndDate},
                contentType: 'application/json',
                cache:false,
                success: function (data) {
                    if (data == ''){
                        SwalMessage('Sorry!','No data available for this employee.','info');
                    }
                    else if (data == 'You do not have permission'){
                        SwalMessage('Sorry!','You do not have permission.','danger');
                    }
                    else {
                        var table = $('#attendances-table').DataTable( {
                            buttons: ['copy','excel','colvis'],
                            "destroy": true,
                            "data": data,
                            "columns": [
                                {"data": "id" },
                                {"data": "punch_state" },
                                {"data": "punch_time" },
                                {"data": "terminal_sn" },
                                {"data": "terminal_alias" },
                                {"data": "sync" }],
                            "columnDefs": [{"targets": [ 0 ],"visible": false},
                                        {"targets": [ 1 ],"render": function ( data, type, row, meta ) {
                                                switch (data['state']) {
                                                    case 0:
                                                        return '<span class="badge badge-success">{{__("Check IN")}}</span>';
                                                    break;
                                                    case 1:
                                                        return '<span class="badge badge-danger">{{__("Check Out")}}</span>';
                                                    break;
                                                    case 3:
                                                        return '<span class="badge badge-warning">{{__("Break IN")}}</span>';
                                                    break;
                                                    case 2:
                                                        return '<span class="badge badge-danger">{{__("Break Out")}}</span>';
                                                    break;
                                                    case 4:
                                                        return '<span class="badge badge-info">{{__("Overtime IN")}}</span>';
                                                    break;
                                                    case 5:
                                                        return '<span class="badge badge-danger">{{__("Overtime Out")}}</span>';
                                                    break;
                                                }

                                            }},
                                        {"targets": [ 2 ],"render": function ( data, type, row, meta ) {
                                            return '<span class="badge badge-info">'+moment(data).format('Do MMMM YYYY, h:mm:ss a')+'</span>';
                                        }},
                                        {"targets": [ 5 ],"render": function ( data, type, row, meta ) {
                                            if (data == 1) {
                                                return '<span class="badge badge-success"> {{__("Accepted")}} </span>';
                                            } else {
                                                return '<span class="badge badge-danger"> {{__("Not Accepted")}} </span>';
                                            }
                                        }},
                                        ]
                        });

                        table.buttons().container().prependTo('#attendances-table_length');
                        table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');

                    }

                },
                error: function(jqXHR){
                    if(jqXHR.status==0) {
                        SwalMessage('You are offline','Connection to the server has been lost. Please check your internet connection and try again.','error');
                    } else{
                        SwalMessage('Attention','Something went wrong. Please try again later.','warning');
                    }
                    $('#finish3').attr('disabled', false);
                }
            });
        };
    </script>
@endsection
