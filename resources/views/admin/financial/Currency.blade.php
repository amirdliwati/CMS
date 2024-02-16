@extends('layouts.app')
@section('css')
    <title>{{__('Currencies')}}</title>
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-extension.css') }}">
@endsection
@section('breadcrumb')
    <h3>{{__('Currency Managment')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Currencies')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Currencies')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <table id="currency-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Currency ID')}}</th>
                    <th class="text-primary">{{__('Name')}}</th>
                    <th class="text-primary">{{__('Code')}}</th>
                    <th class="text-primary">{{__('Symbol')}}</th>
                    <th class="text-primary">{{__('Point')}}</th>
                    <th class="text-primary">{{__('Dollar Selling')}}</th>
                    <th class="text-primary">{{__('Dollar Buy')}}</th>
                    <th class="text-primary">{{__('Last Update')}}</th>
                    <th class="text-primary">{{__('Exchange')}}</th>
                    <th class="text-primary">{{__('Status')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($Currencies as $Currency)
                <tr style="text-align: center;">
                    <td>{{$Currency->id}}</td>
                    <td>{{$Currency->name}}</td>
                    <td>{{$Currency->code}}</td>
                    <td>{{$Currency->symbol}}</td>
                    @if(empty($Currency->point))
                        <td>N/A</td>
                    @else
                        <td>{{$Currency->point}}</td>
                    @endif

                    @if(empty($Currency->dollar_selling))
                        <td>N/A</td>
                    @else
                        <td>{{$Currency->dollar_selling}}</td>
                    @endif

                    @if(empty($Currency->dollar_buy))
                        <td>N/A</td>
                    @else
                        <td>{{$Currency->dollar_buy}}</td>
                    @endif

                    <td><span class="badge badge-info">{{\Carbon\Carbon::parse($Currency->updated_at)->isoFormat('Do MMMM YYYY, h:mm:ss a')}}</span></td>

                    <td>
                        @if($Currency->id == 2)
                            <button  type="button" class="btn btn-secondary-gradien"><i class="mdi mdi-find-replace"></i></button>
                        @else
                            <button  type="button" class="btn btn-warning-gradien" onclick="Exchange({{$Currency->id}})"><i class="mdi mdi-find-replace"></i></button>
                        @endif
                    </td>

                    <td>
                        @if ($Currency->status == 1)
                            <div class="media-body text-right icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status{{$Currency->id}}" checked="" onclick="Status({{$Currency->id}})"><span class="switch-state"></span>
                                </label>
                            </div>
                        @else
                            <div class="media-body text-right icon-state">
                                <label class="switch">
                                    <input type="checkbox" id="status{{$Currency->id}}" onclick="Status({{$Currency->id}})"><span class="switch-state"></span>
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

<!--modal Exchange-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="currency_id" name="currency_id" value="">
    <div class="modal fade" id="modalExchange" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel"><i class="mdi mdi-find-replace text-primary mr-2"></i> {{__('Exchange')}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="dollar_selling" class="col-form-label text-right">{{__('Dollar Selling')}}</label>
                                    <input class="form-control @error('dollar_selling') is-invalid @enderror ts" type="number" step="0.01"  name="dollar_selling" id="dollar_selling" value="0" placeholder="~0.0" autocomplete="dollar_selling" autofocus required>
                                </div> <!-- end col -->
                                <div class="col-md-6">
                                    <label for="dollar_buy" class="col-form-label text-right">{{__('Dollar Buy')}}</label>
                                    <input class="form-control @error('dollar_buy') is-invalid @enderror ts" type="number" step="0.01" name="dollar_buy" id="dollar_buy" value="0" placeholder="~0.0" autocomplete="dollar_buy" autofocus required>
                                </div> <!-- end col -->
                            </div><!--end row-->
                        </div><!-- end col -->
                    </div><!--end row-->
                </div> <!--end modal-body-->
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
    <script src="{{ asset('js/touchspin/touchspin.js') }}"></script>
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $('#currency-table').DataTable({order:[0, 'desc'] , "columnDefs": [{ "visible": false, "targets": 0 } ]});
        $('.ts').TouchSpin({min: 0, max: 9999999999, step: 0.1, decimals: 2, boostat: 5, maxboostedstep: 10, buttondown_class: 'btn btn-info-gradien', buttonup_class: 'btn btn-primary-gradien'});
        function Exchange(CurrencyID) {
            $('#currency_id').val(CurrencyID);
            $("#modalExchange").modal("show");
        }
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/Currency",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#modalExchange").modal("hide");
                    swal({
                        title: 'Success',
                        text: 'The currency has been edited successfully.',
                        type: 'success',
                        preConfirm: function (){location.reload();}
                    });
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
        function Status(CurrencyID) {
            $.ajax({
                type: "GET",
                url: "/ChangeStatusCurrency",
                data: {'CurrencyID': CurrencyID},
                cache:false,
                contentType: false,
                success: function (data) {
                    if (data == 'success') {
                        NotifyMessage('Success','The currency has been changed status to on','success');
                    } else{
                        NotifyMessage('Error','The currency has been changed status to off','danger');
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
