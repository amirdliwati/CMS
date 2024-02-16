<!-- IN CASE OF EMERGENCY -->
<div id="phoneval" class="ribbon-wrapper card">
    <div class="card-body">
        <div class="ribbon ribbon-bookmark ribbon-secondary">{{__('In Case of Emergency')}}</div>
        <div class="row">
            <div class="col-md-2">
                <label for="relationship" class="col-form-label text-right">{{__('Relationship')}}</label>
                <select class="form-control mb-3 custom-select" style="width: 100%; height:36px;" id="relationship" name="relationship" autocomplete="relationship" autofocus required>
                    <option selected="selected" value="{{ old('relationship') }}">
                        @if( old('relationship') == "" )
                        {{__('Select')}}
                            @elseif(old('relationship') == 1 )
                            {{ __('Father') }}
                            @elseif(old('relationship') == 2 )
                            {{ __('Brother')}}
                            @elseif(old('relationship') == 3 )
                            {{ __('Sister')}}
                            @elseif(old('relationship') == 4 )
                            {{ __('Husband')}}
                            @elseif(old('relationship') == 5 )
                            {{ __('Wife')}}
                            @elseif(old('relationship') == 6 )
                            {{ __('Friend')}}
                        @endif
                    </option>
                    <option value="1">{{__('Father')}}</option>
                    <option value="2">{{__('Brother')}}</option>
                    <option value="3">{{__('Sister')}}</option>
                    <option value="4">{{__('Husband')}}</option>
                    <option value="5">{{__('Wife')}}</option>
                    <option value="6">{{__('Friend')}}</option>
                </select>
            </div>
            <div class="col-md-5">
                <label for="fname_emer" class="col-form-label text-right">{{__('First Name')}}</label>
                <input type="text" class="form-control mx" id="fname_emer" name="fname_emer" value="{{ old('fname_emer') }}" autocomplete="fname_emer" placeholder="First Name" autofocus maxlength="25" required >
            </div> <!-- end col -->
            <div class="col-md-5">
                <label for="lname_emer" class="col-form-label text-right">{{__('Last Name')}}</label>
                <input type="text" class="form-control mx" id="lname_emer" name="lname_emer" value="{{ old('lname_emer') }}" autocomplete="lname_emer" placeholder="Last Name" autofocus maxlength="25" required>
            </div> <!-- end col -->
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <label for="house_phone" class="col-form-label text-right">{{__('House Phone')}}</label>
                <div class="input-group">
                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-phone-classic text-primary"></i></span></div>
                    <input type="tel" class="form-control mx" id="house_phone" name="house_phone" value="{{ old('house_phone') }}" autocomplete="house_phone" placeholder="Phone" autofocus maxlength="20" required>
                </div>
            </div> <!-- end col -->
            <div class="col-md-6">
                <label for="mobile_phone" class="col-form-label text-right">{{__('Mobile Phone')}}</label>
                <div class="input-group">
                    <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="fa fa-mobile"></i></span></div>
                    <input type="tel" class="form-control mx" id="mobile_phone" name="mobile_phone" value="{{ old('mobile_phone') }}" autocomplete="mobile_phone" placeholder="Phone" autofocus maxlength="20" required>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div><!-- end EMERGENCY -->
