@extends('layouts.app')
@section('css')
    <title>{{__('Receipts Books')}}</title>
    <!-- Plugins css -->
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/switch/switch.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Filter Receipts Books')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Receipts Books')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-success-gradien" onclick="OpenModal(1)"><i class="mdi mdi-arrow-down-bold-outline mr-2"></i>{{__('New Receipt')}}</button></li>

    <li><button type="button" class="btn btn-info-gradien" onclick="OpenModal(2)"><i class="mdi mdi-arrow-up-bold-outline mr-2"></i>{{__('New Payment Receipt')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-success border-3">
        <div class="row">
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
                    <option value="" selected></option>
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
            <div class="col-md-5">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-primary" onclick="GetReceiptsBooks(1)"><i class="mdi mdi-arrow-down-bold-outline mr-2"></i>{{__('Receipts')}}</button>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-outline-info" onclick="GetReceiptsBooks(2)"><i class="mdi mdi-arrow-up-bold-outline mr-2"></i>{{__('Payment Receipts')}}</button>
            </div>
        </div><br>
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
                    <th class="text-primary">{{__('Canceled')}}</th>
                    <th class="text-primary">{{__('Controller')}}</th>
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

                        @if(!empty($Receipt->employee_cancel_id))
                            <td>
                                {{$Receipt->employee_cancel->first_name}}{{__(' ')}}{{$Receipt->employee_cancel->last_name}} <br>
                                Notes: {{$Receipt->notes_cancel}}
                            </td>
                        @else
                            <td>N/A</td>
                        @endif

                        @switch($Receipt->status)
                            @case(1)
                                <td><span class="badge badge-success">{{__('Archived')}}</span> </td>
                            @break

                            @case(2)
                                <td>
                                    <div class="media-body icon-state">
                                        <label class="switch">
                                            <input type="checkbox" id="statusReceipt{{$Receipt->id}}" checked onclick="OpenModalCancel('{{$Receipt->id}}' , '{{$Receipt->status}}')"><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </td>
                            @break

                            @case(3)
                                <td>
                                    <div class="media-body icon-state">
                                        <label class="switch">
                                            <input type="checkbox" id="statusReceipt{{$Receipt->id}}" onclick="OpenModalCancel('{{$Receipt->id}}' , '{{$Receipt->status}}')"><span class="switch-state"></span>
                                        </label>
                                    </div>
                                </td>
                            @break
                        @endswitch
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

@include('mutual/financial/modals/Receipt')
@include('mutual/financial/modals/CancelReceipt')

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
        $('form').parsley();
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $('#receipt_date_month').datepicker({onSelect: function () {SearchReceiptBooksByMonth($('#receipt_date_month').val());}});
        $('#receipt_date').datepicker();
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        $('#account_id').select2({width: '100%', placeholder: 'Select an option', dropdownParent: $('#modaladdReceipt')});
        $('#currency').select2({width: '100%', placeholder: 'Select an option', dropdownParent: $('#modaladdReceipt')});

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

        $('.ts').TouchSpin({min: 0,max: 9999999999,step: 0.01,decimals: 2,boostat: 5,maxboostedstep: 10,buttondown_class: 'btn btn-info-gradien',buttonup_class: 'btn btn-primary-gradien'});

        var table = $('#receipts-books-table').DataTable({buttons: ['copy','excel','colvis'], "order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
        table.buttons().container().prependTo('#receipts-books-table_length');
        table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');

        function OpenModal(TypeReceipt) {
            if (TypeReceipt == 1) {
                $("#viewModel").html('<i class="mdi mdi-arrow-down-bold-outline text-primary mr-1"></i> {{__("New Receipt")}}');
                $("#receipt_type").val(TypeReceipt);
                $("#modaladdReceipt").modal("show");
            } else {
                $("#viewModel").html('<i class="mdi mdi-arrow-up-bold-outline text-warning mr-1"></i> {{__("New Payment Receipt")}}');
                $("#receipt_type").val(TypeReceipt);
                $("#modaladdReceipt").modal("show");
            }
        }
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

        function FilterTypes() {
            let query2 = '';
            for (let j = 4; j < 6; j++) {
                if ($('#check-status'+j).prop("checked") == true) {
                    query2 = query2 + "|" + $('#check-status'+j).val();
                }
            }

            $('#receipts-books-table').DataTable().columns( 8 ).search( query2.substring(1), true, false).draw();
        }
    </script>

    <!-- amount write -->
    <script type="text/javascript">
        $('#currency').change(function() {
            var pointCurrency = $('#currency option:selected').text();
            var amount = $('#amount').val();
            var words = toWords(parseFloat(amount).toFixed(2));
            words = words + pointCurrency.split('->')[3] +' only.';
            words = words.replace('point zero zero' , 'and no');
            $('#amount_write').val(words);
            //console.log(words);
        });

        $('#amount').on('change', function() {
            var pointCurrency = $('#currency option:selected').text();
            var amount = $('#amount').val();
            var words = toWords(parseFloat(amount).toFixed(2));
            words = words + pointCurrency.split('->')[3] +' only.';
            words = words.replace('point zero zero' , 'and no');
            $('#amount_write').val(words);
            //console.log(words);
        });

        function toWords(s)
        {
            var nameCurrency = $('#currency option:selected').text();
            var th = ['','thousand','million', 'billion','trillion'];
            // uncomment this line for English Number System
            // var th = ['','thousand','million', 'milliard','billion'];

            var dg = ['zero','one','two','three','four', 'five','six','seven','eight','nine'];
            var tn = ['ten','eleven','twelve','thirteen', 'fourteen','fifteen','sixteen', 'seventeen','eighteen','nineteen'];
            var tw = ['twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

            s = s.toString();
            s = s.replace(/[\, ]/g,'');
            if (s != parseFloat(s)) return 'not a number';
            var x = s.indexOf('.');
            if (x == -1) x = s.length;
            if (x > 15) return 'too big';
            var n = s.split('');
            var str = '';
            var sk = 0;
            for (var i=0; i < x; i++)
            {
                if ((x-i)%3==2)
                {
                    if (n[i] == '1')
                    {
                        str += tn[Number(n[i+1])] + ' '; i++; sk=1;
                    }
                    else if (n[i]!=0)
                    {
                        str += tw[n[i]-2] + ' ';sk=1;
                    }
                }
                else if (n[i]!=0)
                {
                    str += dg[n[i]] +' ';
                    if ((x-i)%3==0) str += 'hundred ';sk=1;
                }
                if ((x-i)%3==1)
                {
                    if (sk) str += th[(x-i-1)/3] + ' ';sk=0;
                }
            }
                if (x != s.length)
                {
                    var y = s.length; str += nameCurrency.split('->')[0] +' point ';
                    for (var i=x+1; i<y; i++) str += dg[n[i]] +' ';
                }
            return str.replace(/\s+/g,' ');
        }
    </script>

    <!-- Search Receipt Books By Month -->
    <script type="text/javascript">
        function SearchReceiptBooksByMonth(DateReceipts) {
            $.ajax({
                type: "GET",
                url: "{{url('SearchReceiptBooksByMonth')}}?Date="+DateReceipts,
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

    <!-- Get Receipts Books -->
    <script type="text/javascript">
        function GetReceiptsBooks(Type){
            $.ajax({
                type: "GET",
                url: "{{url('GetReceiptsBooks')}}",
                data:{Type:Type},
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
                    {"data": "canceled" },
                    {"data": "controller" }],
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
                                    if (data["employee_canceled"] === null) {
                                            return 'N/A';
                                    }else{
                                            return data["employee_canceled"] + '<br> Notes:' + data["notes_canceled"];
                                    }
                                }},
                            {"targets": [ 10 ],"render": function ( data, type, row, meta ) {
                                    switch (data["status_receipt"]) {
                                        case 1:
                                            return '<span class="badge badge-success">Archived</span>';
                                        break;
                                        case 2:
                                            return '<div class="media-body icon-state"><label class="switch"><input type="checkbox" id="statusReceipt'+data["id_receipt"]+'" checked onclick="OpenModalCancel('+data["id_receipt"]+','+data["status_receipt"]+')"><span class="switch-state"></span></label></div>';
                                        break;
                                        case 3:
                                        return '<div class="media-body icon-state"><label class="switch"><input type="checkbox" id="statusReceipt'+data["id_receipt"]+'" onclick="OpenModalCancel('+data["id_receipt"]+','+data["status_receipt"]+')"><span class="switch-state"></span></label></div>';
                                        break;
                                    }

                                }},
                            ]
            });

            table.buttons().container().prependTo('#receipts-books-table_length');
            table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');
        }
    </script>

    <!-- Submit Receipt -->
    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/ReceiptsBooks",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modaladdReceipt").modal("hide");
                    $('#finish').attr('disabled', false);
                    $('#form').trigger("reset");
                    TableReceiptsBooks(data);
                    SwalMessage('Success','The Receipt has been added successfully.','success');
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

    <!-- Status -->
    <script type="text/javascript">
        function cancel() {
            var id = $("#receipt_id").val();
            $('#statusReceipt' + id).prop('checked', true);
            $(this).form.reset();
        }

        function OpenModalCancel(ReceiptID , ReceiptStatus) {
            if (ReceiptStatus == 2) {
                $("#receipt_id").val(ReceiptID);
                $('#modalStatus').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            } else {
                $.ajax({
                    type: "GET",
                    url: "{{url('StatusReceiptsBooks')}}",
                    data: {'ReceiptID': ReceiptID},
                    cache:false,
                    contentType: false,
                    success: function (data) {
                        TableReceiptsBooks(data);
                        NotifyMessage('Success','The receipt has been accepted.','success');
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
        }

        $('#form3').on('submit', function(event) {
            $('#finish3').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/StatusReceiptsBooks",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modalStatus").modal("hide");
                    $('#finish3').attr('disabled', false);
                    $('#form3').trigger("reset");
                    TableReceiptsBooks(data);
                    NotifyMessage('Success','The receipt has been canceled.','danger');
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
        });
    </script>
@endsection
