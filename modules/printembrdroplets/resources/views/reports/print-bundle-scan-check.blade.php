@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('printembrdroplets::layout')
@section('title', 'Bundle Card Scan Checking for Printing')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Bundle Card Scan Checking for Printing || {{ date("jS F, Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body pbundle-scan-check">
            <form>
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Buyer</label>
                    {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>PO</label>
                    {!! Form::select('purchase_order_id', [], null, ['class' => 'order-select form-control form-control-sm select2-input']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>Color</label>
                    {!! Form::select('color_id', [], null, ['class' => 'color-select form-control form-control-sm select2-input']) !!}
                  </div>
                </div>
              </div>
            </form>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                <tr>
                  <th>Serial</th>
                  <th>OP Barcode</th>
                  <th>RP Barcode</th>
                  <th>Print Barcode</th>
                  <th>Total Scanned</th>
                  <th>Total Unscanned</th>
                  <th>Total Quantity</th>
                </tr>
                </thead>
                <tbody class="print-bundle-scan-checked">
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
