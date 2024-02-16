@extends('layouts.app')
@section('css')
<title>{{__('Employee Profile')}}</title>
@endsection
@section('breadcrumb')
    <h3>{{__('Employee Profile')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Employee Profile')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Employee Profile')}} ({{$Employee->first_name}} {{$Employee->last_name}}) </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row ">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12 align-self-center">
                        <img src="{{asset($Employee->emp_image)}}" alt="" class="d-block mx-auto" height="300" width="235">
                    </div>
                    <div class="col-md-10 align-self-center offset-md-1"><br><br>
                        <div class="ribbon-wrapper">
                            <div class="card-body">
                                <div class="ribbon ribbon-bookmark ribbon-danger">{{__('Contact')}}</div><br>
                                @foreach($Employee->phones as $phone)
                                    @if($phone->phone_type == 1)
                                    <i class="mdi mdi-cellphone-android text-primary"></i> <b>{{ __('Mobile') }}:</b>
                                    @elseif($phone->phone_type == 2)
                                    <i class="mdi mdi-phone-classic text-primary"></i> <b>{{ __('Home') }}:</b>
                                    @elseif($phone->phone_type == 3)
                                    <i class="mdi mdi-deskphone text-primary"></i> <b>{{ __('Work') }}:</b>
                                    @elseif($phone->phone_type == 4)
                                    <i class="mdi mdi-phone text-primary"></i> <b>{{ __('Other') }}:</b>
                                    @endif {{$phone->number}}
                                <br>
                                @endforeach
                            </div>
                        </div>
                        <div class="ribbon-wrapper">
                            <div class="card-body">
                                <div class="ribbon ribbon-bookmark ribbon-info">{{__('Addresses')}}</div><br>
                                @foreach($Employee->addresses as $address)
                                <b><i class="fas fa-map-marker-alt text-primary"></i>
                                    @if($address->add_type == 1)
                                    {{__('Permanent') }}
                                    @else {{ __('Temporary')}}
                                    @endif {{__('Address')}}: {{$address->address}}, {{$address->state->name}}, {{$address->countrie->name}}.</b>
                                <br>
                                @endforeach
                            </div>
                        </div>
                        <div class="ribbon-wrapper">
                            <div class="card-body">
                                <div class="ribbon ribbon-bookmark ribbon-primary">{{__('In Emergency Case')}}</div><br>
                                @foreach($Employee->emergencies as $emergency)
                                <b>{{__('Employee')}}
                                    @if($emergency->relationship == 1)
                                    {{ __('Father') }}
                                    @elseif($emergency->relationship == 2)
                                    {{ __('Brother')}}
                                    @elseif($emergency->relationship == 3)
                                    {{ __('Sister')}}
                                    @elseif($emergency->relationship == 4)
                                    {{ __('Friend')}}
                                    @endif: {{$emergency->fname_emer}} {{$emergency->lname_emer}}</b>
                                <br>  <b>
                                    @if ($emergency->house_phone != null)
                                    <i class="mdi mdi-phone-classic text-primary"></i> {{__('House')}}: {{$emergency->house_phone}}<br>
                                    @endif
                                    @if ($emergency->mobile_phone != null)
                                    <i class="mdi mdi-cellphone-android text-primary"></i> {{__('Mobile')}}: {{$emergency->mobile_phone}}
                                    @endif </b>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-md-6">
                <h4 class="text-muted">@if( $Employee->prefix == 1 )
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
                {{$Employee->first_name}} {{$Employee->middle_name}} {{$Employee->last_name}}</h4>
                <p class="text-muted"></p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="ribbon-wrapper">
                            <div class="card-body">
                                <div class="ribbon ribbon-bookmark ribbon-info">{{__('Basic Information')}}</div>
                                <b>{{__('DOB')}}: </b>{{\Carbon\Carbon::parse($Employee->birthdate)->isoFormat('Do MMMM YYYY')}}<br>
                                <b>{{__('Gender')}}: </b>@if ($Employee->gender == 1)
                                {{ __('Male') }}
                                @else
                                {{ __('Female') }}
                                @endif<br>
                                <b>{{__('Nationality')}}: </b>{{$Employee->countrie->nationality}}<br>
                                <b>{{__('Marital Status')}}: </b>
                                @if($Employee->marital_status == 1)
                                {{ __('Single') }}
                                @elseif($Employee->marital_status == 2 )
                                {{ __('Married')}}
                                @elseif($Employee->marital_status == 3 )
                                {{ __('Divorced')}}
                                @elseif($Employee->marital_status == 4 )
                                {{ __('Widowed')}} @endif
                                <br>
                                @if ($Employee->national_no != '')
                                <b>{{__('National ID')}}: </b>{{$Employee->national_no}}<br> @endif
                                @if ($Employee->passport != '')
                                <b>{{__('Passport ID')}}: </b>{{$Employee->passport}}<br> @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="ribbon-wrapper">
                            <div class="card-body">
                                <div class="ribbon ribbon-bookmark ribbon-warning">{{__('Job Details')}}</div>
                                <div class="col-md-11 offset-md-1">
                                    <b>{{__('Hire Date')}}: </b>{{\Carbon\Carbon::parse($Employee->hire_date)->isoFormat('Do MMMM YYYY')}}<br>
                                    @if ($Employee->resignation_date != null)
                                        <b>{{__('Resignation Date')}}: </b>{{\Carbon\Carbon::parse($Employee->resignation_date)->isoFormat('Do MMMM YYYY')}} <br>
                                    @endif
                                    <b>{{__('Status')}}: </b>{{$Employee->job_status->name}} | {{$Employee->job_type->name}} <br>
                                    <b>{{__('Line Manager')}}: </b>{{$Employee->line1->first_name}} {{$Employee->line1->last_name}} | {{$Employee->line1->position->name}} <br>
                                    <b>{{$Employee->position->name}} | {{$Employee->position->department->name}}</b><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($Employee->educations != "[]")
                <div class="row">
                    <div class="col-md-12">
                        <div class="ribbon-wrapper">
                            <div class="card-body">
                                <div class="ribbon ribbon-bookmark ribbon-primary">{{__('Education')}}</div>
                                @foreach($Employee->educations as $education)
                                <div class="row">
                                    <div class="col-md-4">
                                    <b>@if($education->level == 1)
                                        {{ __('Secondary Education')}}
                                        @elseif($education->level == 2)
                                        {{ __('Bachelor Degree')}}
                                        @elseif($education->level == 3)
                                        {{ __('Master Degree')}}
                                        @elseif($education->level == 4)
                                        {{ __('Doctoral Degree')}}
                                        @endif  </b>
                                    </div>
                                    <div class="col-md-8">
                                        {{\Carbon\Carbon::parse($education->start)->isoFormat('Do MMMM YYYY')}} - {{\Carbon\Carbon::parse($education->end)->isoFormat('Do MMMM YYYY')}}  <br>
                                        {{$education->school}} - {{$education->countrie->name}} <br>
                                        {{$education->degree}} - {{$education->field}} <br>
                                        GPA {{$education->grade}}
                                    </div>
                                </div><br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if ($Employee->experiences != "[]")
                <div class="row">
                    <div class="col-md-12">
                        <div class="ribbon-wrapper">
                            <div class="card-body">
                                <div class="ribbon ribbon-bookmark ribbon-danger">{{__('Experience')}}</div>
                                @foreach($Employee->experiences as $experience)
                                <div class="row">
                                    <div class="col-md-4">
                                        <b>{{$experience->job_title}}</b>
                                    </div>
                                    <div class="col-md-8">
                                        {{\Carbon\Carbon::parse($experience->start_date)->isoFormat('Do MMMM YYYY')}} - {{\Carbon\Carbon::parse($experience->end_date)->isoFormat('Do MMMM YYYY')}} <br>
                                        {{$experience->employer}} | {{$experience->sector}}<br>
                                        {{$experience->countrie->name}}
                                    </div>
                                </div><br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end card-body-->
    <div class="card-footer">
        <a href="{{route('ViewEmp')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Employees')}}</button></a>
    </div>
</div><!--end card-->
@endsection


@section('javascript')

@endsection
