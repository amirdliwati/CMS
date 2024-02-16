<!--modal Status-->
<form id="form3" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <!-- Modal view -->
    <div class="modal fade" id="modalStatus" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="viewModel">{{__('Cancel Receipt')}}</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="receipt_id" name="receipt_id" value="">
                    <div class="row form-material">
                        <div class="col-md-12">
                            <label for="notes_cancel" class="col-form-label text-right">{{__('Notes About Cancel')}}</label>
                            <textarea type="text" class="form-control mx" id="notes_cancel" name="notes_cancel" autocomplete="notes_cancel" placeholder="" autofocus rows="5" maxlength="200" required></textarea>
                        </div> <!-- end col -->
                    </div><!--end row-->
                </div> <!--end modal-body-->
                <div class="modal-footer">
                    <button type="submit" id="finish3" class="btn btn-success-gradien">{{__('Save')}}</button>
                    <button type="button" class="btn btn-secondary-gradien" data-dismiss="modal" onclick="cancel()">{{__('Close')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>
