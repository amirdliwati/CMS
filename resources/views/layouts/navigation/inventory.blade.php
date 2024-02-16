
@if ($Role->permissions->where('title','ManagementItems') != '[]')
    <li class="dropdown"><a href="/AddItem" class="nav-link nav-link-custom"><span><i data-feather="plus-circle"></i>{{__('Add Item')}}</span></a></li>
@endif

@if ($Role->permissions->whereIn('title',['ViewItemsInventory','ManagementItems']) != '[]')
    <li class="dropdown"><a href="/Items" class="nav-link nav-link-custom"><span><i data-feather="tv"></i>{{__('Items')}}</span></a></li>
@endif

@if ($Role->permissions->whereIn('title',['ManagementInventory','ModifyInventory']) != '[]')
    <li class="dropdown li-custom"><a class="nav-link menu-title submenu-title-custom" href="#"><i data-feather="git-merge"></i>{{__('Inventory Tree')}}<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span></a>
        <ul class="nav-submenu menu-content nav-sub-childmenu-custom">
            @if ($Role->permissions->where('title','ManagementInventory') != '[]')
                <li><a href="/ManagementInventory"><i class="mdi mdi-briefcase-plus text-info mr-2"></i>{{__('Manage Tree')}}</a></li>
            @endif
            @if ($Role->permissions->where('title','ModifyInventory') != '[]')
                <li><a href="/ModifyInventory"><i class="mdi mdi-briefcase-edit text-warning mr-2"></i>{{__('Modify Tree')}}</a></li>
            @endif
        </ul>
    </li>
@endif

@if ($Role->permissions->where('title','ManageSubInventory') != '[]')
    <li class="dropdown"><a href="/ManageSubInventory" class="nav-link nav-link-custom"><span><i data-feather="command"></i>{{__('Sub Inventory')}}</span></a></li>
@endif




