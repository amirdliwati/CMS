<li class="onhover-dropdown">
    <div class="notification-box"><i data-feather="bell"></i><span class="badge badge-pill badge-secondary">{{Auth::user()->unreadNotifications->count()}}</span></div>
    <ul class="notification-dropdown onhover-show-div">
        @if(Auth::user()->unreadNotifications->count() > 0)
            <li>
                <p class="f-w-600 font-roboto">{{__('You have')}} ({{Auth::user()->unreadNotifications->count()}}) {{__('unseen notifications')}}</p>
            </li>
        @endif

        @forelse (Auth::user()->unreadNotifications as $Notification)
            <li>
                <p><i class="fa fa-circle-o font-{{$Notification->data['notify_color']}} mr-2"></i>{{$Notification->data['message']}} <span class="font-{{$Notification->data['action_color']}}"> {{$Notification->data['action']}} </span> <span class="pull-right">{{$Notification->data['date']}}</span></p>
            </li>

            @if ($loop->iteration == 3) @break @endif
        @empty
            <br><p class="mb-0 ml-2">{{__('You don not have notifications')}}.</p>
        @endforelse
            <br>
        <li class="text-center"><button type="button" class="btn-sm btn-pill btn-outline-primary" onclick="GoToPage('{{route('Notifications')}}')"><i class="fa fa-eye mr-2"></i>{{__('View All')}}</button></li>
    </ul>
</li>
