@include('hr::report-style')
@include('hr::reports.include.header')

<div>
   <div class="row">
      <table class="reportTable" style="border-collapse: collapse">
         <thead id="table-header">
         <tr>
            <th>Unique ID</th>
            <th>Name</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Section</th>
            <th>Type</th>
            <th>Date</th>
            <th>In Time</th>
            <th>Out Time</th>
            <th>Entry By</th>
            <th>Status</th>
         </tr>
         </thead>
         <tbody>
         @if($reports && $reports->count())
            @foreach($reports->sortBy('userid')->groupBy('userid') as $report)
               @php
                  $na_status = '<span class="badge badge-light">N/A</span>';
                  $in_status = '<span class="badge badge-success">In Time</span>';
                  $late_status = '<span class="badge badge-danger">Late</span>';
                  $status = $na_status;
                  $att_in = $report->sortBy('punch_time')->first()->punch_time;
                  $att_out = $report->sortByDesc('punch_time')->first()->punch_time;
                  if ($att_in) {
                     $in_time = Carbon\Carbon::parse($report->first()->attendance_date.' '.$att_in);
                     if ($report->first()->employeeOfficialInfo->type == 'staff') {
                        $attendance_valid_time = Carbon\Carbon::parse($report->first()->attendance_date.' 08:15:00');
                        $status = $late_status;
                        if ($in_time < $attendance_valid_time) {
                           $status = $in_status;
                        }
                     } else {
                        $attendance_valid_time = Carbon\Carbon::parse($report->first()->attendance_date.' 08:05:00');
                        $status = $late_status;
                        if ($in_time < $attendance_valid_time) {
                           $status = $in_status;
                        }
                     }
                  }
               @endphp
               <tr>
                  <td>{{ $report->first()->userid }}</td>
                  <td>{{ $report->first()->name }}</td>
                  <td>{{ $report->first()->employeeOfficialInfo->departmentDetails->name }}</td>
                  <td>{{ $report->first()->employeeOfficialInfo->designationDetails->name }}</td>
                  <td>{{ $report->first()->employeeOfficialInfo->sectionDetails->name }}</td>
                  <td>{{ ucfirst($report->first()->employeeOfficialInfo->type) }}</td>
                  <td>{{ $report->first()->attendance_date ? date('d/m/Y', strtotime($report->first()->attendance_date)) : '' }}</td>
                  <td>{{ $att_in ?? 'N/A' }}</td>
                  <td>{{ $att_out ?? 'N/A' }}</td>
                  <td>{{ $report->first()->createdUser->name }}</td>
                  <td>{!! $status !!}</td>
               </tr>
            @endforeach
         @else
            <tr>
               <td colspan="11">No Data Found</td>
            </tr>
         @endif
         </tbody>
      </table>
   </div>
</div>
