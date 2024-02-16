
@if ($Role->permissions->where('title','AddEmp') != '[]')
    <li><a href="/AddEmployee" class="nav-link nav-link-custom"><span><i data-feather="user-plus"></i>{{__('Add Employee')}}</span></a></li>
@endif

@if ($Role->permissions->where('title','ManageEmp') != '[]')
    <li><a href="/Employees" class="nav-link nav-link-custom"><span><i data-feather="users"></i>{{__('Manage Employees')}}</span></a></li>
@endif

@if ($Role->permissions->where('title','ManageEmp') != '[]')
    <li><a href="/HRAttendances" class="nav-link nav-link-custom"><span><i data-feather="calendar"></i>{{__('Attendances')}}</span></a></li>
@endif

@if ($Role->permissions->where('title','position') != '[]')
    <li><a href="/Positions" class="nav-link nav-link-custom"><span><i data-feather="server"></i>{{__('Job Positions')}}</span></a></li>
@endif

@if ($Role->permissions->where('title','RoleEmployee') != '[]')
    <li><a href="/RoleEmployee" class="nav-link nav-link-custom"><span><i data-feather="user-check"></i>{{__('Role Employee')}}</span></a></li>
@endif
