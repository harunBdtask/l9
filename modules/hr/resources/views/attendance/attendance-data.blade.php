@extends('hr::layout')
@section('title', 'Attendance Data')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Attendance Data</h2>
            </div>
            <div class="box-body">
                <div class="col-md-6">
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    {!! Form::open(['url' => 'get-attendance-data', 'method' => 'GET']) !!}
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="q"
                               value="{{ request('q') ?? '' }}" placeholder="Search">
                        <span class="input-group-btn">
                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                        </span>
                    </div>
                    {!! Form::close() !!}
                </div>
                @include('partials.response-message')

                <table class="reportTable">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>UserID (Punch Card id)</th>
                        <th>Employee Name</th>
                        <th>Punch Time</th>
                        <th>Attendance Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($attendanceData))
                        @foreach($attendanceData as $attendance)
                            <tr class="tr-height">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $attendance->userid }}</td>
                                <td>{{ $attendance->employeeOfficialInfo->employeeBasicInfo->first_name ." ". $attendance->employeeOfficialInfo->employeeBasicInfo->last_name }}</td>
                                <td>{{ $attendance->punch_time }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="tr-height">
                            <td colspan="7" class="text-center text-danger">No Attendance Data
                            <td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
