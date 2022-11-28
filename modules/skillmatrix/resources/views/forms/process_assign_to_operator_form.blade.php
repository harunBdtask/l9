@if(!$sewingProcesses->isEmpty())
@foreach($sewingProcesses as $key => $processObj)
  <tr class="tr-design">
    <td>
      <label class="md-check">
        {!! Form::checkbox("sewing_process_id[$key]", $processObj->sewing_process_id ?? null, $processObj->process_assigned) !!}
        <i class="blue"></i>
      </label>
    </td>  
    <td>
      <b>{{ $processObj->sewingProcess->name ?? '' }}</b>
    </td>
    <td>
      <b>{{ $processObj->sewingProcess->standard_capacity ?? '' }}</b>
      {!! Form::hidden("standard_capacity[$key]", $processObj->sewingProcess->standard_capacity ?? '', ['class' => 'standard-capacity']) !!}
    </td>
    <td>
      {!! Form::number("capacity[$key]", $processObj->capacity ?? null, ['class' => 'b-none orange-A100 number-right s-capacity w-full', 'placeholder' => 'Write here']) !!}
      <span class="scapacity"></span>
    </td>
    <td>
      <b class="efficiency">{{ $processObj->efficiency }} %</b>
      {!! Form::hidden("efficiency[$key]", $processObj->efficiency ?? null) !!}
    </td>
  </tr>
@endforeach
  <tr style="height:50px">
    <td colspan="6">
      {!! Form::hidden('edit', $sewingProcesses->sum('process_assigned') ?? null) !!}
      {!! Form::button('Submit', ['class' => 'btn btn-success btn-sm operator-skill-save-btn', 'type' => 'button']) !!}
    </td>
  </tr>
@else
<tr style="height:50px">
    <td colspan="6" class="text-danger font-weight-bold">
      There is no sewing process for this machine
    </td>
  </tr>
@endif