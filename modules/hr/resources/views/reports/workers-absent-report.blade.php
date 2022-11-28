@include('hr::report-style')
@include('hr::reports.include.header')

<div class="row">
   <div class="col-md-12">
      <h5><b>Date:</b> {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h5>
      <table class="reportTable">
         <tr>
            <td style="width: 30px"><b>SL</b></td>
            <td style="width: 50px"><b>SSL</b></td>
            <td style="width: 100px"><b>Unique ID</b></td>
            <td style="width: 200px"><b>Name</b></td>
            <td style="width: 150px"><b>Designation</b></td>
            <td style="width: 100px"><b>Code</b></td>
            <td style="width: 100px"><b>Grade</b></td>
            <td style="width: 100px"><b>Persent</b></td>
            <td style="width: 100px"><b>Last Present</b></td>
            <td><b>Signature</b></td>
         </tr>
      </table>
   </div>
</div>


<div class="row">
   <div class="col-md-12">
       <?php
       $counter = 1;
       ?>
      @foreach($attendances as $key => $deptAttendances)
         @if($key)
            <h3><b>{{ $key }}</b></h3>
            <table class="reportTable">
               @foreach($deptAttendances as $att)
                  <tr>
                     <td style="width: 30px">{{ $counter }}</td>
                     <td style="width: 50px">1</td>
                     <td style="width: 100px">{{ $att->userid }}</td>
                     <td style="width: 200px">{{ $att->employeeOfficialInfo->employeeBasicInfo->screen_name }}</td>
                     <td style="width: 150px">{{ $att->employeeOfficialInfo->designationDetails->name }}</td>
                     <td style="width: 100px">{{ $att->employeeOfficialInfo->code }}</td>
                     <td style="width: 100px">{{ $att->employeeOfficialInfo->grade->name }}</td>
                     <td style="width: 100px"></td>
                     <td style="width: 100px">
                         <?php
                         $lastPresent = \SkylarkSoft\GoRMG\HR\Models\HrAttendance::where('date', '<', $date)
                             ->where('userid', $att->userid)
                             ->whereNotNull('att_in')
                             ->orderByDesc('date')
                             ->first();

                         if ($lastPresent) {
                             echo \Carbon\Carbon::parse($lastPresent->date)->format('d-M-Y');
                         } else {
                             echo 'n/a';
                         }

                         ?>
                     </td>
                     <td></td>
                  </tr>
                    <?php
                    $counter++;
                    ?>
               @endforeach
            </table>
         @endif
      @endforeach

    </div>
</div>
