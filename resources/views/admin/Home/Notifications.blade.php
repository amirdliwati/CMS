<!-- Legal docs -->
<?php $TotalLegaldocs = app('App\Http\Controllers\homes\HrController')->Notifications()['LegaldocNotifications']->whereBetween('end_valid',[\Carbon\Carbon::now(),\Carbon\Carbon::now()->addDays(20)])->count() ?>

<?php $Legaldocs = app('App\Http\Controllers\homes\HrController')->Notifications()['LegaldocNotifications']->whereBetween('end_valid',[\Carbon\Carbon::now(),\Carbon\Carbon::now()->addDays(20)]) ?>

<li class="onhover-dropdown">
    <div class="notification-box"><i data-feather="bell"></i><span class="badge badge-pill badge-secondary">{{$TotalLegaldocs}}</span></div>
    <ul class="notification-dropdown onhover-show-div">
        <li>
            <p class="f-w-600 font-roboto">{{__('You have')}} ({{$TotalLegaldocs}}) {{__('notifications')}}</p>
        </li>

        @foreach ($Legaldocs as $Legaldoc)
            <li>
                <p class="mb-0"><i class="fa fa-calendar-o font-warning"></i>{{$Legaldoc->first_name}} {{$Legaldoc->last_name}}
                    @if($Legaldoc->doc_type == 1) (Passport) @elseif($Legaldoc->doc_type == 2) (Visa) @endif
                    {{__('Will End')}}<span class="pull-right">{{\Carbon\Carbon::parse($Legaldoc->end_valid)->isoFormat('Do MMMM YYYY')}}</span></p>
            </li>
        @endforeach
    </ul>
</li>
