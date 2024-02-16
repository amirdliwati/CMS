@if ($Role->permissions->whereIn('title',['ReceiptsBooksManage','ReceiptsBooksReport']) != '[]')
    <li class="dropdown li-custom"><a class="nav-link menu-title submenu-title-custom" href="#"><i data-feather="repeat"></i>{{__('Receipts Books')}}<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span></a>
        <ul class="nav-submenu menu-content nav-sub-childmenu-custom">
            @if ($Role->permissions->where('title','ReceiptsBooksManage') != '[]')
                <li><a href="/ReceiptsBooks"><i class="mdi mdi-arrow-down-bold-outline text-primary"></i><i class="mdi mdi-arrow-up-bold-outline text-warning mr-2"></i>{{__('Receipt')}}</a></li>
            @endif
            @if ($Role->permissions->where('title','ReceiptsBooksReport') != '[]')
                <li><a href="/ReceiptsBooksFinancial"><i class="mdi mdi-archive text-success mr-2"></i>{{__('Archive')}}</a></li>
            @endif
        </ul>
    </li>
@endif

@if ($Role->permissions->where('title','ManagementFinancialEmployees') != '[]')
    <li class="dropdown li-custom"><a class="nav-link menu-title submenu-title-custom" href="#"><i data-feather="users"></i>{{__('Employees Finance')}}<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span></a>
        <ul class="nav-submenu menu-content nav-sub-childmenu-custom">
            <li><a href="/ManagementFinancialEmployees"><i class="mdi mdi-account-card-details text-info mr-2"></i>{{__('Financial')}}</a></li>
            <li><a href="/Attendances"><i class="mdi mdi-calendar-check text-warning mr-2"></i>{{__('Attendances')}}</a></li>
        </ul>
    </li>
@endif

@if ($Role->permissions->whereIn('title',['ReportsFinancialReceiptsBooks','ReportsFinancialEmployees']) != '[]')
    <li class="dropdown li-custom"><a class="nav-link menu-title submenu-title-custom" href="#"><i data-feather="bar-chart-2"></i>{{__('Reports')}}<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span></a>
        <ul class="nav-submenu menu-content nav-sub-childmenu-custom">
            @if ($Role->permissions->where('title','ReportsFinancialReceiptsBooks') != '[]')
                <li><a href="/ReportsFinancialReceiptsBooks"><i class="mdi mdi-file-chart text-danger mr-2"></i>{{__('Receipts Books')}}</a></li>
            @endif
            @if ($Role->permissions->where('title','ReportsFinancialEmployees') != '[]')
                <li><a href="/ReportsFinancialEmployees"><i class="mdi mdi-chart-gantt text-warning mr-2"></i>Payrolls Reports</a></li>
            @endif
        </ul>
    </li>
@endif

@if ($Role->permissions->whereIn('title',['ManageAccountsFinancial','ModifyAccountsFinancial']) != '[]')
    <li class="dropdown li-custom"><a class="nav-link menu-title submenu-title-custom" href="#"><i data-feather="git-merge"></i>{{__('Accounts')}}<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span></a>
        <ul class="nav-submenu menu-content nav-sub-childmenu-custom">
            @if ($Role->permissions->where('title','ManageAccountsFinancial') != '[]')
                <li><a href="/ManageAccounts"><i class="fa fa-credit-card text-success mr-2"></i>{{__('Accounts')}}</a></li>
            @endif
            @if ($Role->permissions->where('title','ManageAccountsFinancial') != '[]')
                <li><a href="/AccountsTree"><i class="mdi mdi-file-tree text-info mr-2"></i>{{__('Tree of Accounts')}}</a></li>
            @endif
            @if ($Role->permissions->where('title','ModifyAccountsFinancial') != '[]')
                <li><a href="/ModifyAccountsTree"><i class="mdi mdi-briefcase-edit text-warning mr-2"></i>{{__('Modify Tree')}}</a></li>
            @endif
        </ul>
    </li>
@endif



