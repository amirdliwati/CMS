<li class="onhover-dropdown p-0">
    <div class="media profile-media"><img class="b-r-10" src="{{ asset( Auth::user()->employee->emp_image ) }}" alt="" style="width:35px;height:35px;">
      <div class="media-body"><span>{{ Auth::user()->name }}</span>
        <p class="mb-0 font-roboto">{{ Auth::user()->roles->first()->blug }} <i class="middle fa fa-angle-down"></i></p>
      </div>
    </div>
    <ul class="profile-dropdown onhover-show-div">
        <li onclick="GoToPage('{{ route('MyProfile') }}')"><i class="fa fa-user-md font-primary mr-2"></i>{{__('My Profile')}}</li>

        <li onclick="GoToPage('{{ route('ChangePassword') }}')" title="{{__('Change Password')}}"><i class="fa fa-key font-warning mr-2"></i>{{__('Password')}}</li>

        <li onclick="GoToPage('{{ route('MyAttendances') }}')" title="{{__('My Attendances')}}"><i class="fa fa-calendar-o font-info mr-2"></i>{{__('Attendances')}}</li>

        @if (Auth::User()->id == 1)
            <li onclick="GoToPage('/telescope')" title="{{__('Monitoring')}}"><i class="fa fa-eye font-success mr-2"></i>{{__('Monitoring')}}</li>
        @endif

        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();"><i class="fa fa-sign-out font-danger mr-2"> </i>{{ __('Log Out') }}</x-responsive-nav-link>
            </form>
        </li>
    </ul>
</li>
