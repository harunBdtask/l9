@extends('printembrdroplets::layout')
@section('title', 'Gatepass Bundles Deleted')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Gatepass Deleted Bundles</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <div class="js-response-message text-center"></div>
          <div class="factory-area text-center" style="font-size: 1.1em;">
            @if(isset($challan_no))
            <strong>Challan No: {{ $challan_no ?? '' }}</strong><br>
            @endif
          </div>
          <hr>

          <table class="reportTable">
            <thead>
              <tr>
                <th>SL</th>
                <th>Barcode</th>
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
                <th>Deleted At</th>
                <th>Deleted By</th>
              </tr>
            </thead>
            <tbody>
              @if($print_inventories)
                @php
                  $total_qty = 0;
                  $total_qc_qty = 0;
                  $sl =0;
                @endphp
                @foreach($print_inventories->sortBy('bundle_card_id')->groupBy('size_id') as $bundleBySize)
                  @php
                    $size_total_qty = 0;
                    $size_total_qc_qty = 0;
                    $size = $bundleBySize->first()->size_name;
                  @endphp
                  @foreach($bundleBySize as $bundle)
                  @php
                    $quantity = $bundle->quantity - $bundle->total_rejection;
                    $qc_pass_quantity = $bundle->quantity - $bundle->total_rejection;
                    $total_qty += $quantity;
                    $total_qc_qty += $qc_pass_quantity;
                    $size_total_qty += $quantity;
                    $size_total_qc_qty += $qc_pass_quantity;
                  @endphp
                  <tr class="tr-height">
                    <td>{{ ++$sl }}</td>
                    <td>{{ str_pad($bundle->id, 9, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $bundle->buyer_name ?? '' }}</td>
                    <td>{{ $bundle->style_name ?? '' }}</td>
                    <td>{{ $bundle->po_no ?? '' }}</td>
                    <td>{{ $bundle->po_quantity ?? 0 }}</td>
                    <td>{{ $bundle->color_name ?? '' }}</td>
                    <td>{{ $bundle->cutting_no ?? '' }}</td>
                    <td>{{ $bundle->size_name ?? '' }}@if($bundle->suffix)
                      ({{ $bundle->suffix }}) @endif</td>
                    <td>{{ str_pad(($bundle->is_manual == 1 ? $bundle->size_wise_bundle_no : $bundle->bundle_no), 2, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $quantity }}</td>
                    <td>
                      {{ $qc_pass_quantity }}
                    </td>
                    <td>{{ $bundle->deleted_at ? date('d M, Y h:i a', strtotime($bundle->deleted_at)) : '-' }}</td>
                    <td>{{ $bundle->deleted_by }}</td>
                  </tr>
                  @endforeach
                  <tr>
                    <th colspan="10">Size Total = {{ $size }}</th>
                    <th>{{ $size_total_qty }}</th>
                    <th>{{ $size_total_qc_qty }}</th>
                    <th colspan="2">&nbsp;</th>
                  </tr>
                @endforeach
                <tr>
                  <th colspan="10">Grand Total</th>
                  <th>{{ $total_qty }}</th>
                  <th>{{ $total_qc_qty }}</th>
                  <th colspan="2">&nbsp;</th>
                </tr>
              @else
              <tr>
                <td colspan="14" align="center" class="text-center text-danger">Not found</td>
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