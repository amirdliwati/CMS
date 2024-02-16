<li class="back-btn">
    <div class="mobile-back text-right"><span>{{__('Back')}}</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
</li>

@if (Hash::check(Auth::User()->email, Auth::User()->password))
    <div class="alert alert-warning" role="alert">
        <i class="mdi mdi-alert-outline alert-icon"></i>
        {{__('Please reset your password')}}!
    </div>
@elseif (Auth::user()->roles->where('title','Admin_role') != '[]')
    @php $Role = Auth::user()->roles->where('title','Admin_role')->first() @endphp
    <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="command"></i><span>{{__('Manager')}}</span></a>
        <ul class="nav-submenu menu-content">
            @include('layouts/navigation/admin')
        </ul>
    </li>

    <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="dollar-sign"></i><span>{{__('Financial')}}</span></a>
        <ul class="nav-submenu menu-content">
            @include('layouts/navigation/finacial')
        </ul>
    </li>

    <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="users"></i><span>{{__('Human Resoueces')}}</span></a>
        <ul class="nav-submenu menu-content">
            @include('layouts/navigation/hr')
        </ul>
    </li>

    <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="shopping-bag"></i><span>{{__('Inventory')}}</span></a>
        <ul class="nav-submenu menu-content">
            @include('layouts/navigation/inventory')
        </ul>
    </li>

@elseif (Auth::user()->roles->count() == 1)
    @foreach (Auth::user()->roles->where('active',1) as $Role)
        @if($Role->title == 'HR_role') @include('layouts/navigation/hr') @endif
        @if($Role->title == 'Financial_role') @include('layouts/navigation/finacial') @endif
        @if($Role->title == 'Inventory_role') @include('layouts/navigation/inventory') @endif
    @endforeach

@else ()
    @foreach (Auth::user()->roles->where('active',1) as $Role)
        @if($Role->title == 'HR_role')
            <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="users"></i><span>{{__('Human Resoueces')}}</span></a>
                <ul class="nav-submenu menu-content">
                    @include('layouts/navigation/hr')
                </ul>
            </li>
        @endif
        @if($Role->title == 'Financial_role')
            <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="dollar-sign"></i><span>{{__('Financial')}}</span></a>
                <ul class="nav-submenu menu-content">
                    @include('layouts/navigation/finacial')
                </ul>
            </li>
        @endif
        @if($Role->title == 'Inventory_role')
            <li class="dropdown"><a class="nav-link menu-title" href="#"><i data-feather="shopping-bag"></i><span>{{__('Inventory')}}</span></a>
                <ul class="nav-submenu menu-content">
                    @include('layouts/navigation/inventory')
                </ul>
            </li>
        @endif
    @endforeach
@endif

@foreach (Auth::user()->roles->where('active',1) as $Role)
    @if ($Role->permissions->where('title','ManageEmails') != '[]')
        @include('layouts/navigation/email')
        @break
    @endif
@endforeach
@include('layouts/navigation/account')
