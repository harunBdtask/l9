@extends('printembrdroplets::layout')
@section('title', 'Archived Gatepass Bundles')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Gatepass Bundles (Archived)</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <div class="js-response-message text-center"></div>
          <div class="factory-area text-center" style="font-size: 1.1em;">
            @if(isset($print_inventories))
            <strong>Challan No: {{ $print_inventories->first()->challan_no ?? '' }}</strong><br>
            @endif
          </div>
          <hr>

          <table class="reportTable">
            <thead>
              <tr>
                <th>SL</th>
                <th>Buyer</th>
                <th>Style</th>
                <th>PO</th>
                <th>Order Qty</th>
                <th>Colour</th>
                <th>Cutting No.</th>
                <th>Size</th>
                <th>Bundle No</th>
                <th>Quantity</th>
                <th>QC Pass</th>
              </tr>
            </thead>
            <tbody>
              @if($print_inventories)
              @foreach($print_inventories->sortBy('bundle_card_id') as $bundle)
              @php
                $bundle_no = $bundle->archived_bundle_card->details->is_manual == 1 ? $bundle->archived_bundle_card->size_wise_bundle_no : ($bundle->archived_bundle_card->{getbundleCardSerial()} ?? $bundle->archived_bundle_card->size_wise_bundle_no ?? $bundle->archived_bundle_card->bundle_no ?? '');
              @endphp
              <tr class="tr-height">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $bundle->archived_bundle_card->purchaseOrder->buyer->name ?? '' }}</td>
                <td>{{ $bundle->archived_bundle_card->order->style_name ?? '' }}</td>
                <td>{{ $bundle->archived_bundle_card->purchaseOrder->po_no ?? '' }}</td>
                <td>{{ $bundle->archived_bundle_card->purchaseOrder->po_quantity ?? 0 }}</td>
                <td>{{ $bundle->archived_bundle_card->color->name ?? '' }}</td>
                <td>{{ $bundle->archived_bundle_card->cutting_no ?? '' }}</td>
                <td>{{ $bundle->archived_bundle_card->size->name ?? '' }}@if($bundle->archived_bundle_card->suffix)
                  ({{ $bundle->archived_bundle_card->suffix }}) @endif</td>
                <td>{{ str_pad($bundle_no, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ str_pad($bundle->archived_bundle_card->quantity, 3, '0', STR_PAD_LEFT) }}</td>
                <td>
                  {{ str_pad($bundle->archived_bundle_card->quantity - $bundle->archived_bundle_card->total_rejection, 3, '0', STR_PAD_LEFT) }}
                </td>
              </tr>
              @endforeach
              @else
              <tr>
                <td colspan="11" align="center" class="text-center text-danger">Not found</td>
              </tr>
              @endif
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
@endsection