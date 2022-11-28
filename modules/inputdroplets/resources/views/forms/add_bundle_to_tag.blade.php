@extends('inputdroplets::layout')
@section('title', 'Add Bundle To Tag')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Add Bundle To Tag</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          @include('partials.response-message')
          @php
          $challan_no = $tagBundles->challan_no ?? '';
          $totalBundle = $tagBundles->cutting_inventory->count();
          @endphp
          <div class="text-center js-response-message"></div>
          <form id="tagFormSubmit" accept-charset="UTF-8">
            <div class="row form-group">
              <div class="col-sm-8 col-sm-offset-2">
                <input type="text" class="form-control form-control-sm" id="tagBarcode" placeholder="Scan barcode here" autofocus=""
                  name="barcode" required="required">
                <span class="tag-challan" tag-challan-no="{{ $challan_no }}"> Challan no: {{ $challan_no ?? '' }}</span>
              </div>

              <div class="col-sm-offset-4 col-sm-4 @if($totalBundle == 0) hide @endif">
                <a class="btn btn-success" href="{{ url('/create-challan-for-sewing/'.$tagBundles->id) }}">Create
                  Challan</a>
                <a class="text-center btn btn-danger" href="{{ url('/') }}">Scan Completed</a>
              </div>
            </div>
            <div class="row">
              <span class="loader"></span>
            </div>
          </form>

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
            <tbody id="solidTagScanResult">
              @if($tagBundles)
              @php $total = 0; @endphp
              @foreach($tagBundles->cutting_inventory as $cuttingInv)
              @php
              $bundleQty = $cuttingInv->bundlecard->quantity
              - $cuttingInv->bundlecard->total_rejection
              - $cuttingInv->bundlecard->print_rejection
              - $cuttingInv->bundlecard->embroidary_rejection;
              $total += $bundleQty;
              $bundle_no = $cuttingInv->bundlecard->details->is_manual == 1 ? $cuttingInv->bundlecard->size_wise_bundle_no : ($cuttingInv->bundlecard->{getbundleCardSerial()} ?? $cuttingInv->bundlecard->size_wise_bundle_no ?? $cuttingInv->bundlecard->bundle_no ?? '')
              @endphp
              <tr style="height: 19px !important">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $cuttingInv->bundlecard->buyer->name ?? '' }}</td>
                <td>{{ $cuttingInv->bundlecard->order->style_name ?? '' }}</td>
                <td>{{ $cuttingInv->bundlecard->purchaseOrder->po_no ?? '' }}</td>
                <td>{{ $cuttingInv->bundlecard->color->name ?? '' }}</td>
                <td>{{ $cuttingInv->bundlecard->lot->lot_no ?? '' }}</td>
                <td>{{ $cuttingInv->bundlecard->cutting_no ?? '' }}</td>
                <td>{{ $cuttingInv->bundlecard->size->name ?? '' }}@if($cuttingInv->bundlecard->suffix)
                  ({{ $cuttingInv->bundlecard->suffix }}) @endif</td>
                <td>{{ $bundle_no ?? '' }}</td>
                <td>{{ $bundleQty }}</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="8" class="text-right"><b>Total :</b></td>
                <td><b class="totalBundle">{{ $totalBundle }}</b></td>
                <td><b class="totalQty">{{ $total }}</b></td>
              </tr>
              @else
              <tr>
                <td colspan="10">Not found</td>
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
<script type="text/javascript">
  $(document).on('submit', '#tagFormSubmit', function (e) {
      let current = $(this);
      if (current.data('requestRunning')) {
        return false;
      }
      var message;
      var input = {};
      var challan_no = $('.tag-challan').attr('tag-challan-no');
      var bundleCardId = $('#tagBarcode').val().trim();
      input['challan_no'] = challan_no;
      input['bundle_card_id'] = bundleCardId;
      input['_token'] = $('meta[name="csrf-token"]').attr('content');

      var serial = $('#solidTagScanResult tr').length;
      if (challan_no && bundleCardId.length == 9) {
        current.data('requestRunning', true);
        var lastTr = $('#solidTagScanResult').find('tr:last');
        showLoader();
        $.ajax({
          type: 'POST',
          data: input,
          url: '/add-bundle-tag-post',
          success: function (response) {
            $('#tagBarcode').val('');
            hideLoader();
            if (response.status == 0) {
              var bundle = response.bundle_info;

              $('#tagFormSubmit').find('.hide').removeClass('hide');
              if (response.rejection_status == 1) {
                if (response.challan_type == 0) {
                  window.location.href = '/cutting-rejection?type=tag&bundeId=' + bundle.id;
                } else {
                  window.location.href = '/print-rejection?type=tag&bundeId=' + bundle.id;
                }
                return false;
              }
              var quantity = bundle.quantity
                  - bundle.total_rejection
                  - bundle.print_rejection
                  - bundle.embroidary_rejection;
              var resultRows = '<tr>' +
                  '<td>' + serial + '</td>' +
                  '<td>' + bundle.buyer_name + '</td>' +
                  '<td>' + bundle.style_name + '</td>' +
                  '<td>' + bundle.po_no + '</td>' +
                  '<td>' + bundle.color_name + '</td>' +
                  '<td>' + bundle.lot_no + '</td>' +
                  '<td>' + bundle.cutting_no + '</td>' +
                  '<td>' + bundle.size_name + '</td>' +
                  '<td>' + bundle.bundle_no + '</td>' +
                  '<td>' + quantity + '</td>' +
                  '</tr>';

              lastTr.find('.totalBundle').html(serial);
              var totalQty = lastTr.find('.totalQty').text();
              totalQty = totalQty ? parseInt(totalQty) : 0;
              totalQty = totalQty + quantity;
              lastTr.find('.totalQty').html(totalQty);
              lastTr.before(resultRows);

            } else {
              $('.js-response-message').html(getMessage(response.message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
            }
          },
          error: function (response) {
            hideLoader();
            console.log(response);
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
