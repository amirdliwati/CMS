@extends('layouts.app')
@section('css')
    <title>{{__('Notifications')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Notifications')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Notifications')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" id="mark-notifications-read" class="btn btn-outline-success"><i class="fa fa-check-square-o mr-2"></i>{{__('Mark Notifications as Seen')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Notifications')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="notifications-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('Notify ID')}}</th>
                        <th class="text-primary">{{__('Notify Date Time')}}</th>
                        <th class="text-primary">{{__('Type')}}</th>
                        <th class="text-primary">{{__('Message')}}</th>
                        <th class="text-primary">{{__('Action')}}</th>
                        <th class="text-primary">{{__('Action Date')}}</th>
                        <th class="text-primary">{{__('Unseen/Seen')}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($Notifications as $Notification)
                    <tr style="text-align: center;">
                        <td>{{$Notification->id}}</td>
                        <td>
                            <span class="badge badge-primary">{{\Carbon\Carbon::parse($Notification->created_at)->isoFormat('Do MMMM YYYY, h:mm:ss a')}}</span>
                        </td>
                        <td>{{$Notification->data['type']}}</td>
                        <td>{{$Notification->data['message']}}</td>
                        <td>
                            <span class="badge badge-{{$Notification->data['action_color']}}">{{$Notification->data['action']}}</span>
                        </td>
                        <td>
                            <span class="badge badge-info">{{$Notification->data['date']}}</span>
                        </td>
                        <td>
                            <div class="media-body icon-state">
                                @if(empty($Notification->read_at))
                                    <label class="switch"><input type="checkbox" id="status{{$Notification->id}}" onclick="StatusNotify('{{$Notification->id}}')"><span class="switch-state"></span></label>
                                @else
                                    <label class="switch"><input type="checkbox" id="status{{$Notification->id}}" checked="" onclick="StatusNotify('{{$Notification->id}}')"><span class="switch-state"></span></label>
                                @endif
                            </div>
                        </td>
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
        $('#notifications-table').DataTable({order:[0, 'des'] , "columnDefs": [{ "visible": false, "targets": 0 } ]});

        $('#mark-notifications-read').click(function() {
            $.get('/NotificationsAsRead');
            swal({
                title: 'Success',
                text: 'Notifications have been seen successfully.',
                type: 'success',
                preConfirm: function (){location.reload();}
            });
        });
    </script>

    <!-- Status Permitions -->
    <script type="text/javascript">
        function StatusNotify(NotifyID) {
            $.ajax({
                type: "GET",
                url: "{{url('change-status-notify')}}",
                data: {'NotifyID': NotifyID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'Access Denied') {
                        NotifyMessage('Access Denied','You dont have permission.','error');
                    }
                    else if (data == 'Seen') {
                        NotifyMessage('Success','Notify has been seen successfully.','success');
                    }
                    else if(data == 'Unseen') {
                        NotifyMessage('Attention!','Notify has been unseen successfully.','warning');
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
