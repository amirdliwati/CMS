@extends('layouts.app')
@section('css')
    <title>{{__('User Permissions')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Modifiy Permissions')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Modifiy Permissions')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Page Content-->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('User Permissions')}} ({{$User->name}})</h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row ">
            <table id="usertbl" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th>{{__('id')}}</th>
                        <th class="text-purple" style="font-weight: bold;" colspan="3">{{__('Custom Role for')}} ({{$User->name}})</th>
                    </tr>
                    <tr style="text-align: center;">
                        <th></th>
                        <th class="text-primary" colspan="1">{{__('Permission')}}</th>
                        <th class="text-primary" colspan="1">{{__('Department')}}</th>
                        <th class="text-primary" colspan="1">{{__('Status')}}</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    @foreach($Roles as $Role)
                        @foreach ($Role->permissions as $per)
                        <tr style="text-align: center;">
                            <td>{{$per->id}}</td>
                            <td>{{$per->content}}</td>
                            <td>{{$Role->blug}}</td>

                            <td>
                                @foreach($User->roles->where('title',$Role->title) as $UserRole)
                                    @if ($UserRole->permissions->where('title', $per->title) == '[]')
                                        <div class="media-body icon-state">
                                            <label class="switch">
                                                <input type="checkbox" id="status{{$per->id}}" onclick="StatusPer('{{$per->id}}','{{$UserRole->id}}')"><span class="switch-state"></span>
                                            </label>
                                        </div>
                                    @else
                                        <div class="media-body icon-state">
                                            <label class="switch">
                                                <input type="checkbox" id="status{{$per->id}}" checked="" onclick="StatusPer('{{$per->id}}','{{$UserRole->id}}')"><span class="switch-state"></span>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div><!--end card-body-->
    <div class="card-footer">
        <a href="{{ route('ManageUsers') }}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Manage Users')}}</button></a>
    </div>
</div><!--end card-->
@endsection

@section('javascript')
    <!-- Required datatable js -->
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>

    <!-- Other Scripts -->
        <script type="text/javascript">
        $('#usertbl').DataTable({order:[0, 'desc'] , "columnDefs": [{ "visible": false, "targets": 0 } ]});
    </script>

    <script type="text/javascript">
        function StatusPer(PermID,RoleID) {
            $.ajax({
                type: "GET",
                url: "{{url('change-user-Permission')}}",
                data: {'PermID': PermID , 'RoleID': RoleID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'ok') {
                        NotifyMessage('Success','Permission has been granted successfully.','success');
                    } else{
                        NotifyMessage('Attention!','Permission has been removed.','danger');
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
@endsection
