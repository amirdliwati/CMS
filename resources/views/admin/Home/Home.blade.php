<div class="col-md-12">
    <div class="card">
        <div class="card-header b-l-primary border-3"><h5> {{__('Exchange Currency')}} </h5> @include('layouts/button_card') </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead class="thead-light">
                    <tr style="text-align: center;">
                        <th class="text-primary">{{__('Name')}}</th>
                        <th class="text-primary">{{__('Flag')}}</th>
                        <th class="text-primary">{{__('Code')}}</th>
                        <th class="text-primary">{{__('Symbol')}}</th>
                        <th class="text-primary">{{__('Point')}}</th>
                        <th class="text-primary">{{__('Dollar Selling')}}</th>
                        <th class="text-primary">{{__('Dollar Buy')}}</th>
                        <th class="text-primary">{{__('Last Update')}}</th>
                    </tr><!--end tr-->
                </thead>
                <tbody style="text-align: center;">
                    @foreach ($Admin['Currencies'] as $Currency)
                    <tr style="text-align: center;">
                        <td>{{$Currency->name}}</td>
                        @if($Currency->id != 11)
                            <td><i class="flag-icon flag-icon-{{$Currency->flag}}"></i></td>
                        @else
                            <td><img src="/{{$Currency->flag}}" alt="" style="width:20px;height:15px;"></td>
                        @endif
                        <td>{{$Currency->code}}</td>
                        <td>{{$Currency->symbol}}</td>
                        @if(empty($Currency->point))
                            <td>{{__('N/A')}}</td>
                        @else
                            <td>{{$Currency->point}}</td>
                        @endif

                        @if(empty($Currency->dollar_selling))
                            <td>{{__('N/A')}}</td>
                        @else
                            <td>{{$Currency->dollar_selling}}</td>
                        @endif

                        @if(empty($Currency->dollar_buy))
                            <td>{{__('N/A')}}</td>
                        @else
                            <td>{{$Currency->dollar_buy}}</td>
                        @endif

                        <td><span class="badge badge-success">{{\Carbon\Carbon::parse($Currency->updated_at)->isoFormat('Do MMMM YYYY, h:mm:ss a')}}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table> <!--end table-->
        </div>
    </div>
</div> <!-- end col -->
