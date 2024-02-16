<div class="col-md-12">
    <div class="card">
        <div class="card-header b-l-primary border-3"><h5> {{__('Employees Attendance')}} </h5> @include('layouts/button_card') </div>
        <div class="card-body">
            <table id="attendances-table" class="table table-bordered table-hover table-striped dt-responsive" style="border-collapse: collapse; border-spacing: 3; width: 100%;">
                <thead>
                <tr style="text-align: center;">
                    <th class="text-primary">{{__('Attendance ID')}}</th>
                    <th class="text-primary">{{__('Employee Name')}}</th>
                    <th class="text-primary">{{__('Department')}}</th>
                    <th class="text-primary">{{__('Punch State')}}</th>
                    <th class="text-primary">{{__('Punch Time')}}</th>
                    <th class="text-primary">{{__('Device S/N')}}</th>
                    <th class="text-primary">{{__('Device Name')}}</th>
                </tr>
                </thead>
                <tbody style="text-align: center;">

                @foreach ($HR['Attendances'] as $Attendance)
                <tr style="text-align: center;">
                    <td>{{$Attendance->id}}</td>
                    <td>{{$Attendance->employee->first_name}} {{$Attendance->employee->middle_name}} {{$Attendance->employee->last_name}}</td>
                    <td>{{$Attendance->employee->position->department->name}}</td>
                    @switch($Attendance->punch_state)
                        @case(0)
                            <td><span class="badge badge-success"> {{__('Check IN')}} </span></td>
                        @break
                        @case(1)
                            <td><span class="badge badge-danger"> {{__('Check Out')}} </span></td>
                        @break
                        @case(3)
                            <td><span class="badge badge-warning"> {{__('Break IN')}} </span></td>
                        @break
                        @case(2)
                            <td><span class="badge badge-danger"> {{__('Break Out')}} </span></td>
                        @break
                        @case(4)
                            <td><span class="badge badge-info"> {{__('Overtime IN')}} </span></td>
                            <td></td>
                        @break
                        @case(5)
                            <td><span class="badge badge-danger"> {{__('Overtime Out')}} </span></td>
                        @break
                    @endswitch

                    <td><span class="badge badge-info">{{\Carbon\Carbon::parse($Attendance->punch_time)->isoFormat('Do MMMM YYYY, h:mm:ss a')}}</span></td>
                    <td>{{$Attendance->terminal_sn}}</td>
                    <td>{{$Attendance->terminal_alias}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- end col -->
