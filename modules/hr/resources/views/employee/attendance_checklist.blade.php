{{--<style>--}}
{{--   .reportTable {--}}
{{--      margin-bottom: 1rem;--}}
{{--      width: 100%;--}}
{{--      max-width: 100%;--}}
{{--      font-size: 12px;--}}
{{--      border-collapse: collapse;--}}
{{--   }--}}

{{--   .reportTable thead,--}}
{{--   .reportTable tbody,--}}
{{--   .reportTable th {--}}
{{--      padding: 3px;--}}
{{--      font-size: 12px;--}}
{{--      text-align: center;--}}
{{--   }--}}

{{--   .reportTable th,--}}
{{--   .reportTable td {--}}
{{--      border: 1px solid #ccc;--}}
{{--   }--}}

{{--   .table td, .table th {--}}
{{--      padding: 0.1rem;--}}
{{--      vertical-align: middle;--}}
{{--   }--}}

{{--   @page {--}}
{{--      margin: 100px 35px 35px 35px;!important;--}}
{{--   }--}}

{{--   header {--}}
{{--      position: fixed;--}}
{{--      top: -100px;--}}
{{--      left: 0;--}}
{{--      right: 0;--}}
{{--      text-align: center;--}}
{{--      height: 50px;--}}
{{--   }--}}

{{--   footer {--}}
{{--      position: fixed;--}}
{{--      bottom: -50px;--}}
{{--      font-size: 12px;--}}
{{--      left: 0;--}}
{{--      right: 0;--}}
{{--      text-align: center;--}}
{{--      height: 50px;--}}
{{--   }--}}

{{--   header h4 {--}}
{{--      margin: 2px 0 2px 0;--}}
{{--   }--}}

{{--   header h2 {--}}
{{--      margin-bottom: 2px;--}}
{{--   }--}}

{{--   .spacer {--}}
{{--      height: .5rem;--}}
{{--   }--}}

{{--   tr {--}}
{{--      page-break-inside: avoid;--}}
{{--   }--}}

{{--   @media print {--}}
{{--      @page {--}}
{{--         size: landscape;--}}
{{--         -webkit-transform: rotate(-90deg);--}}
{{--         -moz-transform: rotate(-90deg);--}}
{{--         filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);--}}
{{--         page-break-after: always;--}}
{{--      }--}}

{{--      .badge {--}}
{{--         border-color: transparent;--}}
{{--         -webkit-print-color-adjust: exact;--}}
{{--         border-radius: .25rem;--}}
{{--      }--}}

{{--      .badge-light {--}}
{{--         background-color: #e7e7ff;--}}
{{--         -webkit-print-color-adjust: exact;--}}
{{--      }--}}

{{--      .badge-success {--}}
{{--         background-color: #16682d;--}}
{{--         color: white;--}}
{{--         -webkit-print-color-adjust: exact;--}}
{{--      }--}}

{{--      .badge-danger {--}}
{{--         background-color: #dc222a;--}}
{{--         color: white;--}}
{{--         -webkit-print-color-adjust: exact;--}}
{{--      }--}}
{{--   }--}}

{{--</style>--}}
@include('hr::report-style')
@include('hr::reports.include.header')
{{--@include('reports.include.header')--}}

<div class="row">
    @if($departmentId)
        <div class="col-md-2">
            <?php
            $department = \SkylarkSoft\GoRMG\HR\Models\HrDepartment::find($departmentId);
            ?>
            <span>Department: {{$department->name}}</span>
        </div>
    @endif

    @if($sectionId)
        <div class="col-md-2">
            <?php
            $section = \SkylarkSoft\GoRMG\HR\Models\HrSection::find($sectionId);
            ?>
            <span>Section: {{$section->name}}</span>
        </div>
    @endif

    @if($designationId)
        <div class="col-md-3">
            <?php
            $designation = \SkylarkSoft\GoRMG\HR\Models\HrDesignation::find($designationId);
            ?>
            <span>Designation: {{$designation->name}}</span>
        </div>
    @endif

    @if($type)
        <div class="col-md-2">
            <span>Type: {{ ucfirst($type) }}</span>
        </div>
    @endif

    @if($date)
        <div class="col-md-2">
            <span>Date: {{ \Carbon\Carbon::parse($date)->format('d-M-Y') }}</span>
        </div>
    @endif
</div>

<br>

<div class="row">
    <div class="col-md-12">
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
                <th>Status</th>
                <td style="width: 80px"></td>
                <td style="width: 80px"></td>
            </tr>
            </thead>
            <tbody>
            @if($reports && $reports->count())
                @foreach($reports as $report)
                    @php
                        $na_status = '<span class="badge badge-light">N/A</span>';
                        $in_status = '<span class="badge badge-success">In Time</span>';
                        $late_status = '<span class="badge badge-danger">Late</span>';
                        $status = $na_status;
                        if ($report->att_in) {
                           $in_time = Carbon\Carbon::parse($report->date.' '.$report->att_in);
                           if ($report->employeeOfficialInfo->type == 'staff') {
                              $attendance_valid_time = Carbon\Carbon::parse($report->date.' 08:15:00');
                              $status = $late_status;
                              if ($in_time < $attendance_valid_time) {
                                 $status = $in_status;
                              }
                           } else {
                              $attendance_valid_time = Carbon\Carbon::parse($report->date.' 08:05:00');
                              $status = $late_status;
                              if ($in_time < $attendance_valid_time) {
                                 $status = $in_status;
                              }
                           }
                        }
                    @endphp
                    <tr>
                        <td>{{ $report->userid }}</td>
                        <td>{{ $report->name }}</td>
                        <td>{{ $report->employeeOfficialInfo->departmentDetails->name }}</td>
                        <td>{{ $report->employeeOfficialInfo->designationDetails->name }}</td>
                        <td>{{ $report->employeeOfficialInfo->sectionDetails->name }}</td>
                        <td>{{ ucfirst($report->employeeOfficialInfo->type) }}</td>
                        <td>{{ $report->date ? date('d/m/Y', strtotime($report->date)) : '' }}</td>
                        <td>{{ $report->att_in ?? 'N/A' }}</td>
                        <td>{{ $report->att_out ?? 'N/A' }}</td>
                        <td>{!! $status !!}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="12">No Data Found</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
