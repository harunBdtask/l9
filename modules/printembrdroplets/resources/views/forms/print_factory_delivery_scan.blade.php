@extends('printembrdroplets::layout')
@section('title', 'Print Factory Delivery Scan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Print Factory Delivery Scan</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <div class="text-center js-response-message"></div>
            <form id="printFactoryDeliveryForm" autocomplete="off">
              <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                  <input type="text" class="form-control form-control-sm has-value" id="barcode" placeholder="Scan barcode here"
                         autofocus="" name="barcode" required="required">
                  <span class="print-factory-challan"
                        print-send-challan="{{ $challan_no ?? '' }}"> Challan no: {{ $challan_no }}</span>
                </div>
                <div class="form-group text-center m-t-md @if(count($bundle_info) == 0) hide @endif">
                  <div class="text-center">
                    <a href="{{ url('/print-factory-delevery-challan/'.$challan_no)}}" class="btn btn-success">Create
                      Delivery Challan</a>
                  </div>
                </div>
              </div>
              <span class="loader"></span>
            </form>

            <table class="reportTable">
              <thead>
              <tr>
                <th>SL</th>
                <th>Buyer</th>
                <th>Booking No</th>
                <th>Style/Order No</th>
                <th>PO</th>
                <th>Color</th>
                <th>Lot</th>
                <th>Size</th>
                <th>Cutting No.</th>
                <th>Bundle No</th>
                <th>Cutt. Production</th>
              </tr>
              </thead>
              <tbody id="printSendScanResult">
              @if(isset($bundle_info))
                @php $total = 0; @endphp
                @foreach($bundle_info as $bundle)
                    <?php
                    $bundleQuantity = $bundle->bundle_card->printFactoryDeliveryQuantity();
                    $total += $bundleQuantity;
                    ?>

                    <tr style="height: 19px !important">
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $bundle->bundle_card->buyer->name ?? '' }}</td>
                      <td>{{ $bundle->bundle_card->order->booking_no ?? ''}}</td>
                      <td>{{ $bundle->bundle_card->order->order_style_no ?? ''}}</td>
                      <td>{{ $bundle->bundle_card->purchaseOrder->po_no ?? ''}}</td>
                      <td>{{ $bundle->bundle_card->color->name ?? '' }}</td>
                      <td>{{ $bundle->bundle_card->lot->lot_no ?? '' }}</td>
                      <td>{{ $bundle->bundle_card->size->name ?? '' }}@if($bundle->bundle_card->suffix)
                          ({{ $bundle->bundle_card->suffix }}) @endif</td>
                      <td>{{ $bundle->bundle_card->cutting_no ?? ''}}</td>
                      <td>{{ $bundle->bundle_card->bundle_no ?? '' }}</td>
                      <td>{{ $bundleQuantity }}</td>
                    </tr>
                @endforeach
                <tr>
                  <td colspan="9" class="text-right"><b>Total :</b></td>
                  <td><b class="totalBundle">{{ count($bundle_info) }}</b></td>
                  <td><b class="totalQty">{{ $total }}</b></td>
                </tr>
              @else
                <tr>
                  <td colspan="11" align="center">Not found
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

@push('script-head')
  <script>
    $(document).on('submit', '#printFactoryDeliveryForm', function (e) {
      e.preventDefault();

      let current = $(this);

      if (current.data('requestRunning')) {
        return false;
      }
      current.data('requestRunning', true);

      let message;
      const input = {};
      const challan_no = $('.print-factory-challan').attr('print-send-challan');
      const bundleCardId = $('#barcode').val().trim();
      input['challan_no'] = challan_no;
      input['bundle_card_id'] = bundleCardId;
      input['_token'] = $('meta[name="csrf-token"]').attr('content');

      const serial = $('#printSendScanResult tr').length;
      if (challan_no && bundleCardId.length === 9) {
        const lastTr = $('#printSendScanResult').find('tr:last');

        $('.loader').html(loader);

        $.ajax({
          type: 'POST',
          data: input,
          url: '/print-factory-delivery-scan-post',
          success: function (response) {
            $('#barcode').val('');
            $('.loader').empty();
            if (response.status === 0) {

              const bundle = response.bundle_info;

              $('#printFactoryDeliveryForm').find('.hide').removeClass('hide');

              if (response.rejection_status === 1) {
                window.location.href = '/print-factory-delivery-rejection?type=print&bundeId=' + bundle.id;
              }

              const bundleQty = bundle.quantity;
              const resultRows = '<tr>' +
                  '<td>' + serial + '</td>' +
                  '<td>' + bundle.buyer.name + '</td>' +
                  '<td>' + bundle.order.booking_no + '</td>' +
                  '<td>' + bundle.order.order_style_no + '</td>' +
                  '<td>' + bundle.purchase_order.po_no + '</td>' +
                  '<td>' + bundle.color.name + '</td>' +
                  '<td>' + bundle.lot.lot_no + '</td>' +
                  '<td>' + bundle.size.name + '</td>' +
                  '<td>' + bundle.cutting_no + '</td>' +
                  '<td>' + bundle.bundle_no + '</td>' +
                  '<td>' + bundleQty + '</td>' +
                  '</tr>';

              lastTr.find('.totalBundle').html(serial);
              lastTr.before(resultRows);

              let totalQty = lastTr.find('.totalQty').text();
              totalQty = totalQty ? parseInt(totalQty) : 0;
              totalQty = totalQty + bundleQty;
              lastTr.find('.totalQty').html(totalQty);

            } else {
              $('.js-response-message').html(getMessage(response.message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
            }
          },
          complete: function () {
            current.data('requestRunning', false);
          }
        });
      } else {
        $('.js-response-message').html(getMessage('Please scan valid bundle', 'danger')).fadeIn().delay(2000).fadeOut(2000);
      }
    });
  </script>
@endpush
