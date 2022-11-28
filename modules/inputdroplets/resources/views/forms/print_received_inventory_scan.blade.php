@extends('inputdroplets::layout')
@section('title', 'Received From Print & Embroidery')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Received From Print & Embroidery</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          @include('partials.response-message')
          <div class="text-center js-response-message"></div>
          <form id="printReceivedFormSubmit" autocomplete="off">
            <div class="row form-group">
              <div class="col-sm-8 col-sm-offset-2">
                <input type="text" class="form-control form-control-sm has-value" id="printReceivedBarcode"
                  placeholder="Scan barcode here" autofocus="" name="barcode" required="required">
                <span class="print-received-challan" print-received-challan="{{ $challan_no ?? '' }}"> Challan no:
                  {{ $challan_no ?? '' }}</span>
              </div>
              <div class="col-sm-offset-4 col-sm-8 @if(count($bundle_info) == 0) hide @endif">
                <a href="{{ url('/create-challan-tag/'.$challan_no)}}" class="btn btn-success">Create
                  Tag</a>
                <a href="{{ url('/create-challan/'.$challan_no)}}" class="btn btn-success">Create
                  Challan</a>
                <a class="btn btn-danger" href="{{ url('/') }}">Scan Completed</a>
              </div>
            </div>
          </form>
          <div class="row">
            <span class="loader"></span>
          </div>
          <div class="row table-responsive">
            <table class="reportTable">
              <thead>
                <tr>
                  <th>SL</th>
                  <th>Buyer</th>
                  <th>Style</th>
                  <th>PO</th>
                  <th>Colour</th>
                  <th>Lot</th>
                  <th>Cutting No.</th>
                  <th>Size</th>
                  <th>Bundle No.</th>
                  <th>Cutting Production</th>
                </tr>
              </thead>
              <tbody id="printReceivedScanResult">
                @if($bundle_info && count($bundle_info))
                @php $total = 0; @endphp
                @foreach($bundle_info as $bundle)
                @php
                  $productionQty = $bundle['quantity'] - $bundle['total_rejection'] - $bundle['print_rejection'] - $bundle['embroidary_rejection'];
                  $total += $productionQty; 
                @endphp
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $bundle['buyer_name'] ?? '' }}</td>
                  <td>{{ $bundle['style_name'] ?? '' }}</td>
                  <td>{{ $bundle['po_no'] ?? '' }}</td>
                  <td>{{ $bundle['color_name'] ?? '' }}</td>
                  <td>{{ $bundle['lot_no'] ?? '' }}</td>
                  <td>{{ $bundle['cutting_no'] ?? '' }}</td>
                  <td>{{ $bundle['size_name'] ?? '' }}</td>
                  <td>{{ $bundle['bundle_no'] }}</td>
                  <td>{{ $productionQty }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold">
                  <td colspan="8" class="text-right"><b>Total :</td>
                  <td><b class="totalBundle">{{ count($bundle_info) }}</b></td>
                  <td><b class="totalQty">{{ $total }}</b></td>
                </tr>
                @else
                <tr style="font-weight: bold">
                  <td colspan="8" class="text-right"><b>Total :</td>
                  <td><b class="totalBundle">0</b></td>
                  <td><b class="totalQty">0</b></td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
<script>
$(document).on('submit', '#printReceivedFormSubmit', function (e) {
  let current = $(this);
  if (current.data('requestRunning')) {
    return false;
  }

  var message;
  var input = {};
  var challan_no = $('.print-received-challan').attr('print-received-challan');
  var bundleCardId = $('#printReceivedBarcode').val().trim();
  input['challan_no'] = challan_no;
  input['bundle_card_id'] = bundleCardId;
  input['_token'] = $('meta[name="csrf-token"]').attr('content');

  var serial = $('#printReceivedScanResult tr').length;
  if(challan_no && bundleCardId.length == 9) {
    current.data('requestRunning', true);
    var lastTr = $('#printReceivedScanResult').find('tr:last');
    showLoader();
    $.ajax({
      type: 'POST',
      data: input,
      url: '/bundle-received-from-print-post',
      success: function (response) {
        $('#printReceivedBarcode').val('');
        hideLoader();
        if (response.status == 0) {
          var bundle = response.bundle_info;

          $('#printReceivedFormSubmit').find('.hide').removeClass('hide');
          if(response.rejection_status == 1) {
            window.location.href = '/print-rejection?bundeId='+bundle.id;
          }
          var quantity = bundle.quantity - bundle.total_rejection;
          var resultRows = '<tr>'+
              '<td>'+serial+'</td>'+
              '<td>'+bundle.buyer_name+'</td>'+
              '<td>'+bundle.style_name+'</td>'+
              '<td>'+bundle.po_no+'</td>'+
              '<td>'+bundle.color_name+'</td>'+
              '<td>'+bundle.lot_no+'</td>'+
              '<td>'+bundle.cutting_no+'</td>'+
              '<td>'+bundle.size_name+'</td>'+
              '<td>'+bundle.bundle_no+'</td>'+
              '<td>'+quantity+'</td>'+
              '</tr>';

          lastTr.find('.totalBundle').html(serial);
          lastTr.before(resultRows);

          var totalQty = lastTr.find('.totalQty').text();
          totalQty = totalQty ? parseInt(totalQty) : 0;
          totalQty = totalQty + quantity;
          lastTr.find('.totalQty').html(totalQty);

        } else {
          $('.js-response-message').html(getMessage(response.message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
        }
      },
      error: function (response) {
        hideLoader();
        console.log(response)
      },
      complete: function () {
        current.data('requestRunning', false);
      }
    });
  } else {
    $('.js-response-message').html(getMessage('Please scan valid bundle', 'danger')).fadeIn().delay(2000).fadeOut(2000);
  }
  e.preventDefault();
});

</script>
@endsection
