@extends('layouts.app')
@section('css')
    <title>{{__('Emails')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Emails')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Emails')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" onclick="GoToPage('/EmailCompose')"><i class="fa fa-pencil mr-2"></i>{{__('Compose')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-success border-3">
        <div class="row">
            <div class="col-md-2">

            </div> <!-- end col -->
        </div><!-- end row -->
        @include('layouts/button_card')
    </div>
    <div class="card-body">
        <div class="row">
            <table id="email-table" class="table table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('ID Email')}}</th>
                    <th class="text-primary">{{__('Trainee Name')}}</th>
                    <th class="text-primary">{{__('Subject')}}</th>
                    <th class="text-primary">{{__('Date')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">
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
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});

        var table = $('#email-table').DataTable({buttons: ['copy','excel','colvis'],"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
        table.buttons().container().prependTo('#email-table_length');
        table.buttons().container().siblings("label").prepend('&nbsp;&nbsp;&nbsp;');
    </script>
@endsection
