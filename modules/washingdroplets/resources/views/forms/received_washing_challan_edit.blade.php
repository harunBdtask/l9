@extends('washingdroplets::layout')
@section('title', 'Received Bundle Challan Edit')

@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Received From Wash || {{ date("jS, F Y") }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body color-finishing">
          <div class="box-body">
            <div class="js-response-message text-center"></div>
            <div class="validation-message alert alert-danger text-center" style="display:none"></div>

            <form id="washingReceivedEditForm">
              @php
              $washingReceivedChallanFirst = $washingReceivedChallan->first();
              @endphp
              @csrf
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Buyer</label>
                    {!! Form::select('buyer_id', $buyers, $washingReceivedChallanFirst->buyer_id ?? null, ['class' =>
                    'washing-received-buyer-edit form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer',
                    'disabled']) !!}
                    {!! Form::hidden('buyer_id', $washingReceivedChallanFirst->buyer_id ?? null) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>Order/Style</label>
                    {!! Form::select('order_id', $orders, $washingReceivedChallanFirst->order_id ?? null, ['class' =>
                    'washing-received-our-ref-edit form-control form-control-sm select2-input', 'disabled']) !!}
                    {!! Form::hidden('order_id', $washingReceivedChallanFirst->order_id ?? null) !!}
                  </div>
                </div>
              </div>

              <div class="box-divider m-a-0"></div>

              <br />
              <b>Challan No : </b>
              <input type="text" name="challan_no" placeholder="Enter Challan No"
                value="{{ $washingReceivedChallanFirst->challan_no ?? '' }}">

              <input type="hidden" name="new_challan_no" placeholder="Enter Challan No"
                value="{{ $washingReceivedChallanFirst->challan_no ?? '' }}">

              <div class="col-sm-12 uniqueColorList" style=" padding-top: 25px; padding-bottom: 30px">
                @if($washingReceivedChallan)
                @foreach($washingReceivedChallan->groupBy('color_id') as $key => $washingChallan)
                <div class="col-sm-2">{{ $washingChallan->first()->color->name ?? '' }}:<br />
                  <input type="number" class="" name="color_wise_qty[{{ $key }}]"
                    value="{{ $washingChallan->sum('received_qty') + $washingChallan->sum('rejection_qty') }}">
                </div>
                @endforeach
                @endif
              </div>

              <table class="reportTable">
                <thead>
                  <tr>
                    <th>PO</th>
                    <th>Color</th>
                    <th>Order Qty</th>
                    <th>Total Sent</th>
                    <th>Total Received</th>
                    <th>Balance</th>
                    <th>Received</th>
                    <th>Short/Rejection Qty</th>
                    <th width="20%">Reason</th>
                  </tr>
                </thead>
                <tbody class="poColorList">
                  @if($washingReceivedChallan)
                  @foreach($washingReceivedChallan as $washingChallan)
                  @php
                  $colorWiseTotal =
                  \SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport::getColorWiseTotal($washingChallan->purchase_order_id,
                  $washingChallan->color_id);
                  @endphp
                  <tr style="height:40px !important">
                    <td>
                      {{ $washingChallan->purchaseOrder->po_no ?? '' }}
                      <input type="hidden" class="" name="id[]" value="{{ $washingChallan->id }}">
                    </td>
                    <td>
                      {{ $washingChallan->color->name ?? '' }}
                    </td>
                    <td>{{ $washingChallan->purchaseOrder->po_quantity ?? '' }}</td>
                    <td>{{ $colorWiseTotal->total_washing_sent ?? '0'  }}</td>
                    <td>{{ $colorWiseTotal->total_washing_received ?? '0' }}</td>
                    <td>
                      {{ ($colorWiseTotal->total_washing_received ?? 0) - ($colorWiseTotal->total_washing_sent  ?? 0) }}
                    </td>
                    <td>
                      <input type="hidden" name="color_id[]" value="{{ $washingChallan->color_id }}">
                      <input type="number" class="" name="received_qty[]" value="{{ $washingChallan->received_qty }}">
                    </td>
                    <td>
                      <input type="number" class="" name="rejection_qty[]" value="{{ $washingChallan->rejection_qty }}">
                    </td>
                    <td><input type="text" class="" name="reasons[]" value="{{ $washingChallan->reasons }}"></td>
                  <tr>
                    @endforeach
                    @endif
                </tbody>
              </table>

              <div class="form-group m-t-md">
                <div class="col-sm-4 col-sm-offset-4 text-center">
                  <button type="button" value="{{ $washingReceivedChallanFirst->order_id ?? '' }}"
                    class="btn white washing-rcv-edit-btn">Submit</button>
                  <a href="{{ url('/') }}" class="btn white">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('protracker/washing.js') }}"></script>
@endsection
