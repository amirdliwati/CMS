<!-- EMPLOYEE PHONES -->
<div id="phoneval" class="ribbon-wrapper card">
    <div class="card-body">
        <div class="ribbon ribbon-bookmark ribbon-info">{{__('Employee Phones')}}</div>
        <fieldset id="edu" style="display: block;">
            <div class="repeater-custom-show-hide">
                <div data-repeater-list="phones">
                    <div data-repeater-item="">
                        <div class="form-group row  d-flex align-items-end">
                            <div class="col-md-2 ">
                                <label class="col-form-label text-right">{{__('Phone Type')}}</label>
                                <select class="form-control" id="phone_type" name="phone_type" autofocus required>
                                    <option selected value="{{ old('phone_type') }}">
                                        @if( old('phone_type') == "" )
                                        {{__('Select')}}
                                            @elseif(old('phone_type') == 1 )
                                            {{ __('Mobile') }}
                                            @elseif(old('phone_type') == 2 )
                                            {{ __('Home')}}
                                            @elseif(old('phone_type') == 3 )
                                            {{ __('Work')}}
                                            @elseif(old('phone_type') == 4 )
                                            {{ __('Other')}}
                                        @endif
                                    </option>
                                    <option value="1">{{__('Mobile')}}</option>
                                    <option value="2">{{__('Home')}}</option>
                                    <option value="3">{{__('Work')}}</option>
                                    <option value="4">{{__('Other')}}</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="col-form-label text-right">{{__('Phone Number')}}</label>
                                <div class="input-group">
                                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="fa fa-mobile"></i></span></div>
                                    <input type="tel" class="form-control mx" id="number" name="number" value="{{ old('number') }}" autocomplete="number" placeholder="+964999999999" autofocus maxlength="20" required>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-md-3">
                                <span id="delPhone" hidden name="delPhone" data-repeater-delete=""></span>
                                <span name="index" class="btn btn-danger-gradien btn-md" onclick="fundelphone(this)">
                                    <span class="far fa-trash-alt mr-1"></span><i class="fa fa-trash-o mr-2"></i>{{__('Delete')}}
                                </span>
                                <script type="text/javascript">
                                    function fundelphone(el){
                                        var index = $(el).attr('name').match(/\d+/);
                                        if ($('#count_phone').val() == 1) {
                                            SwalMessage('Warning!','The Employee should have one phone at least.','warning');
                                        }else {
                                            swal({
                                                title: 'Are you sure?',
                                                text: "You won't be able to revert this!",
                                                type: 'warning',
                                                showCloseButton: true,
                                                showCancelButton: true,
                                                confirmButtonClass: 'btn btn-primary-gradien',
                                                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Confirm',
                                                cancelButtonText: '<i class="fa fa-thumbs-down"></i> Cancel',
                                                preConfirm: function () {
                                                    $('span[name="phones['+index+'][delPhone]"').click();
                                                    var x =  document.getElementById("count_phone").value = parseInt( document.getElementById("count_phone").value ) - 1 ;
                                                }
                                            });
                                        }
                                    }
                                </script>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                    <input type="hidden" id="count_phone" name="count_phone" value="1">
                </div><!--end repet-list-->
                <div class="form-group row mb-0">
                    <div class="col-md-12">
                        <span data-repeater-create="" class="btn btn-light btn-md" onclick="funaddphone()">
                            <span class="fa fa-plus"></span> {{__('Add Another Phone')}}
                        </span>
                        <script type="text/javascript">
                            function funaddphone(){
                              var x =  document.getElementById("count_phone").value = parseInt( document.getElementById("count_phone").value )+ 1 ;
                            setTimeout(function (){$('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning"})},30);}
                        </script>
                    </div><!--end col-->
                </div><!--end row-->
            </div> <!--end repeter-->
        </fieldset><!--end fieldset-->
    </div>
</div><!-- end PHONES -->


