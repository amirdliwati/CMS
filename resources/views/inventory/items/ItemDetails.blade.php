@extends('layouts.app')
@section('css')
    <title>{{__('Item Details')}}</title>
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/notebook.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/rating.css') }}" type="text/css"  rel="stylesheet">
@endsection
@section('breadcrumb')
    <h3>{{__('Management Items')}}</h3>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a></li>
        <li class="breadcrumb-item">{{ Auth::user()->roles->first()->blug }}</li>
        <li class="breadcrumb-item active">{{__('Item Details')}}</li>
    </ol>
@endsection
@section('bookmark')
    <li><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="RefreshPage()"><i data-feather="refresh-cw"></i></button></li>
@endsection
@section('content')
<!-- Main content -->
<form id="form" enctype="multipart/form-data" data-parsley-required-message="">
    @csrf
    <input type="hidden" name="IDItemDetails" value="{{$ItemDetails->id}}">
    <div class="card">
        <div class="card-header b-l-primary border-3"><h5> {{__('Item Details')}} ({{$ItemDetails->item->serial}}) </h5> @include('layouts/button_card') </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="category_id" class="col-form-label text-right">{{__('Choose Item Category')}}</label>
                    <select class="form-control select2" id="category_id" name="category_id" autofocus required>
                        <option selected="selected" value="{{$ItemDetails->item->category_id}}">{{$ItemDetails->item->name_item->name}}</option>
                        @foreach($Categories as $Category)
                            <option value="{{$Category->id}}">{{$Category->name}}</option>
                        @endforeach
                    </select>
                </div> <!-- end col -->
                <div class="col-md-4">
                    <label for="model" class="col-form-label text-right">{{__('Model')}}</label>
                    <input type="text" class="form-control mx" placeholder="~Sony Device" id="model" name="model" value="{{$ItemDetails->item->model}}" autocomplete="model" maxlength="100" autofocus required>
                </div> <!-- end col -->
                <div class="col-md-4">
                    <label for="serial" class="col-form-label text-right">{{__('Serial Number')}}</label>
                    <input type="text" class="form-control mx" placeholder="~112233hhgg" id="serial" name="serial" value="{{$ItemDetails->item->serial}}" autocomplete="serial" maxlength="50" autofocus required>
                </div> <!-- end col -->
            </div> <!-- end row --> <br>
            <div class="row">
                <div class="col-md-9">
                    <div class="paper">
                        <div class="paper-content">
                            <label for="description" class="col-form-label text-right text-info" style="font-weight: bold;font-size: large;text-decoration: underline;">{{__('Item Description')}}:</label>
                            <textarea type="text" class="form-control mx" id="description" name="description" autocomplete="description" placeholder="~" autofocus rows="5" maxlength="500" data-toggle="tooltip" data-placement="left" title="Write any thing">{{$ItemDetails->item->description}}</textarea>
                        </div> <!-- paper-content -->
                    </div> <!-- paper -->
                </div> <!-- end col -->
                <div class="col-md-3">
                    <div class="mb-3"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="sub_inventory_id" class="col-form-label text-right">{{__('Choose Inventory')}}</label>
                            <select class="form-control select2" id="sub_inventory_id" name="sub_inventory_id" autofocus required>
                                <option selected="selected" value="{{$ItemDetails->item->sub_inventory_id}}">{{$ItemDetails->item->sub_inventory->branch->name}} -> {{$ItemDetails->item->sub_inventory->name}}</option>
                            </select>
                        </div> <!-- end col -->
                    </div> <!-- end row --> <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="quality" class="col-form-label text-right">{{__('Item Rating')}}</label>
                            <select id="quality" name="quality" autocomplete="off">
                                @switch($ItemDetails->item->quality)
                                    @case(1)
                                        <option value="1" selected="selected">{{__('Bad')}}</option>
                                        <option value="2">{{__('Not Good')}}</option>
                                        <option value="3">{{__('Good')}}</option>
                                        <option value="4">{{__('Very Good')}}</option>
                                        <option value="5">{{__('Excelente')}}</option>
                                        @break
                                    @case(2)
                                        <option value="1">{{__('Bad')}}</option>
                                        <option value="2" selected="selected">{{__('Not Good')}}</option>
                                        <option value="3">{{__('Good')}}</option>
                                        <option value="4">{{__('Very Good')}}</option>
                                        <option value="5">{{__('Excelente')}}</option>
                                        @break
                                    @case(3)
                                        <option value="1">{{__('Bad')}}</option>
                                        <option value="2">{{__('Not Good')}}</option>
                                        <option value="3" selected="selected">{{__('Good')}}</option>
                                        <option value="4">{{__('Very Good')}}</option>
                                        <option value="5">{{__('Excelente')}}</option>
                                        @break
                                    @case(4)
                                        <option value="1">{{__('Bad')}}</option>
                                        <option value="2">{{__('Not Good')}}</option>
                                        <option value="3">{{__('Good')}}</option>
                                        <option value="4" selected="selected">{{__('Very Good')}}</option>
                                        <option value="5">{{__('Excelente')}}</option>
                                        @break
                                    @case(5)
                                        <option value="1">{{__('Bad')}}</option>
                                        <option value="2">{{__('Not Good')}}</option>
                                        <option value="3">{{__('Good')}}</option>
                                        <option value="4">{{__('Very Good')}}</option>
                                        <option value="5" selected="selected">{{__('Excelente')}}</option>
                                        @break
                                @endswitch
                            </select>
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div> <!-- end col -->
            </div> <!-- end row --> <br><br>
            <div class="row">
                <div class="col-md-3">
                    <h4 class="mt-0 header-title"><b class="mr-2">{{__('Upload Image')}}</b>
                        @if(!empty($ItemDetails->image_path))
                            <button type="button" class="btn btn-outline-info" onclick="PreviewImage({{$ItemDetails->item->id }})"><i class="fa fa-eye mr-2"></i>{{__('Preview')}}</button>
                        @endif
                    </h4>
                    <p class="text-muted mb-4">{{__('File should be up to 15 Megabytes')}}.</p>
                    <input type="file" class="dropify form-control @error('image_path') is-invalid @enderror" id="image_path" name="image_path" accept="image/*" data-default-file=""  data-height="125" data-max-file-size="15M" data-allowed-file-extensions="png jpg jpeg">
                </div><!--end col-->
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="origin_country" class="col-form-label text-right">{{__('Origin Country')}}</label>
                            <select class="form-control select2" id="origin_country" name="origin_country" autofocus required>
                                <option selected="selected" value="{{$ItemDetails->origin_country}}">{{$ItemDetails->country->name}}</option>
                                @foreach($Countries as $Country)
                                    <option value="{{$Country->id}}">{{$Country->name}}</option>
                                @endforeach
                            </select>
                        </div> <!-- end col -->
                        <div class="col-md-6">
                            <label for="manufacture" class="col-form-label text-right">{{__('Manufacture')}}</label>
                            <input type="text" class="form-control mx" placeholder="~Sony Device" id="manufacture" name="manufacture" value="{{$ItemDetails->manufacture}}" autocomplete="manufacture" maxlength="100" autofocus>
                        </div> <!-- end col -->
                        <div class="col-md-3">
                            <label for="date_of_manufacture" class="col-form-label text-right">{{__('Date of Manufacture')}}:</label>
                            <select class="select2 form-control mb-3 custom-select @error('date_of_manufacture') is-invalid @enderror" id="date_of_manufacture" name="date_of_manufacture" autofocus>
                                <option selected="selected" value="{{$ItemDetails->date_of_manufacture}}">{{$ItemDetails->date_of_manufacture}}</option>
                                    @for($i = 1960 ; $i <2041 ; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                            </select>
                        </div>
                    </div><!--end row--> <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="brand" class="col-form-label text-right">{{__('Brand')}}</label>
                            <input type="text" class="form-control mx" placeholder="~Sony" id="brand" name="brand" value="{{$ItemDetails->item->brand}}" autocomplete="brand" maxlength="100" autofocus required>
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label for="expire_date" class="col-form-label text-right">{{__('Expire Date')}}</label>
                            <div class="input-group">
                                <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="mdi mdi-calendar"></i></span></div>
                                <input type="text" class="form-control datem" id="expire_date" name="expire_date" value="{{$ItemDetails->expire_date}}" autocomplete="expire_date" placeholder="mm/dd/yyyy" autofocus readonly required >
                            </div>
                        </div> <!-- end col -->
                        <div class="col-md-4">
                            <label for="color" class="col-form-label text-right">{{__('Item Color')}}</label>
                            <input type="color" class="colorpicker form-control mx" value="{{$ItemDetails->color}}" id="color" name="color" autocomplete="color" maxlength="25" autofocus>
                        </div> <!-- end col -->
                    </div><!--end row-->
                    <div class="row">
                        <div class="col-md-6">
                            <label for="barcode" class="col-form-label text-right">{{__('Barcode')}}</label>
                            <input type="text" class="form-control mx" placeholder="~112233hhgg" id="barcode" name="barcode" value="{{$ItemDetails->item->barcode}}" autocomplete="barcode" maxlength="50" autofocus required>
                        </div> <!-- end col -->
                    </div><!--end row-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end card-body-->
            <div class="card-footer">
                @if(Auth::user()->roles->whereIn('title',['Admin_role','Financial_role'])->first()->permissions->where('title','ManagementItems') != '[]')
                    <button type="submit" id="finish" class="btn btn-success-gradien btn-pill"><i class="fa fa-save mr-1"></i>{{__('Save')}}</button>
                @endif
                <a href="{{route('Items')}}"><button type="button" class="btn btn-warning-gradien btn-pill"><i class="mdi mdi-backup-restore mr-1"></i>{{__('Back To Items')}}</button></a>
            </div>
    </div><!--end card-->
</form>
@endsection

@section('javascript')
    <!-- Plugins js -->
    <script src="{{ asset('js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('js/touchspin/touchspin.js') }}"></script>
    <script src="{{ asset('js/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('js/rating/jquery.barrating.js') }}"></script>

    <!-- Files Upload -->
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/jquery.form-upload.init.js') }}"></script>

    <!-- Other Scripts -->
    <script type="text/javascript">
        $('form').parsley();
        $('.mx').maxlength({warningClass: "badge badge-info",limitReachedClass: "badge badge-warning",alwaysShow :true});
        $('.datem').datepicker();
        $(".select2").select2({width: '100%', placeholder: 'Select an option'});
        $('.ts').TouchSpin({ min: 1,max: 10000000, maxboostedstep: 10, initval: 0, buttondown_class: 'btn btn-secondary-gradien', buttonup_class: 'btn btn-info-gradien' });
        $('#quality').barrating('show', {theme: 'bars-1to10'});

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
    </script>

    <!-- Preview Image -->
    <script type="text/javascript">
        function PreviewImage(IdItem) {
            $.ajax({
                type: "GET",
                url: "/PreviewImageItem",
                data: {'IdItem' : IdItem},
                success: function (response) {
                    Swal({
                        title: "Image Item",
                        text: "Do you want printing this image?",
                        imageUrl: "{{ asset('uploads/Temp/Image-Item-') }}"+IdItem+".jpg",
                        imageWidth: 500,
                        imageHeight: 300,
                        imageAlt: "Item Image",
                        showCancelButton: true,
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Yes',
                        showLoaderOnConfirm: true,
                        confirmButtonClass: 'btn btn-primary-gradien',
                        cancelButtonText: '<i class="fa fa-thumbs-down"></i> NO',
                        preConfirm: function (){
                            window.open("{{ asset('uploads/Temp/Image-Item-') }}" + IdItem + ".jpg", "_blank");
                        },
                        allowOutsideClick: false
                    });
                },
                error: function(jqXHR){
                    if(jqXHR.status==0) {
                        SwalMessage('You are offline','Connection to the server has been lost. Please check your internet connection and try again.','error');
                    } else{
                        SwalMessage('Attention','Something went wrong. Please try again later.','warning');;
                    }
                }
            });
        }
    </script>

    <!-- Submit Form -->
    <script type="text/javascript">
        $('#form').on('submit', function(event) {
            $('#finish').attr('disabled', true);
            event.preventDefault()
            var formData = new FormData(this)
            $.ajax({
                type: "POST",
                url: "/ItemDetails",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (data) {
                    window.location.replace('/Items');
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
@endsection
