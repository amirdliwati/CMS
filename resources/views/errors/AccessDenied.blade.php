@extends('layouts.app')
@section('css')
		<title>{{ config('app.name') }} Access Denied</title>
@endsection
@section('content')
<!-- Page Content-->
<div class="row">
    <div class="col-md-5 p-0 align-self-center">
        <img src="{{ asset('images/other-images/server-error.svg') }}" alt="" class="img-fluid">
    </div>
    <div class="col-md-6"> <br><br><br><br><br><br>
        <div class="text-center">
            <h1 class="">{{__('Access Denied')}}!</h1>
            <h4 class="text-primary">{{__('Looks like you hve got lost')}}...</h3><br>
            <a class="btn btn-primary-gradien mb-5 waves-effect waves-light" href="{{route('home')}}">{{__('Back to Dashboard')}}</a>
        </div>
    </div><!--end col-->
</div><!--end row-->
@endsection
@section('javascript')

@endsection
