@extends('printembrdroplets::layout')
@section('title', 'Print/Embr Production Scan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Print/Embr Production Scan</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="text-center js-response-message"></div>
            <form id="printProductionForm" autocomplete="off">
              <div class="form-group" style="margin-bottom: 0px !important;">
                <div class="col-sm-8 col-sm-offset-2">
                  <input type="text" class="form-control form-control-sm" id="printProductionBarcode" placeholder="Scan barcode here" autofocus="" name="barcode" required="required">
                  <span class="print-production-challan" print-production-challan="{{ $production_challan_no ?? '' }}"> Challan no: {{ $production_challan_no }}</span>
                </div>
              </div>
              <div class="form-group text-center @if(count($bundle_info) == 0) hide @endif">
                <div class="text-center">
                  <a href="{{ url('/close-print-embr-production-challan/'.$production_challan_no) }}" class="btn btn-sm btn-danger">Close</a>
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
                  <th>Cutting No.</th>
                  <th>Bundle No</th>
                  <th>Cutt. Production</th>
                </tr>
              </thead>
              <tbody id="printProductionScanResult">
                @if(isset($bundle_info))
                  @php
                    $total = 0;
                    $count = $bundle_info->count();
                  @endphp
                  @foreach($bundle_info as $bundle)
                    @php
                      $qty = $bundle->bundle_card->quantity - ($bundle->bundle_card->total_rejection + $bundle->bundle_card->print_factory_receive_rejection + $bundle->bundle_card->production_rejection_qty) ;
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
                      <td>{{ $bundle->bundle_card->cutting_no ?? ''}}</td>
                      <td>{{ $bundle->bundle_card->bundle_no ?? '' }}</td>
                      <td>{{ $qty }}</td>
                    </tr>
                  @endforeach
                  <tr>
                    <td colspan="8" class="text-right"><b>Total :</b></td>
                    <td><b class="totalBundle">{{ $bundle_info->count() }}</b></td>
                    <td><b class="totalQty">{{ $total }}</b></td>
                  </tr>
                @else
                  <tr>
                    <td colspan="10" align="center">Not found</td>
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
    $(document).on('submit', '#printProductionForm', function (e) {
      let current = $(this);
      var message;
      var input = {};
      var production_challan_no = $('.print-production-challan').attr('print-production-challan');
      var bundleCardId = $('#printProductionBarcode').val().trim();
      input['production_challan_no'] = production_challan_no;
      input['bundle_card_id'] = bundleCardId;
      input['_token'] = $('meta[name="csrf-token"]').attr('content');

      if(production_challan_no && bundleCardId.length == 9) {
        var serial = $('#printProductionScanResult tr').length;
        var firstTr = $('#printProductionScanResult').find('tr:first');
        var lastTr = $('#printProductionScanResult').find('tr:last');
        $('.loader').html(loader);

        $.ajax({
            type: 'POST',
            data: input,
            url: '/print-embr-production-scan-post',
            success: function (response) {
              $('#printProductionBarcode').val('');
              $('.loader').empty();

              var bundle = response.bundle_info;

              if (response.status == 0) {
                $('#printProductionForm').find('.hide').removeClass('hide');

                if (response.rejection_status === 1) {
                  window.location.href = '/print-embr-factory-production-rejection?type=print&bundeId=' + bundle.id;
                }

                var quantity = bundle.quantity - bundle.total_rejection - bundle.print_factory_receive_rejection;

                var resultRows = '<tr>'+
                  '<td>'+serial+'</td>'+
                  '<td>'+bundle.buyer.name+'</td>'+
                  '<td>'+bundle.order.booking_no+'</td>'+
                  '<td>'+bundle.order.order_style_no+'</td>'+
                  '<td>'+bundle.purchase_order.po_no+'</td>'+
                  '<td>'+bundle.color.name+'</td>'+
                  '<td>'+bundle.cutting_no+'</td>'+
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
