@php
    $count = 0;
    $total_operator = 0;
    $total_helper = 0;
    $total_target = 0;
    $total_hour = 0;
    $line_ids = [];
@endphp
@if(count($linesWiseTargets) > 0)
    @foreach($linesWiseTargets as $linesTarget)

        @php
            $count++;
            if (!in_array($linesTarget->line_id, $line_ids)) {
              $line_ids[] = $linesTarget->line_id;
              $total_operator += $linesTarget->operator;
              $total_helper += $linesTarget->helper;
              $total_target += $linesTarget->target;
            }
            $total_hour += $linesTarget->wh;
        @endphp

        <tr style="height: 40px;">
            <input type="hidden" name="line_id[]" value="{{ $linesTarget->line_id }}">
            <td>{{ $linesTarget->line->line_no }}</td>
            <td>
                <input type="number" style="width:70px;height:25px;" name="operator[]"
                       value="{{ $linesTarget->operator }}" class="number-right">
            </td>
            <td>
                <input type="number" style="width:70px;height:25px;" name="helper[]" value="{{ $linesTarget->helper }}"
                       class="number-right">
            </td>
            <td>
                <input type="number" style="width:70px;height:25px;" name="target[]" value="{{ $linesTarget->target }}"
                       class="number-right">
            </td>
            <td>
                <input type="number" style="width:70px;height:25px;" name="wh[]" value="{{ $linesTarget->wh }}"
                       class="number-right">
            </td>
            <td>
                <input type="number" style="width:70px;height:25px;" name="input_plan[]"
                       value="{{ $linesTarget->input_plan }}" class="number-right">
            </td>
            <td>
                <input type="text" style="width:80%; height:25px;" class=""
                       name="remarks[]" value="{{ $linesTarget->remarks }}">
            </td>
            <td>
                <button type="button" class="btn btn-xs btn-success duplicate-line-target">
                    <em class="glyphicon glyphicon-plus"></em>
                </button>
                <button type="button" class="btn btn-xs btn-danger del-duplicate-line-target">
                    <em class="glyphicon glyphicon-remove"></em>
                </button>
            </td>
        </tr>
    @endforeach
    <tr class="tr-height" style="font-weight: bold">
        <td>Total</td>
        <td>{{ $total_operator ?? 0 }}</td>
        <td>{{ $total_helper }}</td>
        <td>{{ $total_target }}</td>
        <td>{{ $total_hour }}</td>
        <td colspan="3"></td>
    </tr>
@else
    @foreach($lines as $line)
        @php($count++)
        <tr style="height: 35px;">
            <input type="hidden" name="line_id[]" value="{{ $line->id }}">
            <td>{{ $line->line_no }}</td>
            <td>
                <input type="number" name="operator[]" class="number-right"
                       style="width:70px;height:25px;" size="60">
            </td>
            <td>
                <input type="number" name="helper[]" class="number-right"
                       style="width:70px;height:25px;" size="60">
            </td>
            <td>
                <input type="number" name="target[]" class="number-right"
                       style="width:70px;height:25px;" size="60">
            </td>
            <td>
                <input type="number" name="wh[]" class="number-right"
                       style="width:70px;height:25px;" size="60">
            </td>
            <td>
                <input type="number" name="input_plan[]" class="number-right"
                       style="width:70px;height:25px;" size="60">
            </td>
            <td>
                <input type="text" style="width:80%; height:25px;" class=""
                       name="remarks[]">
            </td>
            <td>
                <button type="button" class="btn btn-xs btn-success duplicate-line-target">
                    <em class="glyphicon glyphicon-plus"></em></button>
                |
                <button type="button" class="btn btn-xs btn-danger del-duplicate-line-target">
                    <em class="glyphicon glyphicon-remove"></em></button>
            </td>
        </tr>
    @endforeach
@endif

@if($count > 0)
    <tr style="height: 45px">
        <td colspan="8">
            <button type="submit" style="" class="text-center btn btn-success btn-sm sewing-target-btn">Submit</button>
        </td>
    </tr>
@else
    <tr style="height: 40px">
        <td colspan="8" class="text-center text-danger">Not found</td>
    </tr>
@endif

<style>
    .tr-height > td {
        padding-left: 15px !important;
        font-size: 12px !important;
    }
</style>
