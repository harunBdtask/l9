@extends('misdroplets::layout')
@section('title', 'Audit Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Audit Report || {{ date("jS F, Y") }}
            @php
            $currentPage = $reportData ? $reportData->currentPage() : 1;
            @endphp
            <span class="pull-right">
              <a href="{{ url('/audit-report-download/'.$input['buyer_id'].'/'.$input['order_id'].'/'.$input['purchase_order_id'].'/'.$input['from_date'].'/'.$input['to_date'].'/pdf'.'/'.$currentPage) }}"
                download-type="pdf" class=""><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a>
              | <a
                href="{{ url('/audit-report-download/'.$input['buyer_id'].'/'.$input['order_id'].'/'.$input['purchase_order_id'].'/'.$input['from_date'].'/'.$input['to_date'].'/xls'.'/'.$currentPage) }}"
                class=""><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body order-wise-print">
          <form action="{{ url('/audit-report') }}" method="get">
            @csrf
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, $reportParameter['buyer_id'] ?? null, ['class' => 'srch
                  mis-buyer form-control form-control-sm select2-input', 'placeholder' => 'Select Buyer']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Order/Style</label>
                  {!! Form::select('order_id', $orders ?? [], $reportParameter['order_id'] ?? null, ['class' =>
                  'mis-style form-control form-control-sm select2-input', 'placeholder' => 'Select Order']) !!}
                </div>
                <div class="col-sm-2">
                  <label>PO</label>
                  {!! Form::select('purchase_order_id', $purchase_orders ?? [], $reportParameter['purchase_order_id'] ??
                  null, ['class' => 'mis-order form-control form-control-sm select2-input', 'placeholder' => 'Select PO']) !!}
                </div>
                <div class="col-sm-2">
                  <label>From Date</label>
                  {!! Form::date('from_date', $reportParameter['from_date'] ?? null, ['class' => '
                  order-print-send-received form-control form-control-sm', 'placeholder' => 'From date']) !!}
                </div>
                <div class="col-sm-2">
                  <label>To Date</label>
                  {!! Form::date('to_date', $reportParameter['to_date'] ?? null, ['class' => ' order-print-send-received
                  form-control form-control-sm', 'placeholder' => 'To date']) !!}

                  @if($errors->has('to_date'))
                  <span class="text-danger">{{ $errors->first('to_date') }}</span>
                  @endif
                </div>
                <div class="col-sm-1">
                  <label>&nbsp;</label>
                  <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                </div>
              </div>
            </div>
          </form>
          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable"
              style="max-width: 150% !important; font-size: 11px !important; display: block; overflow-x: auto;white-space: nowrap;"
              id="fixTable">
              <thead>
                <tr>
                  <th>Buyer</th>
                  <th>Order/Style</th>
                  <th>PO</th>
                  <th>PO Qty</th>
                  <th>Shipment<br />Date</th>
                  <th>Cutting<br />Starting<br>Date</th>
                  <th>Cutting<br />Qty</th>
                  <th>Excess/Short<br />Cutting<br />Qty</th>
                  <th>Print<br />Issue</th>
                  <th>Print<br />Received</th>
                  <th>Print<br />Balance</th>
                  <th>Print<br />Party</th>
                  <th>Sewing<br />Input</th>
                  <th>Sewing<br />Output</th>
                  <th>Finishing<br />Output</th>
                  <th>Packing List<br />Qty</th>
                  <th>Inspection<br />Qty</th>
                  <th>Inspection<br />Rejection</th>
                  <th>Cutting<br />Rejection</th>
                  <th>Print/Embr.<br />Rejection</th>
                  <th>Sewing<br />Rejection</th>
                  <th>Finishing<br />Rejection</th>
                  <th>Total<br />Rejection</th>
                  <th>Shipment<br />Qty</th>
                  <th>Short<br />Qty</th>
                  <th width="10%">Remarks</th>
                </tr>
              </thead>

              @include('misdroplets::reports.audit_report_tbody')

              <tfoot>
                @if(!$print && $reportData->total() > 15)
                <tr>
                  <td colspan="26" align="center">{{ $reportData->appends(request()->except('page'))->links() }}</td>
                </tr>
                @endif
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
  @media screen and (-webkit-min-device-pixel-ratio: 0) {
    input[type=date].form-control form-control-sm {
      line-height: 1;
    }
  }
</style>
@endsection
