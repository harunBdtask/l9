@php
  $count = 0;
  $total_operator = 0;
  $total_helper = 0;
  $total_target = 0;
  $total_hour = 0;
  $total_input_plan = 0;
  $line_ids = [];
@endphp
@if($linesWiseTarget && count($linesWiseTarget) > 0)
  @foreach($linesWiseTarget as $linesTarget)
    @php
      $count++;
      if (!in_array($linesTarget->line_id, $line_ids)) {
        $line_ids[] = $linesTarget->line_id;
        $total_operator += $linesTarget->operator;
        $total_helper += $linesTarget->helper;
        $total_target += $linesTarget->target;
      }
      $total_hour += $linesTarget->wh;
      $total_input_plan += $linesTarget->input_plan;
      $hourly_target = $linesTarget->target ? $linesTarget->target : ceil($linesTarget->smv * $linesTarget->efficiency);
      $day_target = ceil($linesTarget->wh * $hourly_target);
    @endphp
    <tr style="height: 40px;">
        <input type="hidden" name="line_id[]" value="{{ $linesTarget->line_id }}">
        <td>{{ $linesTarget->line->line_no }}</td>
        <td>
          <input type="number" style="width:70px;height:25px;" name="operator[]" value="{{ $linesTarget->operator }}" class="text-right">
        </td>
        <td>
          <input type="number" style="width:70px;height:25px;" name="helper[]" value="{{ $linesTarget->helper }}"  class="text-right">
        </td>
        <td>
          <input type="number" step=".0001" style="width:70px;height:25px;" name="smv[]" value="{{ $linesTarget->smv ?? 0 }}"  class="text-right">
        </td>
        <td>
          <input type="number" step=".0001" style="width:70px;height:25px;" name="efficiency[]" value="{{ $linesTarget->efficiency ?? 0 }}"  class="text-right">
        </td>
        <td>
          <span class="hourly_target_value">{{ $hourly_target }}</span>
          <input type="text" style="width:70px;height:25px;" name="target[]" value="{{ $hourly_target }}"  class="text-right hide">
        </td>
        <td>
          <input type="number" style="width:70px;height:25px;"  name="wh[]" value="{{ $linesTarget->wh }}"  class="text-right">
        </td>
        <td>
          <span class="day_target_value">{{ $day_target }}</span>
        </td>
        <td>
          <input type="number" style="width:70px;height:25px;" name="input_plan[]" value="{{ $linesTarget->input_plan }}" class="text-right">
        </td>
        <td >
          <input type="text" style="width:80%; height:25px;" class="" name="remarks[]" value="{{ $linesTarget->remarks }}">
        </td>
        <td>
          <button type="button" class="btn btn-xs btn-success duplicate-line-target">
            <i class="glyphicon glyphicon-plus"></i>
          </button>
          <button type="button" class="btn btn-xs btn-danger del-duplicate-line-target">
            <i class="glyphicon glyphicon-remove"></i>
          </button>
        </td>
    </tr>
  @endforeach
    <tr class="tr-height" style="font-weight: bold">
      <td>Total</td>
      <td id="total_operator_value">{{ $total_operator ?? 0 }}</td>
      <td id="total_helper_value">{{ $total_helper }}</td>
      <td colspan="2">&nbsp;</td>
      <td id="total_hourly_target_value">{{ $total_target }}</td>
      <td id="total_hour_value">{{ $total_hour }}</td>
      <td>&nbsp;</td>
      <td id="total_input_plan_value">{{ $total_input_plan }}</td>
      <td colspan="2">&nbsp;</td>
    </tr>
@else
  @foreach($lines as $line)
    @php $count++; @endphp
    <tr style="height: 35px;">
      <input type="hidden" name="line_id[]" value="{{ $line->id }}">
      <td>{{ $line->line_no }}</td>
      <td>
        <input type="number" name="operator[]" class="text-right" style="width:70px;height:25px;" size="60">
      </td>
      <td>
        <input type="number"  name="helper[]" class="text-right" style="width:70px;height:25px;" size="60">
      </td>
      <td>
        <input type="number" step=".0001" style="width:70px;height:25px;" name="smv[]"  class="text-right">
      </td>
      <td>
        <input type="number" step=".0001" style="width:70px;height:25px;" name="efficiency[]"  class="text-right">
      </td>
      <td>
        <span class="hourly_target_value"></span>
        <input type="number"  name="target[]" class="text-right hide" style="width:70px;height:25px;" size="60">
      </td>
      <td>
        <input type="number"  name="wh[]" class="text-right" style="width:70px;height:25px;" size="60">
      </td>
      <td>
        <span class="day_target_value"></span>
      </td>
      <td>
        <input type="number" name="input_plan[]"  class="text-right" style="width:70px;height:25px;" size="60">
      </td>
      <td >
        <input type="text" style="width:80%; height:25px;" class="" name="remarks[]">
      </td>
      <td >
        <button type="button" class="btn btn-xs btn-success duplicate-line-target"><i class="glyphicon glyphicon-plus"></i></button> |
        <button type="button" class="btn btn-xs btn-danger del-duplicate-line-target"><i class="glyphicon glyphicon-remove"></i></button>
      </td>
    </tr>
  @endforeach
@endif
@if($count > 0)
  <tr style="height: 45px">
    <td colspan="11">
      <button type="submit" style="" class="text-center btn btn-success btn-sm sewing-target-btn">Submit</button>
    </td>
  </tr>
@else
  <tr style="height: 40px">
    <td colspan="11" class="text-center text-danger">Not found</td>
  </tr>
@endif