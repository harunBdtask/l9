<table class="reportTable">
  <thead>
  <tr>
    <th>Date</th>
    <th>Working Hour</th>
  </tr>
  </thead>
  <tbody class="wh-table-body">
  @if($sewing_working_hours)
    @foreach($sewing_working_hours as $sewing_working_hour)
      <tr>
        <td>
          {{ date('d/m/Y', strtotime($sewing_working_hour->working_date ?? $sewing_working_hour['working_date'])) }}
          {!! Form::hidden('working_dates[]', $sewing_working_hour->working_date ?? $sewing_working_hour['working_date']) !!}
          <br>
          <span class="text-danger working_dates"></span>
        </td>
        <td>
          {!! Form::number('working_hours[]', $sewing_working_hour->working_hour ?? 10) !!}
          <br>
          <span class="text-danger working_hours"></span>
        </td>
      </tr>
    @endforeach
  @endif
  </tbody>
</table>