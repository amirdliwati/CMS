<!-- EMPLOYEE ADDRESSES -->
<div id="phoneval" class="ribbon-wrapper card">
    <div class="card-body">
        <div class="ribbon ribbon-bookmark ribbon-primary">{{__('Employee Addresses')}}</div>
        <div class="form-group row  d-flex align-items-end">
            <div class="col-md-2">
                <div class="form-group row">
                <label class="col-form-label text-right"> <i class="fas fa-map-marker-alt text-primary"> </i>  {{__('Permanent Address')}}</label>
                </div>
                <div class="form-group row"></div>
            </div>
            <div class="col-md-4">
                <div class="col-md-12">
                    <label for="country" class="col-form-label text-right">{{__('Country')}}</label>
                    <select class="form-control select2" id="country" name="country" autofocus required>
                        <option selected="selected" value="{{ old('country') }}"></option>
                        @foreach($Countries as $country)
                            <option value="{{$country->id}}"> {{$country->name}}</option>
                        @endforeach
                    </select>
                </div> <!-- end col -->
                <div class="col-md-12">
                    <label for="state" class="col-form-label text-right">{{__('City')}}</label>
                    <select class="form-control select2" id="state" name="state" autofocus required>
                        <option selected="selected" value="{{ old('state') }}"></option>
                    </select>
                </div> <!-- end col -->
            </div>
            <div class="col-md-5">
                <label for="address" class="col-form-label text-right">{{__('Address')}}</label>
                <textarea type="text" class="form-control mx" id="address" name="address" value="{{ old('address') }}" autocomplete="address" placeholder="Address" autofocus rows="5" maxlength="200" required></textarea>
            </div> <!-- end col -->
        </div><!--end row-->
        <div id="myDIV" style="display: none;">
            <div class="form-group row  d-flex align-items-end">
                <div class="col-md-2">
                    <div class="form-group row"></div>
                    <div class="form-group row">
                        <label class="col-form-label text-right"> <i class="fas fa-map-marker-alt text-primary"> </i>  {{__('Temporary Address')}}</label>
                    </div>
                    <div class="form-group row"></div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-12">
                        <label for="country2" class="col-form-label text-right">{{__('Country')}}</label>
                        <select class="form-control select2" id="country2" name="country2" autocomplete="country2" autofocus disabled="">
                            <option selected="selected" value="{{ old('country2') }}"></option>
                            @foreach($Countries as $country2)
                                <option value="{{$country2->id}}"> {{$country2->name}}</option>
                            @endforeach
                        </select>
                    </div> <!-- end col -->
                    <div class="col-md-12">
                        <label for="state2" class="col-form-label text-right">{{__('City')}}</label>
                        <select class="form-control select2" id="state2" name="state2" autofocus disabled="">
                            <option selected="selected" value="{{ old('state2') }}"></option>
                        </select>
                    </div> <!-- end col -->
                </div>
                <div class="col-md-5">
                    <label for="address2" class="col-form-label text-right">{{__('Address')}}</label>
                    <textarea type="text" class="form-control @error('address2') is-invalid @enderror mx" id="address2" name="address2" value="{{ old('address2') }}" autocomplete="address2" placeholder="Address" autofocus rows="5" maxlength="200" disabled=""></textarea>
                </div> <!-- end col -->
                <div class="col-md-1">
                        <span data-repeater-delete="" id="btnReset" class="btn btn-danger-gradien btn-sm" onclick="fundeladdress()">
                            <span class="far fa-trash-alt mr-1"></span> {{__('Delete')}}
                        </span>
                        <script type="text/javascript">
                            function fundeladdress(){
                                $("#myDIV").fadeOut("3000");
                              document.getElementById("myDIV").style.display = "none";
                              document.getElementById("ss").style.display = "block";
                              document.getElementById("country2").disabled = true;
                              document.getElementById("state2").disabled = true;
                              document.getElementById("address2").value = "";
                              document.getElementById("address2").disabled = true;}
                        </script>
                    <div class="form-group row"></div>
                    <div class="form-group row"></div>
                </div><!--end col-->
            </div><!--end row-->
        </div>
        <div id="ss" class="form-group row mb-0" >
            <div class="col-sm-12">
                <span data-repeater-create="" class="btn btn-light btn-md" onclick="funaddressadd()">
                    <span class="fa fa-plus"></span> {{__('Add Temp Address')}}
                </span>
                <script type="text/javascript">
                    function funaddressadd(){$("#myDIV").fadeIn("slow");
                        document.getElementById("country2").disabled = false;
                        document.getElementById("state2").disabled = false;
                        document.getElementById("address2").disabled = false;
                     document.getElementById("myDIV").style.display = "block";
                     document.getElementById("ss").style.display = "none";

                    }
                </script>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div><!-- end address -->
