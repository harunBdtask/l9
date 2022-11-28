<table class="reportTable" style="border-collapse: collapse;">
  <thead>
  <tr>
    <th colspan="4" style="font-size: 14px; font-weight: bold">
      Sewingoutput Challan List
    </th>
  </tr>
  <tr>
    <th>Date</th>
    <th>Challan No</th>
    <th>User Name</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody class="color-wise-report">
  @if($reportData)
    @foreach($reportData as $report)
      @foreach($report->groupBy('user_id') as $reportGroupByUser)
        @php
          $singleRow = $reportGroupByUser->first();
        @endphp
        <tr>
          <td>{{ $singleRow->createDate ?? 'N/A'}}</td>
          <td>{{ $singleRow->output_challan_no }}</td>
          <td>{{ $singleRow->user->email ?? 'N/A'}}</td>
          <td>
            <a class="btn btn-sm white" href="{{ url('/view-sewingoutput-challan/'.$singleRow->output_challan_no) }}">
              <i class="fa fa-eye"></i>
            </a>
          </td>
        </tr>
      @endforeach
    @endforeach
  @else
    <tr>
      <td colspan="3" class="text-danger text-center">Not found
      <td>
    </tr>
  @endif
  </tbody>
</table>