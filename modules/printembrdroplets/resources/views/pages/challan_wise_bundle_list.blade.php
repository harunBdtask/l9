@extends('printembrdroplets::layout')
@section('title', 'Gatepass Bundles')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Gatepass Bundles</h2>
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
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @if($print_inventories)
                @php
                  $total_qty = 0;
                  $total_qc_qty = 0;
                  $sl =0;
                @endphp
                @foreach($print_inventories->sortBy('bundle_card_id')->groupBy('bundle_card.size_id') as $bundleBySize)
                  @php
                    $size_total_qty = 0;
                    $size_total_qc_qty = 0;
                    $size = $bundleBySize->first()->bundle_card->size->name;
                  @endphp
                  @foreach($bundleBySize as $bundle)
                  @php
                    $quantity = $bundle->bundle_card->quantity - $bundle->bundle_card->total_rejection;
                    $qc_pass_quantity = $bundle->bundle_card->quantity - $bundle->bundle_card->total_rejection;
                    $total_qty += $quantity;
                    $total_qc_qty += $qc_pass_quantity;
                    $size_total_qty += $quantity;
                    $size_total_qc_qty += $qc_pass_quantity;
                    $bundle_no = $bundle->bundle_card->details->is_manual == 1 ? $bundle->bundle_card->size_wise_bundle_no : ($bundle->bundle_card->{getbundleCardSerial()} ?? $bundle->bundle_card->size_wise_bundle_no ?? $bundle->bundle_card->bundle_no ?? '');
                  @endphp
                  <tr class="tr-height">
                    <td>{{ ++$sl }}</td>
                    <td>{{ str_pad($bundle->bundle_card->id, 9, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $bundle->bundle_card->purchaseOrder->buyer->name ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->order->style_name ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->purchaseOrder->po_no ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->purchaseOrder->po_quantity ?? 0 }}</td>
                    <td>{{ $bundle->bundle_card->color->name ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->cutting_no ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->size->name ?? '' }}@if($bundle->bundle_card->suffix)
                      ({{ $bundle->bundle_card->suffix }}) @endif</td>
                    <td>{{ str_pad($bundle_no, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $quantity }}</td>
                    <td>
                      {{ $qc_pass_quantity }}
                    </td>
                    <td>
                      @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_gatepass_list_delete'))
                        <button type="button" style="" class="btn btn-xs btn-danger getpass-bundle-dtn"
                          value="{{ $bundle->id}}">
                          <i class="fa fa-times"></i>
                        </button>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                  <tr>
                    <th colspan="10">Size Total = {{ $size }}</th>
                    <th>{{ $size_total_qty }}</th>
                    <th>{{ $size_total_qc_qty }}</th>
                    <th>&nbsp;</th>
                  </tr>
                @endforeach
                <tr>
                  <th colspan="10">Grand Total</th>
                  <th>{{ $total_qty }}</th>
                  <th>{{ $total_qc_qty }}</th>
                  <th>&nbsp;</th>
                </tr>
              @else
              <tr>
                <td colspan="12" align="center" class="text-center text-danger">Not found
                <td>
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
  // print gate challan bundle delete
    $(document).on('click', '.getpass-bundle-dtn', function () {
      if (confirm('Are you sure to delete this') == true) {
        var id = $(this).val();
        var current = $(this);
        if (id) {
          $.ajax({
            type: 'DELETE',
            url: '/delete-print-invntory-bundle/' + id,
            success: function (response) {
              let status = response.status;
              if (status == 200) {
                $('.js-response-message').html(getMessage(D_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);
                current.parent('td').parent('tr').remove();
              } else {
                var message = response.message ? response.message : D_FAIL;
                $('.js-response-message').html(getMessage(message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
              }
            }
          });
        }
      }
    });
</script>
@endsection