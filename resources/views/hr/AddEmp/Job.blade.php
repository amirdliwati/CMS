<div class="row">
    <div class="col-md-6">
        <label for="hire_date" class="col-form-label text-right">{{__('Hire Date')}}</label>
        <div class="input-group">
            <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
            <input type="text" class="form-control datem" placeholder="mm/dd/yy" id="hire_date" name="hire_date" value="{{ old('hire_date') }}" autocomplete="hire_date" autofocus readonly required>
        </div>
    </div><!-- end col -->
    <div class="col-md-6">
        <label for="resignation_date" class="col-form-label text-right">{{__('Resignation Date')}}</label>
        <div class="input-group">
            <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
            <input type="text" class="form-control datem" placeholder="mm/dd/yy" id="resignation_date" name="resignation_date" value="{{ old('resignation_date') }}" autocomplete="resignation_date" readonly autofocus>
        </div>
    </div><!-- end col -->
</div>
<br>
<div class="row">
    <div class="col-md-4">
        <label for="job_type_id" class="col-form-label text-right">{{__('Job Type')}}</label>
        <select class="form-control" style="width: 100%; height:36px;" id="job_type_id" name="job_type_id" autocomplete="job_type_id" autofocus required>
            <option disabled selected value="{{ old('job_type_id') }}">{{__('Select an option')}}</option>
            @foreach($job_types as $job_type)
                <option value="{{$job_type->id}}">{{$job_type->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="status_id" class="col-form-label text-right">{{__('Job Status')}}</label>
        <select class="form-control" style="width: 100%; height:36px;" id="status_id" name="status_id" autocomplete="status_id" autofocus required>
            <option disabled selected value="{{ old('status_id') }}">{{__('Select an option')}}</option>
            @foreach($job_statuses as $job_status)
                <option value="{{$job_status->id}}">{{$job_status->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="basic" class="col-form-label text-right">{{__('Basic Salary')}}</label>
        <input type="number" class="form-control money" id="basic" name="basic" value="{{ old('basic') }}" autocomplete="basic" placeholder="" autofocus required >
    </div>
</div> <!-- end row -->
<br>
<div class="row">
    <div class="col-md-4">
        <label for="department" class="col-form-label text-right">{{__('Department')}}</label>
        <select class="form-control select2" id="department" name="department" autofocus required>
            <option selected="selected" value="{{ old('department') }}"></option>
            @foreach($departments as $department)
                <option value="{{$department->id}}">{{$department->branch->code}} - {{$department->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="position" class="col-form-label text-right">{{__('Position')}}</label>
        <select class="form-control select2" id="position" name="position" autofocus required>
            <option selected="selected" value="{{ old('position') }}"></option>
        </select>
    </div>
    <div class="col-md-4">
        <label for="line" class="col-form-label text-right">{{__('Line Manager')}}</label>
        <select class="form-control select2" id="line" name="line" autofocus required>
            <option selected="selected" value="{{ old('line') }}"></option>
        </select>
    </div>
</div> <!-- end row -->
