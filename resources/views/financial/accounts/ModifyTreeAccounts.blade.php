@extends('layouts.app')
@section('css')
    <title>{{__('Modify Accounts Tree')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <style>.select2-container{z-index:100000;}</style>

    <!-- Tree View -->
    <link href="{{ asset('libs/treeview/themes/default/style.css') }}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <h3>{{__('Modify Accounts Tree')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Modify Accounts Tree')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li>
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-outline-warning" data-toggle="modal"  data-target="#modalRename"><i class="mdi mdi-18px mdi-circle-edit-outline mr-2"></i>{{__('Rename')}}</button>
            <button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modalChangeParent"><i class="mdi mdi-18px mdi-folder-edit mr-2"></i>{{__('Change Parent')}}</button>
            <button type="button" class="btn btn-outline-secondary" data-toggle="modal"  data-target="#modalChangeClosing"><i class="mdi mdi-18px mdi-folder-edit mr-2"></i>{{__('Change Closing Account')}}</button>
        </div>
    </li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <input type="hidden" id="account_id" name="account_id" value="">
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

<!-- Large modal Rename Account -->
<form id="form" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="rename_account_id" name="rename_account_id" value="">
    <div class="modal fade bs-example-modal-sm" id="modalRename" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">{{__('Rename Account')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="name" class="col-form-label text-right">{{__('Account Name')}}</label>
                            <input type="text" class="form-control mx" placeholder="~Electrical" id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="75" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label for="account_number" class="col-form-label text-right">{{__('Account Number')}}</label>
                            <input type="number" class="form-control mx" placeholder="~001" id="account_number" name="account_number" value="{{ old('account_number') }}" autocomplete="account_number" maxlength="10" autofocus required>
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

<!-- Large modal Change Parent Account -->
<form id="form2" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="change_parent_account_id" name="change_parent_account_id" value="">
    <div class="modal fade bs-example-modal-sm" id="modalChangeParent" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">{{__('Change Parent Account')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="parent" class="col-form-label text-right">{{__('Choose Parent')}}</label>
                            <select class="select2 form-control" id="parent" name="parent" autofocus required>
                                <option selected="selected" value="{{ old('parent') }}"></option>
                                <option value="0"> { {{__('Root')}} } </option>
                                @foreach($Accounts->where('type',0) as $Account)
                                    <option value="{{$Account->id}}">{{$Account->name}}</option>
                                @endforeach
                            </select>
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

<!-- Large modal Change Closing Account -->
<form id="form3" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="change_closing_account_id" name="change_closing_account_id" value="">
    <div class="modal fade bs-example-modal-sm" id="modalChangeClosing" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">{{__('Change Closing Account')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="closing" class="col-form-label text-right">{{__('Choose Closing Account')}}</label>
                            <select class="form-control select2" id="closing" name="closing" autofocus required>
                                <option selected="selected" value="{{ old('closing') }}"></option>
                                @foreach($Accounts->where('type',1) as $Account)
                                    <option value="{{$Account->id}}">{{$Account->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div><!-- end modal-body -->
                <div class="modal-footer">
                    <button type="submit" id="finish3" class="btn btn-success-gradien">{{__('Save')}}</button>
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

        $('#finish2 , #finish3').click( function() {
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
            $("#account_id").val(r[0]);
            const Split = r[2].split("-");
            $("#account_number").val(Split[0]);
            $("#name").val(Split[1]);
            $("#rename_account_id").val(r[0]);
            $("#change_parent_account_id").val(r[0]);
            $("#change_closing_account_id").val(r[0]);
        });

        function TreeData(data) {
            treeData = [];
            // Make it a variable so you can access it later
                for(i=0;i<data.length;i++){
                    if(data[i].parent === null) {
                        treeData.push({ "id" : data[i].id, "parent" : "#", "text" :data[i].account_number +'-'+ data[i].name, "data": data[i].type ,"icon":"fa fa-folder text-warning font-24"});
                    }
                    else if(data[i].type == 1) {
                        treeData.push({ "id" : data[i].id, "parent" : data[i].parent, "text" :data[i].account_number +'-'+ data[i].name, "data": data[i].type ,"children": false ,"icon":"far fa-newspaper text-info font-24"});
                    }
                    else {
                        treeData.push({ "id" : data[i].id, "parent" : data[i].parent, "text" :data[i].account_number +'-'+ data[i].name, "data": data[i].type ,"icon":"fa fa-folder text-warning font-24"});
                    }
                };
            swal({
                title: 'successfully',
                text: 'The Account has been chaned successfully.',
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
                        $("#account_id").val(r[0]);
                        const Split = r[2].split("-");
                        $("#account_number").val(Split[0]);
                        $("#name").val(Split[1]);
                        $("#rename_account_id").val(r[0]);
                        $("#change_parent_account_id").val(r[0]);
                        $("#change_closing_account_id").val(r[0]);
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
                url: "/RenameAccountCard",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    $('#modalRename').modal('hide');
                    $('#form').trigger("reset");
                    $('#finish').attr('disabled', false);
                    if (data == 'Rename Error') {
                        SwalMessage('Error','You can not rename account becuase did not select account.','error');
                        $('#finish').attr('disabled', false);
                    } else if(data == 'Number Error') {
                        SwalMessage('Account Number Error','Account number is duplicate.','error');
                        $('#finish').attr('disabled', false);
                    } else {
                        $("#jstree").jstree('destroy');
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
                url: "/ChangeParentAccountCard",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    $('#modalChangeParent').modal('hide');
                    $('#form2').trigger("reset");
                    $('#finish2').attr('disabled', false);
                    if (data == 'Error') {
                        SwalMessage('Error','You can not change parent.','error');
                    } else {
                        $("#jstree").jstree('destroy');
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

    <script type="text/javascript">
        $('#form3').on('submit', function(event) {
            $('#finish3').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/ChangeClosingAccountCard",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    $('#modalChangeClosing').modal('hide');
                    $('#form2').trigger("reset");
                    $('#finish3').attr('disabled', false);
                    if (data == 'Error') {
                        SwalMessage('Error','You can not change closing account.','error');
                    } else {
                        $("#jstree").jstree('destroy');
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
