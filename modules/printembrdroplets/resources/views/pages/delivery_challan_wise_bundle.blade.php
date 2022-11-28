@extends('printembrdroplets::layout')
@section('title', 'Print Factory Delivery Bundles')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Print Factory Delivery Bundles</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="js-response-message text-center"></div>
            <div class="factory-area text-center" style="font-size: 1.1em;">
              @if(isset($inventories))
                <strong>Challan No: {{ $inventories->first()->challan_no ?? '' }}</strong><br>
              @endif
            </div>
            <hr>

            <table class="reportTable">
              <thead>
              <tr>
                <th>SL</th>
                <th>Buyer</th>
                <th>Booking No</th>
                <th>Style/Order No</th>
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
              @if($inventories)
                @foreach($inventories->sortBy('bundle_card_id') as $bundle)
                  <tr class="tr-height">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bundle->bundle_card->purchaseOrder->buyer->name ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->order->booking_no ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->order->order_style_no ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->purchaseOrder->po_no ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->purchaseOrder->po_quantity ?? 0 }}</td>
                    <td>{{ $bundle->bundle_card->color->name ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->cutting_no ?? '' }}</td>
                    <td>{{ $bundle->bundle_card->size->name ?? '' }}@if($bundle->bundle_card->suffix)
                        ({{ $bundle->bundle_card->suffix }}) @endif</td>
                    <td>{{ str_pad($bundle->bundle_card->bundle_no, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ str_pad($bundle->bundle_card->quantity, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>
                      {{ str_pad($bundle->bundle_card->quantity - ($bundle->bundle_card->total_rejection +  $bundle->bundle_card->print_factory_receive_rejection), 3, '0', STR_PAD_LEFT)
                  }}
                    </td>
                    <td>
                      <button type="button" style="" class="btn btn-xs btn-danger getpass-bundle-dtn"
                              value="{{ $bundle->id}}">
                        <i class="fa fa-times"></i>
                      </button>
                    </td>
                  </tr>
                @endforeach
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
  <script>
    // print gate challan bundle delete
    $(document).on('click', '.getpass-bundle-dtn', function () {
      if (confirm('Are you sure to delete this') === true) {
        const id = $(this).val();
        const current = $(this);
        if (id) {
          $.ajax({
            type: 'DELETE',
            url: '/delete-print-delivery-invntory-bundle/' + id,
            success: function (response) {
              if (response == 200) {
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