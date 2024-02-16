<!--modal Add Receipt-->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <!-- Modal view -->
    <div class="modal fade" id="modaladdReceipt" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel"> </h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="receipt_type" name="receipt_type" value="">
                    <div class="row form-material">
                        <div class="col-md-3">
                            <label for="currency" class="col-form-label text-right">{{__('Choose Currency')}}</label>
                            <select class="form-control" id="currency" name="currency" autofocus required>
                                @foreach($Currencies as $Currency)
                                    <option value="{{$Currency->id}}"> {{$Currency->name}} -> {{$Currency->code}} -> {{$Currency->symbol}} -> {{$Currency->point}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                        <label for="date" class="col-form-label text-right">{{__('Date')}}</label>
                            <div class="input-group">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                <input type="text" class="form-control datem initDate" placeholder="mm/dd/yyyy" id="date" name="date" value="{{ old('date') }}" autocomplete="date" readonly required>
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-6">
                            <label for="customer" class="col-form-label text-right">{{__('Full Name')}}</label>
                            <input type="text" class="form-control mx" id="customer" name="customer" placeholder="~Name" value="{{ old('customer') }}" maxlength="80" autocomplete="customer" required>
                        </div><!--end col-->
                    </div><!--end row-->
                        <br>
                    <div class="row form-material">
                        <div class="col-md-4">
                            <label for="amount" class="col-form-label text-right">{{__('Amount')}}</label>
                            <input class="form-control ts" type="number" step="0.01" name="amount" id="amount" value="0" placeholder="~0.0" autocomplete="amount" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-8">
                            <label for="amount_write" class="col-form-label text-right">{{__('Written Amount')}}</label>
                            <input class="form-control text-purple" type="text" name="amount_write" id="amount_write" value="{{ old('amount_write') }}" style="font-weight: bold;" placeholder="~Write Amount" autocomplete="amount_write" autofocus>
                        </div> <!-- end col -->
                    </div><!--end row-->
                        <br>
                    <div class="row form-material">
                        <div class="col-md-3">
                            <label for="account_id" class="col-form-label text-right">{{__('Choose Account')}}</label>
                            <select class="form-control" id="account_id" name="account_id" autofocus required>
                                <option value="" selected></option>
                                @foreach($Accounts as $Account)
                                    <option value="{{$Account->id}}"> {{$Account->account_number}} - {{$Account->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-9">
                            <label for="notes" class="col-form-label text-right">{{__('Description')}}</label>
                            <input type="text" class="form-control" id="notes" name="notes" placeholder="~Description" value="{{ old('notes') }}" autocomplete="notes" required>
                        </div><!--end col-->
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
