@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('printembrdroplets::layout')
@section('title', 'Order & PO Wise Print Send Receive Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Order &amp; PO Wise Print Send Receive Report || {{ date("jS F, Y") }} <span class="pull-right"><a
                    download-type="pdf" class="order-wise-print-send-receive-report-dwnld-btn"><i style="color: #DC0A0B"
                                                                                                  class="fa fa-file-pdf-o"></i></a> | <a
                    download-type="xls" class="order-wise-print-send-receive-report-dwnld-btn"><i style="color: #0F733B"
                                                                                                  class="fa fa-file-excel-o"></i></a></span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body order-wise-print">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-select-order-wise-print form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Booking No</label>
                  {!! Form::select('order_id', [], null, ['class' => 'booking-select-order-wise-print form-control form-control-sm select2-input', 'placeholder' => 'Select a Style/Order']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Style/Order No</label>
                  {!! Form::select('order_id', [], null, ['class' => 'style-select-order-wise-print form-control form-control-sm select2-input', 'placeholder' => 'Select a Style/Order', 'disabled']) !!}
                </div>
                <div class="col-sm-2">
                  <label>PO</label>
                  {!! Form::select('purchase_order_id', [], null, ['class' => 'order-select-order-wise-print form-control form-control-sm select2-input', 'placeholder' => 'Select a PO']) !!}
                </div>
              </div>
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                <tr class="style-wise-print-header">
                  <th>PO</th>
                  <th>PO Quantity</th>
                  <th>Cutting Production</th>
                  <th>Cutting WIP</th>
                  <th>Total Send</th>
                  <th>Total Recieved</th>
                  <th>Fabric Rejection</th>
                  <th>Print Rejection</th>
                  <th>Total Rejection</th>
                  <th>Print WIP/Short</th>
                </tr>
                <tr class="order-wise-print-header" style="display: none">
                  <th>Color Name</th>
                  <th>Size Name</th>
                  <th>PO Quantity</th>
                  <th>Cutting Production</th>
                  <th>Cutting WIP</th>
                  <th>Total Send</th>
                  <th>Total Recieved</th>
                  <th>Fabric Rejection</th>
                  <th>Print Rejection</th>
                  <th>Total Rejection</th>
                  <th>Print WIP/Short</th>
                </tr>
                </thead>
                <tbody class="sise-print-report">
                </tbody>
              </table>
            </div>

            <div class="loader"></div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
