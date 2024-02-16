<!-- EMPLOYEE E-MAIL ADDRESS -->
<div id="phoneval" class="ribbon-wrapper card">
    <div class="card-body">
        <div class="ribbon ribbon-bookmark ribbon-success">{{__('Employee E-mail Address')}}</div>
        <div class="row">
            <div class="col-md-6">
                <label for="email" class="col-form-label text-right">{{__('Personal E-mail Address')}}</label>
                <div class="input-group">
                     <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="fa fa-envelope-o"></i></span></div>
                     <input type="email" class="form-control" placeholder="mymail@mail.com" id="email" name="email" pattern="/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i"  value="{{$Employee->email}}" maxlength="200" autofocus required>
                </div>
            </div><!-- end col -->
            <div class="col-md-6">
                <label for="system_email" class="col-form-label text-right">{{__('System E-mail Address')}}</label>
                <div class="input-group">
                     <div class="input-group-append bg-custom b-0"><span class="input-group-text"><i class="fa fa-envelope-o"></i></span></div>
                     <input type="email" class="form-control mx" placeholder="mymail@system.com" id="system_email" name="system_email" pattern="/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i" value="{{$Employee->system_email}}"  autocomplete="system_email" maxlength="200" autofocus required>
                </div>
            </div><!-- end col -->
        </div> <!-- end row -->
    </div>
</div><!-- end MAIL -->
