@if($sewingLineCapacity->count())
  @php
    $total_operator = 0;
    $total_helper = 0;
  @endphp
  @foreach($sewingLineCapacity as $lineCapacity)
    @php
      $total_operator += $lineCapacity->operator;
      $total_helper += $lineCapacity->helper;
    @endphp

    <tr style="height: 40px;">
      <input type="hidden" name="line_id[]" value="{{ $lineCapacity->line_id }}">
      <td>{{ $lineCapacity->line->line_no }}</td>
      <td>
        <input type="number" name="operator[]" value="{{ $lineCapacity->operator }}" class="text-right  operator"
               size="20">
      </td>
      <td>
        <input type="number" name="helper[]" value="{{ $lineCapacity->helper }}" class="text-right  helper" size="20">
      </td>
      <td>
        <input type="text" name="absent_percent[]" value="{{ $lineCapacity->absent_percent }}"
               class="text-right  absent_percent" size="20">
      </td>
      <td>
        <input type="number" name="working_hour[]" value="{{ $lineCapacity->working_hour }}"
               class="text-right  working_hour" size="20">
      </td>
      <td>
        <input type="text" name="line_efficiency[]" value="{{ $lineCapacity->line_efficiency }}"
               class="text-right  line_efficiency" size="20">
      </td>
      <td>
        <input type="text" name="capacity_available_minutes[]" value="{{ $lineCapacity->capacity_available_minutes }}"
               style="width:110px;height:25px; background-color: #cecece;"
               class="text-right  capacity_available_minutes" readonly>
      </td>
    </tr>
  @endforeach
  <tr style="height: 40px;">
    <td><b>Total</b></td>
    <td><input type="number" class="text-right " disabled="disabled" value="{{ $total_operator ?? 0 }}"></td>
    <td><input type="number" class="text-right " disabled="disabled" value="{{ $total_helper ?? 0 }}"></td>
  </tr>
@else
  @foreach($lines as $line)
    <tr style="height: 35px;">
      <input type="hidden" name="line_id[]" value="{{ $line->id }}">
      <td>{{ $line->line_no }}</td>
      <td>
        <input type="number" name="operator[]" style="width:110px;height:25px;" size="20" class="operator text-right">
      </td>
      <td>
        <input type="number" name="helper[]" style="width:110px;height:25px;" size="20" class="helper text-right">
      </td>
      <td>
        <input type="text" name="absent_percent[]" style="width:110px;height:25px;" size="20"
               class="absent_percent text-right">
      </td>
      <td>
        <input type="number" name="working_hour[]" style="width:110px;height:25px;" size="20"
               class="working_hour text-right">
      </td>
      <td>
        <input type="text" name="line_efficiency[]" style="width:110px;height:25px;" size="20"
               class="line_efficiency text-right">
      </td>
      <td>
        <input type="text" name="capacity_available_minutes[]"
               style="width:110px;height:25px; background-color: #cecece;" size="20"
               class="capacity_available_minutes text-right" readonly>
      </td>
    </tr>
  @endforeach
@endif