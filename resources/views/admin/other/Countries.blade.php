@extends('layouts.app')
@section('css')
    <title>{{__('Countries')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Country Managment')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Countries')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Countries')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="country-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Country ID')}}</th>
                    <th class="text-primary">{{__('Name')}}</th>
                    <th class="text-primary">{{__('ISO2')}}</th>
                    <th class="text-primary">{{__('ISO3')}}</th>
                    <th class="text-primary">{{__('Nationality')}}</th>
                    <th class="text-primary">{{__('Currency')}}</th>
                    <th class="text-primary">{{__('Status')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($Countries as $Country)
                <tr style="text-align: center;">
                    <td>{{$Country->id}}</td>
                    <td>{{$Country->name}}</td>
                    <td>{{$Country->iso2}}</td>
                    <td>{{$Country->iso3}}</td>
                    <td>{{$Country->nationality}}</td>
                    <td>{{$Country->currency}}</td>
                    <td>
                        @if ($Country->status == 1)
                            <div class="media-body text-right icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status{{$Country->id}}" checked="" onclick="Status({{$Country->id}})"><span class="switch-state"></span>
                                </label>
                            </div>
                        @else
                            <div class="media-body text-right icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status{{$Country->id}}" onclick="Status({{$Country->id}})"><span class="switch-state"></span>
                                </label>
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
            <a href="{{ route('home') }}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
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
        $('#country-table').DataTable({order:[0, 'desc'] , "columnDefs": [{ "visible": false, "targets": 0 } ]});
    </script>

    <script type="text/javascript">
        function Status(CountryID) {
            $.ajax({
                type: "GET",
                url: "/ChangeStatusCountry",
                data: {'CountryID': CountryID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'success') {
                        NotifyMessage('Success','The Country has been changed status to on','success');
                    } else{
                        NotifyMessage('Error','The Country has been changed status to off','danger');
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
@endsection
