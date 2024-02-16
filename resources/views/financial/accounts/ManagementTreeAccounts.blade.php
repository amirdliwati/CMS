@extends('layouts.app')
@section('css')
    <title>{{__('Management Accounts Tree')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <style>.select2-container{z-index:100000;}</style>

    <!-- Tree View -->
    <link href="{{ asset('libs/treeview/themes/default/style.css') }}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <h3>{{__('Management Accounts Tree')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Management Accounts Tree')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modalAccount"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add Account')}}</button></li>
    <li><button type="button" id="add-account" class="btn btn-outline-info" data-toggle="modal"  data-target="#modalAccountByTree">{{__('Root')}}</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Accounts Tree')}} </h5> @include('layouts/button_card') </div>
    <div class="card-body">
        <div class="row">
            <div class="btn-group btn-group-pill mb-2" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-outline-success" onclick="ExpandTree()"><i class="mdi mdi-18px mdi-expand-all"></i></button>
                <button type="button" class="btn btn-outline-info" onclick="CollapseTree()"><i class="mdi mdi-18px mdi-collapse-all-outline"></i></button>
            </div>
        </div><!-- end row -->
        <div class="row">
            <div id="jstree">
            </div> <!-- end tree -->
        </div> <!-- end row -->
    </div> <!-- end card-body -->
        <div class="card-footer">
            <a href="{{route('home')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Home')}}</button></a>
        </div>
</div> <!-- end card -->

<!-- Large modal Add New Account -->
<form id="form" data-parsley-required-message="">
    @csrf
    <div class="modal fade bs-example-modal-xl" id="modalAccount" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">{{__('Add Account')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="parent" class="col-form-label text-right">{{__('Choose Account Parent')}}</label>
                            <select class="form-control select2" id="parent" name="parent" autofocus required>
                                <option selected="selected" value="{{ old('parent') }}"></option>
                                <option value="0"> { {{__('Root')}} } </option>
                                @foreach($Accounts as $Account)
                                    <option value="{{$Account->id}}">{{$Account->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-6">
                            <label for="closing" class="col-form-label text-right">{{__('Choose Account Closing')}}</label>
                            <select class="form-control select2" id="closing" name="closing" autofocus required>
                                <option selected="selected" value="{{ old('closing') }}"></option>
                                @foreach($Accounts as $Account)
                                    <option value="{{$Account->id}}">{{$Account->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name" class="col-form-label text-right">{{__('Account Name')}}</label>
                            <input type="text" class="form-control mx" placeholder="~Account Name" id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="100" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="account_number" class="col-form-label text-right">{{__('Account Number')}}</label>
                            <input type="number" class="form-control mx" placeholder="~001" id="account_number" name="account_number" value="{{ old('account_number') }}" autocomplete="account_number" maxlength="10" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="type" class="col-form-label text-right">{{__('Type')}}</label>
                            <select class="select2 form-control" id="type" name="type" autofocus required>
                                <option selected="selected" value="0">{{__('Normal')}}</option>
                                <option value="1">{{__('Closing')}}</option>
                            </select>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="notes" class="col-form-label text-right">{{__('Notes')}}</label>
                            <input type="text" class="form-control mx" placeholder="~Notes" id="notes" name="notes" value="{{ old('notes') }}" autocomplete="notes" maxlength="300" autofocus>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div><!-- end modal-body -->
                <div class="modal-footer">
                    <button type="submit" id="finish" class="btn btn-success-gradien">{{__('Save')}}</button>
                    <button type="button" class="btn btn-secondary-gradien" data-dismiss="modal" onclick="this.form.reset()">{{__('Close')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>

<!-- Large modal Add New Account by Tree -->
<form id="form2" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="parent_id" name="parent_id" value="">
    <div class="modal fade bs-example-modal-lg" id="modalAccountByTree" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">{{__('Add Account')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="closing_account" class="col-form-label text-right">{{__('Choose Account Closing')}}</label>
                            <select class="form-control select2" id="closing_account" name="closing_account" autofocus>
                                <option selected="selected" value="{{ old('closing_account') }}"> </option>
                                @foreach($Accounts as $Account)
                                    <option value="{{$Account->id}}">{{$Account->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-5">
                            <label for="name_account" class="col-form-label text-right">{{__('Account Name')}}</label>
                            <input type="text" class="form-control mx" placeholder="~Account Name" id="name_account" name="name_account" value="{{ old('name_account') }}" autocomplete="name_account" maxlength="100" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="number_account" class="col-form-label text-right">{{__('Account Number')}}</label>
                            <input type="number" class="form-control" placeholder="~001" id="number_account" name="number_account" value="{{ old('number_account') }}" autocomplete="number_account" maxlength="10" autofocus required>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="type_account" class="col-form-label text-right">{{__('Type')}}</label>
                            <select class="form-control select2" id="type_account" name="type_account" autofocus required>
                                <option selected="selected" value="0">{{__('Normal')}}</option>
                                <option value="1">{{__('Closing')}}</option>
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-9">
                            <label for="notes_account" class="col-form-label text-right">{{__('Notes')}}</label>
                            <input type="text" class="form-control mx" placeholder="~Notes" id="notes_account" name="notes_account" value="{{ old('notes_account') }}" autocomplete="notes_account" maxlength="300" autofocus>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div><!-- end modal-body -->
                <div class="modal-footer">
                    <button type="submit" id="finish2" class="btn btn-success-gradien">{{__('Save')}}</button>
                    <button type="button" class="btn btn-secondary-gradien" data-dismiss="modal" onclick="this.form.reset()">{{__('Close')}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>
@endsection
@section('javascript')
	<!-- Plugins js -->
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>

    <!-- Tree View -->
    <script src="{{ asset('libs/treeview/jstree.min.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});

        $( ".select2" ).change(function() {
            if ($(this).val() == "") { $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'}); }
            else { $(this).siblings(".select2-container").css({'border': '','border-radius': ''}); }
        });

        $('#finish').click( function() {
            $(".select2").each(function() {
                if ($(this).val() == "") {
                    $(this).siblings(".select2-container").css({'border': '1px solid red','border-radius': '5px'});
                }else {
                    $(this).siblings(".select2-container").css({'border': '','border-radius': ''});
                }
            });
        });

        function ExpandTree(){ $("#jstree").jstree('open_all'); }
        function CollapseTree(){ $("#jstree").jstree('close_all'); }
    </script>

    <!-- Tree Data -->
    <script type="text/javascript">
        // Make it a variable so you can access it later
        var treeData = [
            @foreach($Accounts as $Account)
                @if(empty($Account->parent))
                    { "id" : "{{$Account->id}}", "parent" : "#", "text" : "{{$Account->account_number}}-{{$Account->name}}", "data":"{{$Account->type}}" ,"icon":"fa fa-folder text-warning font-24"},
                @elseif($Account->type == 1)
                    { "id" : "{{$Account->id}}", "parent" : "{{$Account->parent}}", "text" : "{{$Account->account_number}}-{{$Account->name}}", "data":"{{$Account->type}}" ,"children": false ,"icon":"fa fa-folder text-secondary font-24"},
                @else
                    { "id" : "{{$Account->id}}", "parent" : "{{$Account->parent}}", "text" : "{{$Account->account_number}}-{{$Account->name}}", "data":"{{$Account->type}}" ,"icon":"fa fa-folder text-warning font-24"},
                @endif
            @endforeach
        ];

        var accountTree = $('#jstree').jstree({ 'core' : {
        "themes" : {"variant" : "large"},
        'data' : treeData // Use it here
        }});

        accountTree.on('changed.jstree', function(e, data) {
            var i, j, r = [];
            for(i = 0, j = data.selected.length; i < j; i++) {
                r.push(data.instance.get_node(data.selected[i]).id);
                r.push(data.instance.get_node(data.selected[i]).data);
                r.push(data.instance.get_node(data.selected[i]).text);
            }
            $("#parent_id").val(r[0]);
            $("#add-account").html(r[2]);
        });

        function TreeData(data) {
            treeData = [];
            // Make it a variable so you can access it later
                for(i=0;i<data.length;i++){
                    if(data[i].parent === null) {
                        treeData.push({ "id" : data[i].id, "parent" : "#", "text" :data[i].account_number +'-'+ data[i].name, "data": data[i].type ,"icon":"fa fa-folder text-warning font-24"});
                    }
                    else if(data[i].type == 1) {
                        treeData.push({ "id" : data[i].id, "parent" : data[i].parent, "text" :data[i].account_number +'-'+ data[i].name, "data": data[i].type ,"children": false ,"icon":"fa fa-folder text-secondary font-24"});
                    }
                    else {
                        treeData.push({ "id" : data[i].id, "parent" : data[i].parent, "text" :data[i].account_number +'-'+ data[i].name, "data": data[i].type ,"icon":"fa fa-folder text-warning font-24"});
                    }
                };
            swal({
                title: 'successfully',
                text: 'The Account has been added successfully.',
                type: 'success',
                preConfirm: function (){
                    var accountTree = $('#jstree').jstree({'core' : {
                    "themes" : {"variant" : "large"},
                    'data' : function (obj, cb) {cb.call(this,treeData);}
                    }});
                    accountTree.on('changed.jstree', function(e, data) {
                        var i, j, r = [];
                        for(i = 0, j = data.selected.length; i < j; i++) {
                            r.push(data.instance.get_node(data.selected[i]).id);
                            r.push(data.instance.get_node(data.selected[i]).data);
                            r.push(data.instance.get_node(data.selected[i]).text);
                        }
                        $("#parent_id").val(r[0]);
                        $("#add-account").html(r[2]);
                    });
                }
            });
        }
    </script>

    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/AddAccount",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    if (data == 'Number Error') {
                        SwalMessage('Account Number Error','Account number is duplicate.','error');
                    }else {
                        $('#modalAccount').modal('hide');
                        $('#form').trigger("reset");
                        $("#jstree").jstree('destroy');
                        $('#finish').attr('disabled', false);
                        TreeData(data);
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
        });
    </script>

    <script type="text/javascript">
        $('#form2').on('submit', function(event) {
            $('#finish2').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/AddAccountByTree",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    if (data == 'Number Error') {
                        SwalMessage('Account Number Error','Account number is duplicate.','error');
                        $('#finish2').attr('disabled', false);
                    }else {
                        $('#modalAccountByTree').modal('hide');
                        $('#form2').trigger("reset");
                        $("#jstree").jstree('destroy');
                        $('#finish2').attr('disabled', false);
                        TreeData(data);
                    }
                },
                error: function(jqXHR){
                    if(jqXHR.status==0) {
                        SwalMessage('You are offline','Connection to the server has been lost. Please check your internet connection and try again.','error');
                    } else{
                        SwalMessage('Attention','Something went wrong. Please try again later.','warning');
                    }
                    $('#finish2').attr('disabled', false);
                }
            });
        });
    </script>
@endsection
