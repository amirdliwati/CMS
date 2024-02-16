@extends('layouts.app')
@section('css')
    <title>{{__('My Increments')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('My Increments')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('My Increments')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Increments')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="increments-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Increment ID')}}</th>
                    <th class="text-primary">{{__('Date')}}</th>
                    <th class="text-primary">{{__('Amount')}}</th>
                    <th class="text-primary">{{__('Salary')}}</th>
                    <th class="text-primary">{{__('Payroll')}}</th>
                    <th class="text-primary">{{__('Notes/Reason')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($Increments as $Increment)
                <tr style="text-align: center;">
                    <td>{{$Increment->id}}</td>
                    <td><span class="badge badge-info"> {{\Carbon\Carbon::parse($Increment->date)->isoFormat('Do MMMM YYYY')}} </span></td>
                    <td><span class="badge badge-success">{{$Employee->currencie->symbol}}</span> {{$Increment->amount}}</td>
                    <td><span class="badge badge-success">{{$Employee->currencie->symbol}}</span> {{$Increment->salary->basic}}</td>
                    @if(empty($Increment->payroll->date))
                    <td><span class="badge badge-danger">Not added to payroll yet</span></td>
                    @else
                        <td><span class="badge badge-success">{{$Increment->payroll->date}}</span></td>
                    @endif
                    <td>{{$Increment->notes}}</td>
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
@endsection
@section('javascript')
    <!-- Required datatable js -->
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('#increments-table').DataTable({"columnDefs": [{"targets": [ 0 ],"visible": false,"searchable": false},]});
    </script>
@endsection
