<div class="row">
    <div class="col-md-2">
        <label for="prefix" class="col-form-label text-right">{{__('Prefix')}}</label>
        <select class="form-control mb-3" id="prefix" name="prefix" autofocus tabindex="0" required>
            <option selected="selected" value="{{$Employee->prefix}}">
                @if($Employee->prefix == 1 )
                    {{ __('Mr.') }}
                    @elseif($Employee->prefix == 2 )
                    {{ __('Ms.')}}
                    @elseif($Employee->prefix == 3 )
                    {{ __('Mrs.') }}
                    @elseif($Employee->prefix == 4 )
                    {{ __('Dr.') }}
                    @elseif($Employee->prefix == 5 )
                    {{ __('Eng.') }}
                @endif
            </option>
            <option value="1">{{__('Mr.')}}</option>
            <option value="2">{{__('Ms.')}}</option>
            <option value="3">{{__('Mrs.')}}</option>
            <option value="4">{{__('Dr.')}}</option>
            <option value="5">{{__('Eng.')}}</option>
        </select>
    </div> <!-- end col -->
    <div class="col-md-5">
        <label for="first_name" class="col-form-label text-right">{{__('First Name')}}</label>
        <input type="text" class="form-control mx" id="first_name" name="first_name" value="{{$Employee->first_name}}" autocomplete="first_name" placeholder="~John" autofocus maxlength="25" required>
    </div> <!-- end col -->
    <div class="col-md-5">
        <label for="last_name" class="col-form-label text-right">{{__('Last Name')}}</label>
        <input type="text" class="form-control mx" id="last_name" name="last_name" value="{{$Employee->last_name}}" autocomplete="last_name" placeholder="~Smith" autofocus maxlength="25" required>
    </div> <!-- end col -->
</div> <!-- end row -->
<div class="row">
    <div class="col-md-5 offset-md-2">
        <div class="col-md-12">
            <label for="middle_name" class="col-form-label text-right">{{__('Middle Name')}} (Optional)</label>
            <input type="text" class="form-control mb-2 mx" id="middle_name" name="middle_name" value="{{$Employee->middle_name}}" autocomplete="middle_name" placeholder="~Samer" autofocus maxlength="25">
        </div> <!-- end col -->
        <div class="col-md-12">
            <label for="mother" class="col-form-label text-right">{{__('Mother Name')}} (Optional)</label>
            <input type="text" class="form-control mb-2" id="mother" name="mother" value="{{$Employee->mother}}" autocomplete="mother" placeholder="~Samar" autofocus maxlength="25">
        </div> <!-- end col -->
        <div class="col-md-12">
            <label for="gender" class="col-form-label text-right">{{__('Gender')}}</label>
            <select class="form-control mb-3" id="gender" name="gender" autofocus required>
                <option selected="selected" value="{{$Employee->gender}}">
                    @if($Employee->gender == 1 )
                        {{ __('Male') }}
                        @elseif($Employee->gender == 2 )
                        {{ __('Female')}}
                    @endif
                </option>
                <option value="1">{{__('Male')}}</option>
                <option value="2">{{__('Female')}}</option>
            </select>
        </div> <!-- end col -->
        <div class="col-md-12">
            <label for="marital_status" class="col-form-label text-right">{{__('Marital Status')}}</label>
            <select class="form-control mb-1" id="marital_status" name="marital_status" autofocus required>
                <option selected="selected" value="{{$Employee->marital_status}}">
                    @if($Employee->marital_status == 1 )
                        {{ __('Single') }}
                        @elseif($Employee->marital_status == 2 )
                        {{ __('Married')}}
                        @elseif($Employee->marital_status == 3 )
                        {{ __('Divorced')}}
                        @elseif($Employee->marital_status == 4 )
                        {{ __('Widowed')}}
                    @endif
                </option>
                <option value="1">{{__('Single')}}</option>
                <option value="2">{{__('Married')}}</option>
                <option value="3">{{__('Divorced')}}</option>
                <option value="4">{{__('Widowed')}}</option>
            </select>
        </div> <!-- end col -->
    </div> <!-- end col -->
     <div class="col-md-5">
        <div id="perpic">
            <label for="emp_image" class="col-form-label text-right">{{__('Personal Image')}}</label>
            <p class="text-muted mb-4">{{__('File should be up to 3 Megabytes')}}.</p>
            <input type="file" class="dropify form-control @error('emp_image') is-invalid @enderror" id="emp_image" name="emp_image" data-max-file-size="3M" data-show-loader="true" accept="image/*" data-allowed-file-extensions="png jpg jpeg gif bmp tiff svg" data-default-file="{{asset($Employee->emp_image)}}">
        </div><!--end card-body-->
    </div><!--end col-->
</div>
<div class="row">
    <div class=" offset-md-2 col-md-5">
        <label for="birthdate" class="col-form-label text-right">{{__('Birth Date')}}</label>
        <div class="input-group">
            <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
            <input type="text" class="form-control datem" placeholder="mm/dd/yyy" id="birthdate" name="birthdate" value="{{$Employee->birthdate}}" autocomplete="birthdate" autofocus readonly required>
        </div>
    </div><!-- end col -->
    <div class="col-md-5">
        <i class="mdi mdi-water mr-0 text-danger"></i><label for="blood" class="col-form-label text-right">{{__('Blood Type')}}</label>
        <select class="select2 form-control mb-3" id="blood" name="blood" autofocus required>
            <option selected="selected" value="{{$Employee->blood}}">
                @if ($Employee->blood == 1 )
                    {{ __('A+') }}
                    @elseif($Employee->blood == 2 )
                    {{ __('B+')}}
                    @elseif($Employee->blood == 3 )
                    {{ __('AB+')}}
                    @elseif($Employee->blood == 4 )
                    {{ __('O+')}}
                    @elseif($Employee->blood == 5 )
                    {{ __('A-')}}
                    @elseif($Employee->blood == 6 )
                    {{ __('B-')}}
                    @elseif($Employee->blood == 7 )
                    {{ __('AB-')}}
                    @elseif($Employee->blood == 8 )
                    {{ __('O-')}}
                @endif
            </option>
            <option value="1">{{__('A+')}}</option>
            <option value="2">{{__('B+')}}</option>
            <option value="3">{{__('AB+')}}</option>
            <option value="4">{{__('O+')}}</option>
            <option value="5">{{__('A-')}}</option>
            <option value="6">{{__('B-')}}</option>
            <option value="7">{{__('AB-')}}</option>
            <option value="8">{{__('O-')}}</option>
        </select>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-4">
        <label for="Nationality" class="col-form-label text-right">{{__('Nationality')}}</label>
        <select class="select2 form-control mb-3" id="Nationality" name="Nationality" autofocus required>
            <option selected="selected" value="{{$Employee->country_id}}">{{ $Employee->countrie->nationality}}</option>
            </option>
            @foreach($Countries as $Countrie)
                <option value="{{$Countrie->id}}">{{$Countrie->nationality}}</option>
            @endforeach
        </select>
    </div> <!-- end col -->
    <div class="col-md-4">
        <label for="national_no" class="col-form-label text-right">{{__('National ID')}}</label>
        <input type="text" class="form-control mx" id="national_no" name="national_no" value="{{$Employee->national_no}}" autocomplete="national_no" placeholder="~987654321" autofocus maxlength="20">
    </div> <!-- end col -->
    <div class="col-md-4">
        <label for="passport" class="col-form-label text-right">{{__('Passport ID')}}</label>
        <input type="text" class="form-control mx" id="passport" name="passport" value="{{$Employee->passport}}" autocomplete="passport" placeholder="~N0123456" autofocus maxlength="20">
    </div> <!-- end col -->
</div> <!-- end row -->
