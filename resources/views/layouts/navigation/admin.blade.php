<li><a class="submenu-title" href="#"><i data-feather="dollar-sign"></i>{{__('Financial')}}<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span></a>
    <ul class="nav-sub-childmenu submenu-content">
        @if ($Role->permissions->where('title','CurrencyAdmin') != '[]')
            <li><a href="/Currency"><i class="mdi mdi-currency-eur text-warning mr-2"></i>{{__('Currencies')}}</a></li>
        @endif
        @if ($Role->permissions->where('title','ReceiptsBooksArchive') != '[]')
            <li><a href="/AdminReceiptsBooks"><i class="mdi mdi-book-outline text-success mr-2"></i>{{__('Receipts Books')}}</a></li>
        @endif
    </ul>
</li>


<li><a class="submenu-title" href="#"><i data-feather="users"></i>{{__('Employees')}}<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span></a>
    <ul class="nav-sub-childmenu submenu-content">
        @if (Auth::user()->roles->first()->title == 'Admin_role')
            <li><a href="/ActivityLog"><i class="mdi mdi-account-details text-info mr-2"></i> {{__('Activity Users')}}</a></li>
            <li><a href="/ManageUsers"><i class="mdi mdi-account-group text-warning mr-2"></i> {{__('Manage Users')}}</a></li>
        @endif
    </ul>
</li>


<li><a class="submenu-title" href="#"><i data-feather="settings"></i>{{__('Management')}}<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span></a>
    <ul class="nav-sub-childmenu submenu-content">
        @if ($Role->permissions->where('title','CountryAdmin') != '[]')
            <li><a href="/Countries"><i class="fa fa-flag-o text-danger mr-2"></i>{{__('Countries')}}</a></li>
        @endif
        @if ($Role->permissions->where('title','branch') != '[]')
            <li><a href="/Branches"><i class="mdi mdi-home text-info mr-2"></i>{{__('Branchs')}}</a></li>
        @endif
        @if ($Role->permissions->where('title','department') != '[]')
            <li><a href="/Departments"><i class="mdi mdi-office-building text-success mr-2"></i>{{__('Departments')}}</a></li>
        @endif
    </ul>
</li>


