@extends('inputdroplets::layout')
@section('title', $title ?? 'Tag/Challan')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>{{ $title ?? '' }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <div class="js-response-message text-center"></div>
          <div class="factory-area text-center" style="font-size: 1.1em;">
            @if(isset($challan_info))
            <b>{{ $title ?? '' }} No. :</b> {{ $challan_info->challan_no }}
            @if ($challan_info->line)
            |
            <b>Floor No. :</b> {{ $challan_info->line->floor->floor_no ?? '' }}
            |
            <b>Line No. :</b> {{ $challan_info->line->line_no ?? '' }}
            @endif
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
                <th>OQ</th>
                <th>Color</th>
                <th>Ct No.</th>
                <th>Lot</th>
                <th>Size</th>
                <th>Bundle No.</th>
                <th>Serial No.</th>
                <th>Quantity</th>
                <th>Sewing Scan</th>
              </tr>
            </thead>
            <tbody>
              @if($challan_info)
              @php
              $total = 0;
              @endphp
              @foreach($challan_info->archived_cutting_inventory->sortBy('bundle_card_id') as $bundle)
              @php
              $bundle_qty = $bundle->archivedBundlecard->quantity
              - $bundle->archivedBundlecard->total_rejection
              - $bundle->archivedBundlecard->print_rejection
              - $bundle->archivedBundlecard->embroidary_rejection;
              $total += $bundle_qty;
              $bundle_no = $bundle->archivedBundlecard->details->is_manual == 1 ? $bundle->archivedBundlecard->size_wise_bundle_no : ($bundle->archivedBundlecard->{getbundleCardSerial()} ?? $bundle->archivedBundlecard->size_wise_bundle_no ?? $bundle->archivedBundlecard->bundle_no ?? '')
              @endphp
              <tr class="tr-height">
                <td>{{ $loop->iteration }}</td>
                <td>{{ str_pad($bundle->archivedBundlecard->id, 9, '0', STR_PAD_LEFT) ?? '' }}</td>
                <td>{{ $bundle->archivedBundlecard->buyer->name ?? '' }}</td>
                <td>{{ $bundle->archivedBundlecard->order->style_name ?? '' }}</td>
                <td>{{ $bundle->archivedBundlecard->purchaseOrder->po_no ?? '' }}</td>
                <td>{{ $bundle->archivedBundlecard->purchaseOrder->po_quantity ?? 0 }}</td>
                <td>{{ $bundle->archivedBundlecard->color->name ?? '' }}</td>
                <td>{{ $bundle->archivedBundlecard->cutting_no ?? '' }}</td>
                <td>{{ $bundle->archivedBundlecard->lot->lot_no ?? '' }}</td>
                <td>{{ $bundle->archivedBundlecard->size->name ?? ''}}@if($bundle->archivedBundlecard->suffix)
                  ({{ $bundle->archivedBundlecard->suffix }}) @endif</td>
                <td>{{ $bundle_no }}</td>
                <td>{{ $bundle->archivedBundlecard->serial ?? '' }}</td>
                <td>{{ $bundle_qty }}</td>
                <td>{{ ($bundle->archivedBundlecard->sewing_output_date != null) ? 'Yes': 'No' }}</td>
              </tr>
              @endforeach
              <tr class="tr-height" style="font-weight: bold;">
                <td colspan="12" align="right">Total &nbsp;</td>
                <td>{{ $total }}</td>
                <td></td>
                <td></td>
              </tr>
              @else
              <tr>
                <td colspan="11" align="center">Not found</td>
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