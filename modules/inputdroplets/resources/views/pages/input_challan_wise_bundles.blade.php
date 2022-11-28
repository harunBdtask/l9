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
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @if($challan_info)
              @php
              $total = 0;
              $sl = 0;
              @endphp
              @foreach($challan_info->cutting_inventory->sortBy('bundle_card_id')->groupBy('bundlecard.size_id') as $bundleBySize)
                @php
                  $sizeWiseBundles = $bundleBySize->count();
                  $sizeWiseQty = 0;
                  $size = $bundleBySize->first()->bundlecard->size->name;
                @endphp
                @foreach($bundleBySize->sortBy('bundle_card_id')->groupBy('bundle_card_id') as $bundleById)
                @php
                $bundle = $bundleById->first();
                $bundle_qty = $bundle->bundlecard->quantity
                - $bundle->bundlecard->total_rejection
                - $bundle->bundlecard->print_rejection
                - $bundle->bundlecard->embroidary_rejection;
                $total += $bundle_qty;
                $sizeWiseQty += $bundle_qty;
                $bundle_no = $bundle->bundlecard->details->is_manual == 1 ? $bundle->bundlecard->size_wise_bundle_no : ($bundle->bundlecard->{getbundleCardSerial()} ?? $bundle->bundlecard->size_wise_bundle_no ?? $bundle->bundlecard->bundle_no ?? '')
                @endphp
                <tr class="tr-height">
                  <td>{{ ++$sl }}</td>
                  <td>{{ str_pad($bundle->bundlecard->id, 9, '0', STR_PAD_LEFT) ?? '' }}</td>
                  <td>{{ $bundle->bundlecard->buyer->name ?? '' }}</td>
                  <td>{{ $bundle->bundlecard->order->style_name ?? '' }}</td>
                  <td>{{ $bundle->bundlecard->purchaseOrder->po_no ?? '' }}</td>
                  <td>{{ $bundle->bundlecard->purchaseOrder->po_quantity ?? 0 }}</td>
                  <td>{{ $bundle->bundlecard->color->name ?? '' }}</td>
                  <td>{{ $bundle->bundlecard->cutting_no ?? '' }}</td>
                  <td>{{ $bundle->bundlecard->lot->lot_no ?? '' }}</td>
                  <td>{{ $bundle->bundlecard->size->name ?? ''}}@if($bundle->bundlecard->suffix)
                    ({{ $bundle->bundlecard->suffix }}) @endif</td>
                  <td>{{ $bundle_no }}</td>
                  <td>{{ $bundle->bundlecard->serial ?? '' }}</td>
                  <td>{{ $bundle_qty }}</td>
                  <td>{{ ($bundle->bundlecard->sewing_output_date != null) ? 'Yes': 'No' }}</td>
                  <td>
                    @if(getRole() == 'super-admin' || getRole() == 'admin' || session()->has("permission_of_challan_list_delete"))
                      <button type="button" class="btn btn-danger btn-xs input-bundle-btn" value="{{ $bundle->bundle_card_id }}">
                        <i class="fa fa-times"></i>
                      </button>
                    @endif
                  </td>
                </tr>
                @endforeach
                <tr class="tr-height" style="font-weight: bold;">
                  <td colspan="12" align="right">Size Total = {{ $size }}</td>
                  <td>{{ $sizeWiseQty }}</td>
                  <td></td>
                  <td></td>
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
                <td colspan="11" align="center">Not found
                </td>
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
<script>
  $(document).on('click', '.input-bundle-btn', function () {
      if (confirm('Are you sure to delete this') == true) {
        var bundleCardId = $(this).val();
        var current = $(this);
        if (bundleCardId) {
          showLoader();
          $.ajax({
            type: 'DELETE',
            url: '/delete-input-bundle/' + bundleCardId,
            success: function (response) {
              hideLoader();
              if (response == 200) {
                $('.js-response-message').html(getMessage(D_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);
                current.parent('td').parent('tr').remove();
              } else {
                var message = response.message ? response.message : D_FAIL;
                $('.js-response-message').html(getMessage(message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
              }
            },
            error: function (response) {
              hideLoader();
              console.log(response)
            },
          });
        }
      }
    });
</script>
@endsection