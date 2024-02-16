@extends('layouts.app')
@section('css')
    <title>{{__('User Activity Log')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('User Activity Log')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('User Activity Log')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('User Activity Log')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="LogTbl" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('id')}}</th>
                        <th class="text-primary">{{__('Date Time')}}</th>
                        <th class="text-primary">{{__('User Name')}}</th>
                        <th class="text-primary">{{__('Department')}}</th>
                        <th class="text-primary">{{__('Title')}}</th>
                        <th class="text-primary">{{__('Operating Name')}}</th>
                        <th class="text-primary">{{__('Operating Code')}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($logs as $log)
                    <tr style="text-align: center;">
                        <td>{{$log->id}}</td>
                        <td><span class="badge badge-info">{{\Carbon\Carbon::parse($log->created_at)->isoFormat('Do MMMM YYYY, h:mm:ss a')}}</span></td>
                        <td><i class="fas fa-user-tie text-purple mr-2"></i>{{$log->user->name}}</td>
                        <td>{{$log->user->employee->position->department->name}}</td>
                        <td>{{$log->title}}</td>
                        <td>{{$log->operating_name}}</td>
                        <td>{{$log->operating_code}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('home') }}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
    </div>
</div>
@endsection
@section('javascript')
    <!-- Required datatable js -->
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('#LogTbl').DataTable({order:[0, 'des'] , "columnDefs": [{ "visible": false, "targets": 0 } ]});
    </script>
@endsection
