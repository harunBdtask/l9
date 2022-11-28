@if($lines)
  @foreach($lines as $line)
  <tr class="next-inspection-schedule">
    <td>
      {{ $line->line_no }}
    </td>
    <td>
      {!! Form::select("buyer_id[]", $buyers, $line->inspectionSchedule->buyer_id ?? null, ['class' => 'form-control form-control-sm c-select inspection-schedule-buyer select2-input', 'id' => 'buyerId', 'placeholder' => 'Select a Buyer', 'required']) !!}
    </td>
    <td>
      @php
        $orders = [];
        $orderId = $line->inspectionSchedule->order_id ?? null;
        if(isset($line->inspectionSchedule->buyer_id)) {
          $buyerId = $line->inspectionSchedule->buyer_id;
          $orders = \SkylarkSoft\GoRMG\Merchandising\Models\Order::where('buyer_id', $buyerId)
            ->pluck('order_style_no', 'id')
            ->all();
        }
      @endphp
      {!! Form::select("order_id[]", $orders, $orderId ?? null, ['class' => 'form-control form-control-sm c-select inspection-schedule-style select2-input', 'id' => 'styleId', 'placeholder' => 'Select Order', 'required']) !!}
    </td>
    <td>
      {!! Form::date("output_finish_date[]", $line->inspectionSchedule->output_finish_date ?? null, ['class' => 'output-finish-date', 'id' => 'outputFinishDate',  'required']) !!}
    </td>
    <td>
      <button type="button" value="{{ $line->inspectionSchedule->id ?? '' }}" data-line-id="{{ $line->id }}" class="btn btn-sm white inspection-schedule-update-btn">
        Update
      </button>
    </td>
  </tr>
  @endforeach
@endif
