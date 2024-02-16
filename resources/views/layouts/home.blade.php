@extends('layouts.app')

@section('css')
    <title>{{__('Home')}}</title>

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">

@endsection

@section('breadcrumb')
    <h3>{{__('Home')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Home')}}</li>
    </ol>
@endsection

@section('bookmark')
    <li><a href="{{ route('MyAttendances') }}" data-container="body" data-toggle="popover" data-placement="top" title="{{__('My Attendances')}}" data-original-title=""><i class="fa fa-calendar text-warning"></i></a></li>
    <li><a href="{{ route('MyPayrolls') }}" data-container="body" data-toggle="popover" data-placement="top" title="{{__('My Payrolls')}}" data-original-title=""><i class="fa fa-usd text-primary"></i></a></li>
    <li><a href="{{ route('MyLeaves') }}" data-container="body" data-toggle="popover" data-placement="top" title="{{__('My Leaves')}}" data-original-title=""><i class="fa fa-arrow-circle-up text-info"></i></a></li>
    <li><a href="{{ route('MyIncrements') }}" data-container="body" data-toggle="popover" data-placement="top" title="{{__('My Increments')}}" data-original-title=""><i class="fa fa-plus-square-o text-success"></i></a></li>
    <li><a href="{{ route('MyLoans') }}" data-container="body" data-toggle="popover" data-placement="top" title="{{__('My Loans')}}" data-original-title=""><i class="fa fa-retweet text-warning"></i></a></li>
    <li><a href="{{ route('MyDeductions') }}" data-container="body" data-toggle="popover" data-placement="top" title="{{__('My Deductions')}}" data-original-title=""><i class="fa fa-minus-square-o text-danger"></i></a></li>
@endsection

@section('content')
    <!-- Page Content-->
    <div class="row ui-sortable" id="draggableMultiple">
        @if (!empty(Auth::user()->roles->where('title','Admin_role')->first()))
            @include('admin/Home/Home')
            @include('hr/Home/Home')
            @include('financial/Home/Home')
            @include('inventory/Home/Home')

        @else ()
            @foreach (Auth::user()->roles->where('active',1) as $Role)
                @if($Role->title == 'HR_role') @include('hr/Home/Home') @endif
                @if($Role->title == 'Financial_role') @include('financial/Home/Home') @endif
                @if($Role->title == 'Inventory_role') @include('inventory/Home/Home') @endif
            @endforeach
        @endif
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('js/jquery.ui.min.js') }}"></script>
    <script src="{{ asset('js/dragable/sortable.js') }}"></script>
    <script src="{{ asset('js/dragable/sortable-custom.js') }}"></script>

    <!-- charts apex-->
    <script src="{{ asset('js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('js/chart/apex-chart/chart-custom.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>

    @if (!empty(Auth::user()->roles->where('title','Admin_role')->first()))
        @include('admin/Home/JSHome')
        @include('hr/Home/JSHome')
        @include('financial/Home/JSHome')
        @include('inventory/Home/JSHome')

    @else ()
        @foreach (Auth::user()->roles->where('active',1) as $Role)
            @if($Role->title == 'HR_role') @include('hr/Home/JSHome') @endif
            @if($Role->title == 'Financial_role') @include('financial/Home/JSHome') @endif
            @if($Role->title == 'Inventory_role') @include('inventory/Home/JSHome') @endif
        @endforeach
    @endif

@endsection
