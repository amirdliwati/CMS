@extends('layouts.app')
@section('css')
    <title>{{__('Items')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Items')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Items')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" onclick="GetItems(2)">{{__('In Storage')}}</button></li>
    <li><button type="button" class="btn btn-outline-success" onclick="GetItems(4)">{{__('Sold')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Items')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <table id="items-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
            <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('ID')}}</th>
                    <th class="text-primary">{{__('Serial Number')}}</th>
                    <th class="text-primary">{{__('Item Name')}}</th>
                    <th class="text-primary">{{__('Model')}}</th>
                    <th class="text-primary">{{__('Rating')}}</th>
                    <th class="text-primary">{{__('Status')}}</th>
                    <th class="text-primary">{{__('Inventory')}}</th>
                    <th class="text-primary">{{__('Barcode')}}</th>
                </tr>
            </thead>
            <tbody style="text-align: center;">
                @foreach($Items as $Item)
                <tr style="text-align: center;">
                    <td>{{$Item->id}}</td>
                    <td>
                        <a href="/ItemDetails/{{$Item->id}}" target="_blank">{{$Item->serial}}</a>
                    </td>
                    <td>{{$Item->name_item->name}}</td>
                    <td>{{$Item->model}}</td>

                    @switch($Item->quality)
                        @case(1)
                            <td><span class="badge badge-danger">{{__('Bad')}}</span></td>
                            @break
                        @case(2)
                            <td><span class="badge badge-warning">{{__('Not Bad')}}</span></td>
                            @break
                        @case(3)
                            <td><span class="badge badge-info">{{__('Good')}}</span></td>
                            @break
                        @case(4)
                            <td><span class="badge badge-primary">{{__('Very Good')}}</span></td>
                            @break
                        @case(5)
                            <td><span class="badge badge-success">{{__('Exclente')}}</span></td>
                            @break
                    @endswitch

                    @switch($Item->status_id)
                        @case(2)
                            <td><span class="badge badge-primary">{{$Item->status_item->name}}</span></td>
                            @break
                        @case(4)
                            <td><span class="badge badge-success">{{$Item->status_item->name}}</span></td>
                            @break
                        @default
                            <td><span class="badge badge-secondary">{{$Item->status_item->name}}</span></td>
                            @break
                    @endswitch

                    <td>{{$Item->sub_inventory->branch->name}} -> {{$Item->sub_inventory->name}}</td>

                    <td>
                        <button type="button" class="btn btn-danger" onclick="printBarcode({{$Item->id }})"><i class="fa fa-qrcode"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table><!--end table-->
    </div><!--end card-body-->
        <div class="card-footer">
            <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
        </div>
</div><!--end card-->
@endsection

@section('javascript')
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
        var table = $('#items-table').DataTable({buttons: ['copy','excel','colvis'],"order": [[ 0, "desc" ]],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
        table.buttons().container().prependTo('#items-table_length');
        table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');
    </script>

    <!-- Get Items by Status -->
    <script type="text/javascript">
        function GetItems(Status) {
            $.ajax({
                type: "GET",
                url: "get-items-by-status",
                data: {'Status' : Status},
                cache:false,
                contentType: false,
                success: function (dataTable) {
                    if (dataTable == ''){
                        $('#items-table').DataTable().clear().draw();
                        SwalMessage('Sorry!','No data available in this category.','info');
                    }
                    else {
                        DataTableItems(dataTable);
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
    </script>

    <!-- Data Table Items -->
    <script type="text/javascript">
        function DataTableItems(Data) {
            var table = $('#items-table').DataTable( {
                buttons: ['copy','excel','colvis'],
                "destroy": true,
                "data": Data,
                "columns": [
                    { "data": "id" },
                    { "data": "serial_number" },
                    { "data": "name" },
                    { "data": "model" },
                    { "data": "quality" },
                    { "data": "status" },
                    { "data": "inventory" },
                    { "data": "id" }],
                "order": [[ 0, "desc" ]],
                "columnDefs": [{"targets": [ 0 ],"visible": false},
                                {"targets": [ 1 ],"render": function ( data, type, row, meta ) {
                                    return '<a href="/ItemDetails/'+data["id"]+'" target="_blank">'+data["serial"]+'</a>';
                                }},
                                {"targets": [ 4 ],"render": function ( data, type, row, meta ) {
                                    switch (data) {
                                        case 1:
                                            return '<span class="badge badge-danger">Bad</span>';
                                            break;
                                        case 2:
                                            return '<span class="badge badge-warning">Not Bad</span>';
                                            break;
                                        case 3:
                                            return '<span class="badge badge-info">Good</span>';
                                            break;
                                        case 4:
                                            return '<span class="badge badge-primary">Very Good</span>';
                                            break;
                                        case 5:
                                            return '<span class="badge badge-success">Exclente</span>';
                                            break;

                                    }
                                }},
                                {"targets": [ 5 ],"render": function ( data, type, row, meta ) {
                                    switch (data["id"]) {
                                        case 2:
                                            return '<span class="badge badge-primary">'+data["status_name"]+'</span>';
                                            break;
                                        case 4:
                                            return '<span class="badge badge-success">'+data["status_name"]+'</span>';
                                            break;
                                        default:
                                            return '<span class="badge badge-secondary">'+data["status_name"]+'</span>';
                                            break;

                                    }
                                }},

                                {"targets": [ 7 ],"render": function ( data, type, row, meta ) {
                                    return '<button type="button" class="btn btn-danger" onclick="printBarcode('+data+')"><i class="fa fa-qrcode"></i></button>';
                                }}
                            ]
            });
            table.buttons().container().prependTo('#items-table_length');
            table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');
        }
    </script>

    <!-- print Barcode -->
    <script type="text/javascript">
        function printBarcode(ItemID) {
            // CSRF Token
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: "/PrintItemBarcode",
                data: {_token: CSRF_TOKEN,'ItemID' : ItemID},
                success: function (response) {
                    Swal({
                        title: "Print Barcode",
                        text: "Do you want printing this barcode ?",
                        imageUrl: "{{ asset('uploads/Temp/qr-item-') }}"+ItemID+".png",
                        imageWidth: 450,
                        imageHeight: 100,
                        imageAlt: "Barcode Item",
                        showCancelButton: true,
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                        showLoaderOnConfirm: true,
                        confirmButtonClass: 'btn btn-primary-gradien',
                        cancelButtonText: '<i class="fa fa-thumbs-down"></i> NO',
                        preConfirm: function (){
                            window.open("{{ asset('uploads/Temp/qr-item-') }}" + ItemID + ".png", "_blank");
                        },
                        allowOutsideClick: false
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
    </script>

@endsection
