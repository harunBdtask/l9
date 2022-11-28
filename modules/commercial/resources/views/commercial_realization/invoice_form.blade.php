@if($document_submission && $document_submission_invoices && count($document_submission_invoices))
  @foreach ($document_submission_invoices as $invoice)
  <tr>
    <td>
      {!! Form::hidden('commercial_realization_invoices_id[]', null) !!}
      {!! Form::hidden('document_submission_invoice_id[]', $invoice['document_submission_invoice_id']) !!}
      {!! Form::hidden('primary_contract_id[]', $invoice['primary_contract_id']) !!}
      {!! Form::hidden('export_invoice_id[]', $invoice['export_invoice_id']) !!}
      {!! Form::hidden('sales_contract_id[]', $invoice['sales_contract_id']) !!}
      {!! Form::hidden('export_lc_id[]', $invoice['export_lc_id']) !!}
      {{ $invoice['invoice_no'] }}
      <span class="text-danger document_submission_invoice_id"></span>
    </td>
    <td>
      {!! Form::hidden('invoice_date[]', $invoice['invoice_date']) !!}
      {{ $invoice['invoice_date'] ? date('d M, Y', strtotime($invoice['invoice_date'])) : null }}
      <span class="text-danger invoice_date"></span>
    </td>
    <td>
      {!! Form::hidden('net_invoice_value[]', $invoice['net_invoice_value']) !!}
      {{ $invoice['net_invoice_value'] }}
      <span class="text-danger net_invoice_value"></span>
    </td>
    <td>
      {!! Form::hidden('document_submission_date[]', $invoice['document_submission_date']) !!}
      {{ $invoice['document_submission_date'] ? date('d M, Y', strtotime($invoice['document_submission_date'])) : null }}
      <span class="text-danger document_submission_date"></span>
    </td>
    <td>
      {!! Form::hidden('submission_value[]', $invoice['submission_value']) !!}
      {{ $invoice['submission_value'] }}
      <span class="text-danger submission_value"></span>
    </td>
    <td>
      <span class="prev_realized_value_html">{{ $invoice['prev_realized_value'] }}</span>
      <span class="text-danger prev_realized_value"></span>
    </td>
    <td>
      {!! Form::number('realized_value[]', $invoice['realized_value'], ['class' => 'text-right', 'step' => '.0001']) !!}
      <br>
      <span class="text-danger realized_value"></span>
    </td>
    <td>
      {!! Form::number('short_realized_value[]', $invoice['short_realized_value'], ['class' => 'text-right', 'step' => '.0001']) !!}
      <br>
      <span class="text-danger short_realized_value"></span>
    </td>
    <td>
      {!! Form::hidden('due_realized_value[]', $invoice['due_realized_value'], ['class' => 'text-right']) !!}
      <span class="due_realized_value_html">{{ $invoice['due_realized_value'] }}</span>
      <span class="text-danger due_realized_value"></span>
    </td>
    <td>
      <button type="button" class="btn btn-sm btn-danger remove-invoice" data-id=""><i class="fa fa-times"></i></button>
    </td>
  </tr>
  @endforeach
@endif