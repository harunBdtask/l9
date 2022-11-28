@if($lines)
  @foreach($lines as $line)
    <input type="hidden" name="id[]" value="{{ $line->id }}">
    <tr style="height: 30px">
      <td>{{ $line->line_no ?? '' }}</td>
      <td>{{ $line->sewingLineTarget->operator ?? '' }}</td>
      <td>{{ $line->sewingLineTarget->helper ?? '' }}</td>
      <td>{{ $line->sewingLineTarget->wh ?? '' }}</td>
      <td><input type="number" number="" name="add_man_min[]" value="{{ $line->sewingLineTarget->add_man_min ?? '' }}" style="width:70px;height:20px;" size="60" maxlength=""></td>
      <td><input type="number" number="" name="sub_man_min[]" value="{{ $line->sewingLineTarget->sub_man_min ?? '' }}" style="width:70px;height:20px;" size="60" maxlength=""></td>
      <td><input type="number" number="" name="mb[]" value="{{ $line->sewingLineTarget->mb ?? '' }}" style="width:70px;height:20px;" size="60" maxlength=""></td>
      <td><input type="number" number="" name="shading_problem[]" value="{{ $line->sewingLineTarget->shading_problem ?? '' }}" style="width:70px;height:20px;" size="60" maxlength=""></td>
      <td><input type="number" number="" name="late_decision[]" value="{{ $line->sewingLineTarget->late_decision ?? '' }}" style="width:70px;height:20px;" size="60" maxlength=""></td>
      <td><input type="number" number="" name="cutting_problem[]" value="{{ $line->sewingLineTarget->cutting_problem ?? '' }}" style="width:70px;height:20px;" size="60" maxlength=""></td>
      <td><input type="number" number="" name="input_problem[]" value="{{ $line->sewingLineTarget->input_problem ?? '' }}" style="width:70px;height:20px;" size="60" maxlength=""></td>
      <td><input type="number" number="" name="late_to_get_mc[]" value="{{ $line->sewingLineTarget->late_to_get_mc ?? '' }}" style="width:70px;height:20px;" size="60" maxlength=""></td>
      <td><input type="number" number="" name="print_mistake[]" value="{{ $line->sewingLineTarget->print_mistake ?? '' }}" style="width:70px;height:20px;" size="60" maxlength=""></td>
    {{-- <td><input type="number" number="" name="line_status[]" value="{{ $line->sewingLineTarget->line_status ?? '' }}" style="width:150px;height:20px;" size="60" maxlength=""></td> --}}
    </tr>
  @endforeach
@endif    