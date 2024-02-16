@extends('layouts.app')
@section('css')
    <title>{{__('Inventory Tree')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/switch/switch.css') }}" rel="stylesheet" type="text/css" />
    <style>.select2-container{z-index:100000;}</style>

    <!-- Tree View -->
    <link href="{{ asset('libs/treeview/themes/default/style.css') }}" rel="stylesheet">
@endsection
@section('breadcrumb')
    <h3>{{__('Management Inventory')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Inventory Tree')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn btn-outline-primary" data-toggle="modal"  data-target="#modalCategory"><i class="fa fa-plus-square-o mr-2"></i>{{__('Add New Category/Item')}}</button></li>
    <li><button type="button" id="add-category" class="btn btn-outline-info" data-toggle="modal"  data-target="#modalCategoryByTree">Root</button></li>
@endsection
@section('content')
<!-- Main content -->
<div class="card">
    <div class="card-header b-l-primary border-3"><h5> {{__('Inventory Tree')}} </h5> @include('layouts/button_card') </div>
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

<!-- Large modal Add New Category -->
<form id="form" data-parsley-required-message="">
    @csrf
    <div class="modal fade bs-example-modal-xl" id="modalCategory" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">{{__('Add New Category/Item')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="parent" class="col-form-label text-right">{{__('Choose Parent')}}</label>
                            <select class="form-control select2" style="width: 100%; height:36px;" id="parent" name="parent" autocomplete="parent" autofocus required>
                                <option value="0"> { Root } </option>
                                @foreach($Categories->where('type',0) as $Category)
                                    <option value="{{$Category->id}}">{{$Category->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-5">
                            <label for="name" class="col-form-label text-right">{{__('Category Name')}}</label>
                            <input type="text" class="form-control mx" placeholder="~Electrical" id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="75" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-2">
                            <label for="type" class="col-form-label text-right">{{__('Type: Category/Item')}}</label>
                            <div class="mb-2"></div>
                            <input type="checkbox" id="type" name="type" switch="warning" checked="">
                            <label for="type" data-on-label="&#128194;" data-off-label="Item"></label>
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

<!-- Large modal Add New Category by Tree -->
<form id="form2" data-parsley-required-message="">
    @csrf
    <input type="hidden" id="parent_id" name="parent_id" value="0">
    <div class="modal fade bs-example-modal-lg" id="modalCategoryByTree" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myLargeModalLabel">{{__('Add New Category/Item')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="name_category" class="col-form-label text-right">{{__('Category Name')}}</label>
                            <input type="text" class="form-control mx" placeholder="~Electrical" id="name_category" name="name_category" value="{{ old('name_category') }}" autocomplete="name_category" maxlength="75" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label for="type_category" class="col-form-label text-right">{{__('Type: Category/Item')}} </label>
                            <div class="mb-2"></div>
                            <input type="checkbox" id="type_category" name="type_category" switch="warning" checked="">
                            <label for="type_category" data-on-label="&#128194;" data-off-label="Item"></label>
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
            @foreach($Categories as $Category)
                @if(empty($Category->parent))
                    { "id" : "{{$Category->id}}", "parent" : "#", "text" : "{{$Category->name}}", "data":"{{$Category->type}}" ,"icon":"fa fa-folder text-warning font-24"},
                @elseif($Category->type == 1)
                    { "id" : "{{$Category->id}}", "parent" : "{{$Category->parent}}", "text" : "{{$Category->name}}", "data":"{{$Category->type}}" ,"children": false ,"icon":"fa fa-file-text-o text-info font-24"},
                @else
                    { "id" : "{{$Category->id}}", "parent" : "{{$Category->parent}}", "text" : "{{$Category->name}}", "data":"{{$Category->type}}" ,"icon":"fa fa-folder text-warning font-24"},
                @endif
            @endforeach
        ];

        var inventoryTree = $('#jstree').jstree({ 'core' : {
        "themes" : {"variant" : "large"},
        'data' : treeData // Use it here
        }});

        inventoryTree.on('changed.jstree', function(e, data) {
            var i, j, r = [];
            for(i = 0, j = data.selected.length; i < j; i++) {
            r.push(data.instance.get_node(data.selected[i]).id);
            r.push(data.instance.get_node(data.selected[i]).data);
            r.push(data.instance.get_node(data.selected[i]).text);
            }
            $("#parent_id").val(r[0]);
            $("#add-category").html(r[2]);
        });

        function TreeData(data) {
            treeData = [];
            // Make it a variable so you can access it later
                for(i=0;i<data.length;i++){
                    if(data[i].parent === null) {
                        treeData.push({ "id" : data[i].id, "parent" : "#", "text" : data[i].name, "data": data[i].type ,"icon":"fa fa-folder text-warning font-24"});
                    }
                    else if(data[i].type == 1) {
                        treeData.push({ "id" : data[i].id, "parent" : data[i].parent, "text" : data[i].name, "data": data[i].type ,"children": false ,"icon":"fa fa-file-text-o text-info font-24"});
                    }
                    else {
                        treeData.push({ "id" : data[i].id, "parent" : data[i].parent, "text" : data[i].name, "data": data[i].type ,"icon":"fa fa-folder text-warning font-24"});
                    }
                };
            swal({
                title: 'successfully',
                text: 'The Category has been added successfully.',
                type: 'success',
                preConfirm: function (){
                    var inventoryTree = $('#jstree').jstree({'core' : {
                    "themes" : {"variant" : "large"},
                    'data' : function (obj, cb) {cb.call(this,treeData);}
                    }});
                    inventoryTree.on('changed.jstree', function(e, data) {
                        var i, j, r = [];
                        for(i = 0, j = data.selected.length; i < j; i++) {
                        r.push(data.instance.get_node(data.selected[i]).id);
                        r.push(data.instance.get_node(data.selected[i]).data);
                        r.push(data.instance.get_node(data.selected[i]).text);
                        }
                        $("#parent_id").val(r[0]);
                        $("#add-category").html(r[2]);
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
                url: "/AddCategory",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    $('#modalCategory').modal('hide');
                    $('#form').trigger("reset");
                    $("#jstree").jstree('destroy');
                    $('#finish').attr('disabled', false);
                    TreeData(data);
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
                url: "/AddCategoryByTree",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    $('#modalCategoryByTree').modal('hide');
                    $('#form2').trigger("reset");
                    $("#jstree").jstree('destroy');
                    $('#finish2').attr('disabled', false);
                    TreeData(data);
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
