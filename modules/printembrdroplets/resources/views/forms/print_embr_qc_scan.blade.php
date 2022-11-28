@extends('printembrdroplets::layout')
@section('title', 'Print/Embr Qc Scan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Print/Embr. Qc Scan</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <div class="text-center js-response-message"></div>
            <form id="printEmbrQcForm" autocomplete="off">
              <div class="form-group" style="margin-bottom: 0 !important">
                <div class="col-sm-8 col-sm-offset-2">
                  <input type="text" class="form-control form-control-sm has-value" id="printEmbrQcBarcode" placeholder="Scan barcode here" autofocus="" name="barcode" required="required">
                  <span class="print-embr-qc-challan" print-embr-qc-challan="{{ $challan_no ?? '' }}"> Challan no: {{ $challan_no }}</span>
                </div>
              </div>
              <div class="form-group text-center @if(count($bundle_info) == 0) hide @endif">
                <div class="text-center">
                  <a href="{{ url('/create-print-embr-qc-tag/'.$challan_no)}}" class="btn btn-success">Delivery Tag</a>
                  <a href="{{ url('/create-print-embr-delivery-challan/'.$challan_no)}}" class="btn btn-success">Delivery Challan</a>
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
                  <th>Colour</th>
                  <th>Size</th>
                  <th>Bundle No</th>
                  <th>Quantity</th>
                </tr>
              </thead>
              <tbody id="printEmbrQcScanResult">
                @if(isset($bundle_info))
                  @php
                    $total = 0;
                    $count = $bundle_info->count()
                  @endphp
                  @foreach($bundle_info as $bundle)
                    @php
                      $qty = $bundle->bundle_card->quantity - ($bundle->bundle_card->total_rejection + $bundle->bundle_card->print_factory_receive_rejection + $bundle->bundle_card->production_rejection_qty + $bundle->bundle_card->qc_rejection_qty);
                      $total += $qty;

                    @endphp
                    <tr style="height: 19px !important">
                      <td>{{ $count-- }}</td>
                      <td>{{ $bundle->bundle_card->buyer->name ?? '' }}</td>
                      <td>{{ $bundle->bundle_card->order->booking_no ?? ''}}</td>
                      <td>{{ $bundle->bundle_card->order->order_style_no ?? ''}}</td>
                      <td>{{ $bundle->bundle_card->purchaseOrder->po_no ?? ''}}</td>
                      <td>{{ $bundle->bundle_card->color->name ?? '' }}</td>
                      <td>{{ $bundle->bundle_card->size->name ?? '' }}@if($bundle->bundle_card->suffix)({{ $bundle->bundle_card->suffix }}) @endif</td>
                      <td>{{ $bundle->bundle_card->bundle_no ?? '' }}</td>
                      <td>{{ $qty }}</td>
                    </tr>
                  @endforeach
                  <tr>
                    <td colspan="7" class="text-right"><b>Total :</b></td>
                    <td><b class="totalBundle">{{ $bundle_info->count() }}</b></td>
                    <td><b class="totalQty">{{ $total }}</b></td>
                  </tr>
                @else
                  <tr>
                    <td colspan="9" align="center">Not found<td>
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
  <script type="text/javascript">
    // print production scan
    $(document).on('submit', '#printEmbrQcForm', function (e) {
      let current = $(this);
      var message;
      var input = {};
      var challan_no = $('.print-embr-qc-challan').attr('print-embr-qc-challan');
      var bundleCardId = $('#printEmbrQcBarcode').val().trim();
      input['challan_no'] = challan_no;
      input['bundle_card_id'] = bundleCardId;
      input['_token'] = $('meta[name="csrf-token"]').attr('content');

      if(challan_no && bundleCardId.length == 9) {
        var serial = $('#printEmbrQcScanResult tr').length;
        var firstTr = $('#printEmbrQcScanResult').find('tr:first');
        var lastTr = $('#printEmbrQcScanResult').find('tr:last');
        $('.loader').html(loader);

        $.ajax({
            type: 'POST',
            data: input,
            url: '/print-embr-qc-scan-post',
            success: function (response) {
              $('#printEmbrQcBarcode').val('');
              $('.loader').empty();
              if (response.status == 0) {
                $('#printEmbrQcForm').find('.hide').removeClass('hide');
                var bundle = response.bundle_info;

                  if (response.rejection_status === 1) {
                      window.location.href = '/print-embr-factory-qc-rejection?type=print&bundeId=' + bundle.id;
                  }

                var quantity = bundle.quantity - bundle.total_rejection - bundle.print_factory_receive_rejection - bundle.production_rejection_qty;

                var resultRows = '<tr>'+
                  '<td>'+serial+'</td>'+
                  '<td>'+bundle.buyer.name+'</td>'+
                  '<td>'+bundle.order.booking_no+'</td>'+
                  '<td>'+bundle.order.order_style_no+'</td>'+
                  '<td>'+bundle.purchase_order.po_no+'</td>'+
                  '<td>'+bundle.color.name+'</td>'+
                  '<td>'+bundle.size.name+'</td>'+
                  '<td>'+bundle.bundle_no+'</td>'+
                  '<td>'+quantity+'</td>'+
                '</tr>';

                lastTr.find('.totalBundle').html(serial);
                firstTr.before(resultRows);

                var totalQty = lastTr.find('.totalQty').text();
                totalQty = totalQty ? parseInt(totalQty) : 0;
                totalQty = totalQty + quantity;
                lastTr.find('.totalQty').html(totalQty);

                if (response.rejection_status == 1) {
                  $('#main-content').find('.print-send-scan-area').hide();
                  $('#main-content').append(response.rejection_form);
                  $('#fabric_rejection').focus();
                  return false;
                }
              } else {
                $('.js-response-message').html(getMessage(response.message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
              }
            }
          });
        } else {
          $('.js-response-message').html(getMessage('Please scan valid bundle', 'danger')).fadeIn().delay(2000).fadeOut(2000);
        }
      e.preventDefault();
    });
  </script>
@endsection
