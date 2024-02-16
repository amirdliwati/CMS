@extends('layouts.app')
@section('css')
    <title>{{__('Receipts Books')}}</title>
    <!-- Plugins css -->
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Receipts Books Archived')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Receipts Books')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" onclick="GetReceiptsBooks(1)"><i class="mdi mdi-arrow-down-bold-outline mdi-18px mr-2"></i>{{__('Receipts')}}</button></li>

    <li><button type="button" class="btn btn-outline-info" onclick="GetReceiptsBooks(2)"><i class="mdi mdi-arrow-up-bold-outline mdi-18px mr-2"></i>{{__('Payment Receipts')}}</button></li>

    <li><button type="button" class="btn btn-outline-success" onclick="GetReceiptsBooks(4)"><i class="mdi mdi-arrow-down-bold-outline mdi-18px"></i><i class="mdi mdi-arrow-up-bold-outline mdi-18px mr-2"></i>{{__('All Receipts')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-success border-3">
        <div class="row ">
            <div class="col-md-2">
                <label for="receipt_date_month" class="col-form-label text-right">{{__('Receipt Date (Month)')}}</label>
                <div class="input-group">
                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                    <input type="text" class="form-control" id="receipt_date_month" placeholder="mm/dd/yyyy" readonly>
                </div><!-- input-group -->
            </div> <!-- end col -->
            <div class="col-md-2">
                <label for="employee" class="col-form-label text-right">{{__('Choose Employee')}}</label>
                <select class="form-control select2" id="employee" name="employee">
                    <option value="" selected>{{__('Select Employee')}}</option>
                    @foreach($ReceiptsBooks->unique('employee_id') as $Receipt)
                        <option value="{{$Receipt->employee->first_name}}{{__(' ')}}{{$Receipt->employee->last_name}}">{{$Receipt->employee->first_name}}{{__(' ')}}{{$Receipt->employee->last_name}}</option>
                    @endforeach
                </select>
            </div> <!-- end col -->
            <div class="col-md-4">
                <label class="col-form-label text-right">{{__('Status')}}</label><br>
                <div class="checkbox checkbox-primary form-check-inline">
                    <input type="checkbox" id="check-status1" value="New" onchange="FilterStatus()" checked>
                    <label for="check-status1"> {{__('New')}} </label>
                </div>
                <div class="checkbox checkbox-success form-check-inline">
                    <input type="checkbox" id="check-status2" value="Archived" onchange="FilterStatus()" checked>
                    <label for="check-status2"> {{__('Archived')}} </label>
                </div>
                <div class="checkbox checkbox-danger form-check-inline">
                    <input type="checkbox" id="check-status3" value="Canceled" onchange="FilterStatus()" checked>
                    <label for="check-status3"> {{__('Canceled')}} </label>
                </div>
            </div> <!-- end col -->
            <div class="col-md-2">
                <label for="receipt_date" class="col-form-label text-right">{{__('Receipt Date (Day)')}}</label>
                <div class="input-group">
                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                    <input type="text" class="form-control" id="receipt_date" placeholder="mm/dd/yyyy" readonly>
                </div><!-- input-group -->
            </div> <!-- end col -->
            <div class="col-md-2">
                <label for="serial_number" class="col-form-label text-right">{{__('Serial Number')}}</label>
                <input class="form-control mx" type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" placeholder="~search" autocomplete="serial_number" maxlength="25" required>
            </div> <!-- end col -->
        </div><!-- end row -->
        @include('layouts/button_card')
    </div>
    <div class="card-body">
        <div class="row">
            <table id="receipts-books-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('ID')}}</th>
                    <th class="text-primary">{{__('S/N')}}</th>
                    <th class="text-primary">{{__('Date')}}</th>
                    <th class="text-primary">{{__('Customer')}}</th>
                    <th class="text-primary">{{__('Status')}}</th>
                    <th class="text-primary">{{__('Employee')}}</th>
                    <th class="text-primary">{{__('Amount')}}</th>
                    <th class="text-primary">{{__('Description')}}</th>
                    <th class="text-primary">{{__('Type')}}</th>
                    <th class="text-primary">{{__('Employee Archived')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">
                    @foreach ($ReceiptsBooks as $Receipt)
                    <tr style="text-align: center;">
                        <td>{{$Receipt->id}}</td>

                        @if($Receipt->status == 3)
                            <td>{{$Receipt->serial_number}}</td>
                        @else
                            <td><a href="/PreviewReceiptBook/{{$Receipt->id}}" target="_blank">{{$Receipt->serial_number}}</a></td>
                        @endif

                        <td><span class="badge badge-info">{{\Carbon\Carbon::parse($Receipt->date)->isoFormat('Do MMMM YYYY')}}</span></td>

                        <td>{{$Receipt->customer}}</td>

                        @switch($Receipt->status)
                            @case(1)
                                <td><span class="badge badge-success">{{__('Archived')}}</span> </td>
                            @break

                            @case(2)
                                <td><span class="badge badge-primary">{{__('New')}}</span> </td>
                            @break

                            @case(3)
                                <td><span class="badge badge-danger">{{__('Canceled')}}</span> </td>
                            @break
                        @endswitch

                        <td>{{$Receipt->employee->first_name}}{{__(' ')}}{{$Receipt->employee->last_name}}</td>

                        <td><span class="badge badge-success">{{$Receipt->currencie->symbol}}</span>   {{$Receipt->amount}}</td>

                        <td>{{$Receipt->notes}}</td>

                        @if($Receipt->type == 1)
                            <td><span class="badge badge-primary">{{__('Receipt')}}</span> </td>
                        @else
                            <td><span class="badge badge-warning">{{__('Payment Receipt')}}</span> </td>
                        @endif

                        @if(!empty($Receipt->employee_archive_id))
                            <td>
                                {{$Receipt->employee_archive->first_name}}{{__(' ')}}{{$Receipt->employee_archive->last_name}}
                            </td>
                        @else
                            <td>{{__('N/A')}}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div> <!-- end row -->
    </div> <!-- end card-body -->
        <div class="card-footer">
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
        $('#receipt_date_month').datepicker({onSelect: function () {SearchReceiptBooksByMonth($('#receipt_date_month').val());}});
        $('#receipt_date').datepicker();
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});

        var table = $('#receipts-books-table').DataTable({ buttons: ['copy','excel','colvis'],"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false, "searchable": false},]});
        table.buttons().container().prependTo('#receipts-books-table_length');
        table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');
    </script>

    <!-- Search Offline -->
    <script type="text/javascript">
        $('#serial_number').on( 'keyup', function () {$('#receipts-books-table').DataTable().columns( 1 ).search( this.value ).draw();} );

        $('#employee').on('change', function () {
            if (!$(this).val()) {
                $('#receipts-books-table').DataTable().columns( 5 ).search('').draw();
            }
            else { $('#receipts-books-table').DataTable().columns( 5 ).search( this.value ).draw(); }
        } );

        $('#receipt_date').on( 'change', function () {
            if (!$(this).val()) {
                $('#receipts-books-table').DataTable().columns( 2 ).search('').draw();
            }
            else { $('#receipts-books-table').DataTable().columns( 2 ).search( moment(this.value).format('Do MMMM YYYY') ).draw(); }
        } );

        function FilterStatus() {
            let query = '';
            for (let i = 1; i < 4; i++) {
                if ($('#check-status'+i).prop("checked") == true) {
                    query = query + "|" + $('#check-status'+i).val();
                }
            }

            $('#receipts-books-table').DataTable().columns( 4 ).search( query.substring(1), true, false).draw();
        }
    </script>

    <!-- Search Receipt Books By Month -->
    <script type="text/javascript">
        function SearchReceiptBooksByMonth(DateReceipts) {
            $.ajax({
                type: "GET",
                url: "{{url('SearchReceiptBooksByMonthFinancial')}}?Date="+DateReceipts,
                contentType: 'application/json',
                cache:false,
                success: function (data) {
                    if (data == ''){
                        $('#receipts-books-table').DataTable().clear().draw();
                        SwalMessage('Sorry!','No data available.','info');
                    }
                    else {
                        TableReceiptsBooks(data);
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

    <!-- Get Receipts Books -->
    <script type="text/javascript">
        function GetReceiptsBooks(Type){
            $.ajax({
                type: "GET",
                url: "{{url('GetReceiptsBooksFinancial')}}?Type="+Type,
                contentType: 'application/json',
                cache:false,
                success: function (data) {
                    if (data == ''){
                        $('#receipts-books-table').DataTable().clear().draw();
                        SwalMessage('Sorry!','No data available.','info');
                    }
                    else {
                        TableReceiptsBooks(data);
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

    <!-- Table Receipts Books -->
    <script type="text/javascript">
        function TableReceiptsBooks(data) {
            var table = $('#receipts-books-table').DataTable( {
                buttons: ['copy','excel','colvis'],
                "destroy": true,
                "data": data,
                "columns": [
                    {"data": "id" },
                    {"data": "serial_number" },
                    {"data": "date" },
                    {"data": "customer" },
                    {"data": "status" },
                    {"data": "employee" },
                    {"data": "amount" },
                    {"data": "notes" },
                    {"data": "type" },
                    {"data": "employee_archived" }],
                "order": [[ 0, "desc" ]],
                "columnDefs": [{"targets": [ 0 ],"visible": false},
                                {"targets": [ 1 ],"render": function ( data, type, row, meta ) {
                                    if (data["status"] == 3) {
                                        return data["S/N"];
                                    } else {
                                        return '<a href="/PreviewReceiptBook/'+data["receipt_id"]+'" target="_blank">'+data["S/N"]+'</a>';
                                    }
                                }},
                                {"targets": [ 2 ],"render": function ( data, type, row, meta ) {
                                    return '<span class="badge badge-info">'+moment(data).format('Do MMMM YYYY')+'</span>';
                                }},
                            {"targets": [ 4 ],"render": function ( data, type, row, meta ) {
                                    switch (data) {
                                        case 1:
                                            return '<span class="badge badge-success">Archived</span>';
                                        break;
                                        case 2:
                                            return '<span class="badge badge-primary">New</span>';
                                        break;
                                        case 3:
                                            return '<span class="badge badge-danger">Canceled</span>';
                                        break;
                                    }

                                }},
                            {"targets": [ 6 ],"render": function ( data, type, row, meta ) {
                                    return '<span class="badge badge-success">'+data["currency"]+'</span>  ' + data["amount_receipt"];
                                }},
                            {"targets": [ 8 ],"render": function ( data, type, row, meta ) {
                                    if (data == 1) {
                                        return '<span class="badge badge-primary">Receipt</span>';
                                    } else {
                                        return '<span class="badge badge-warning">Payment Receipt</span>';
                                    }
                                }},
                            {"targets": [ 9 ],"render": function ( data, type, row, meta ) {
                                    if (data === null) {
                                            return 'N/A';
                                    }else{
                                            return data;
                                    }
                                }}
                            ]
            });

            table.buttons().container().prependTo('#receipts-books-table_length');
            table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');

        }
    </script>
@endsection
