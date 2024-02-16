@extends('layouts.app')
@section('css')
    <title>{{__('Users Management')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Users Management')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Users Management')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Page Content-->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Users Management')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="usertbl" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('User ID')}}</th>
                    <th class="text-primary">{{__('User Name')}}</th>
                    <th class="text-primary">{{__('User E-mail')}}</th>
                    <th class="text-primary">{{__('Last Login at')}}</th>
                    <th class="text-primary">{{__('Last Login IP')}}</th>
                    <th class="text-primary">{{__('Department')}}</th>
                    <th class="text-primary">{{__('Permissions')}}</th>
                    <th class="text-primary">{{__('Password')}}</th>
                    <th class="text-primary">{{__('Status')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">
                @foreach ($Users as $User)
                <tr style="text-align: center;">
                    <td>{{$User->id}}</td>
                    <td>{{$User->name}}</td>
                    <td>{{$User->email}}</td>
                    <td>
                        <span class="badge badge-primary">{{\Carbon\Carbon::parse($User->last_login_at)->isoFormat('MMMM Do YYYY, h:mm:ss a')}}</span>
                    </td>
                    <td>
                        @if(empty($User->last_login_ip))
                            {{__('N/A')}}
                        @else
                            {{$User->last_login_ip}}
                        @endif
                    </td>
                    <td>
                        @foreach($User->roles as $Role)
                            {{$loop->iteration }} - {{$Role->blug}} <br>
                        @endforeach
                    </td>
                    <td>
                        <button type="button" class="btn btn-success-gradien" onclick="GoToPage('/EditPermissions/{{$User->id}}')"><i class="fa fa-edit mr-1"></i>{{__('Modify')}}</button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning-gradien" onclick="UserResetPassword({{$User->id}})"><i class="mdi mdi-lastpass mr-1"></i>{{__('Reset')}}</button>
                    </td>
                    <td>
                        @if($User->status == 1)
                            <div class="media-body text-right icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status{{$User->id}}" checked="" onclick="StatusUser('{{$User->id}}')"><span class="switch-state"></span>
                                </label>
                            </div>
                        @else
                            <div class="media-body text-right icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status{{$User->id}}" onclick="StatusUser('{{$User->id}}')"><span class="switch-state"></span>
                                </label>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div><!--end card-body-->
    <div class="card-footer">
        <a href="{{ route('home') }}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
    </div>
</div><!--end card-->

<!-- Modal Reset -->
<form id="form" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="user_id" name="user_id" value="">
    <div class="modal fade" id="modalReset" tabindex="-1" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Reset Password')}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="container col-md-12">
                            <label for="password" class="col-form-label text-right">{{__('New Password')}}</label>
                            <div class="input-group"><div class="input-group-append bg-custom b-0"><span class="input-group-text"><b class="mdi mdi-textbox-password text-primary"></b></span></div><input type="password" class="form-control maxclass" placeholder="~x!82DEge3" id="password" name="password" value="{{ old('password') }}" maxlength="20" autofocus required></div>
                        </div>
                    </div><!--end row-->
                </div>
                <div class="modal-footer">
                    <button type="submit" id="finish" class="btn btn-success-gradien">{{__('Save')}}</button>
                    <button type="button" class="btn btn-secondary-gradien" data-dismiss="modal" onclick="this.form.reset()">{{__('Close')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>
@endsection

@section('javascript')
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('.maxclass').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $('#usertbl').DataTable({order:[0, 'asc'] , "columnDefs": [{ "visible": false, "targets": 0 } ]});
        $('form').parsley();

        function UserResetPassword(UserID) {
            $("#modalReset").modal("show");
            $("#user_id").val(UserID);
        }
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/changepass",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modalReset").modal("hide");
                    $('#finish').attr('disabled', false);
                    $('#form').trigger("reset");
                    NotifyMessage('Success','User password has been changed successfully','success');
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

    <script type="text/javascript">
        function StatusUser(UserID) {
            $.ajax({
                type: "GET",
                url: "{{url('change-user-status')}}",
                data: {'id_user': UserID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'ok') {
                        NotifyMessage('Success','User status active successfully','success');
                    } else{
                        NotifyMessage('Success','User status deactivate','danger');
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
