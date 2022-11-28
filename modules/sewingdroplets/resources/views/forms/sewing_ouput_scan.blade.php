@extends('sewingdroplets::layout')
@section('title', 'Bundle Received From Sewing Line Output')
@section('styles')
<style>
  #loader {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(226, 226, 226, 0.75) no-repeat center center;
    width: 100%;
    z-index: 1000;
  }

  .spin-loader {
    position: relative;
    top: 46%;
    left: 5%;
  }
</style>
@endsection
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2 class="scan-section">Bundle Received From Sewing Line Output || {{ date("jS F, Y") }}</h2>
          <h2 class="rejection-section hide">Sewing Rejection</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          @include('partials.response-message')
          <div class="text-center js-response-message"></div>
          <div class="scan-section">
            <form id="sewingFormSubmit" autocomplete="off">
              <div class="row form-group">
                <div class="col-sm-8 col-sm-offset-2">
                  <input type="text" class="form-control form-control-sm has-value" id="sewingBarcode" placeholder="Scan barcode here"
                    autofocus="" name="barcode" required="required">
                  <span class="sewing-challan-no" sewing-challan-no="{{ $output_challan_no ?? '' }}">
                    Challan no: {{ $output_challan_no ?? '' }}
                  </span>
                </div>
                <div class="text-center col-sm-offset-4 col-sm-4 @if(count($output_bundles) == 0) hide @endif">
                  <a href="{{ url('/sewing-close-challan/'.$output_challan_no)}}" class="btn btn-success">Close
                    Challan</a>
                </div>
              </div>
            </form>

            <table class="reportTable">
              <thead>
                <tr>
                  <th>Sl.</th>
                  <th>Line No</th>
                  <th>Buyer</th>
                  <th>Style</th>
                  <th>PO</th>
                  <th>Colour</th>
                  <th>Size</th>
                  <th>Bundle No</th>
                  <th>Sewing Output</th>
                </tr>
              </thead>
              <tbody id="sewingScanResult">
                @if($output_bundles && count($output_bundles))
                @php $total = 0; @endphp
                @foreach($output_bundles as $bundle)
                @php
                $bundleQty = $bundle['sewing_qty'];
                $line_no = $bundle['line_no'];
                $buyer = $bundle['buyer'];
                $style_name = $bundle['style_name'];
                $po_no = $bundle['po_no'];
                $color = $bundle['color'];
                $size = $bundle['size'];
                $bundle_no = $bundle['bundle_no'];
                $total += $bundleQty;
                @endphp
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $line_no ?? '' }}</td>
                  <td>{{ $buyer ?? '' }}</td>
                  <td>{{ $style_name ?? '' }}</td>
                  <td>{{ $po_no ?? '' }}</td>
                  <td>{{ $color ?? ''}}</td>
                  <td>{{ $size ?? '' }}</td>
                  <td>{{ $bundle_no ?? '' }}</td>
                  <td>{{ $bundleQty }}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="7" class="text-right"><b>Total :</b></td>
                  <td><b class="totalBundle">{{ count($output_bundles) }}</b></td>
                  <td><b class="totalQty">{{ $total }}</b></td>
                </tr>
                @else
                <tr>
                  <td colspan="7" class="text-right"><b>Total :</b></td>
                  <td><b class="totalBundle">0</b></td>
                  <td><b class="totalQty">0</b></td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
          <div class="rejection-section hide">
            <form method="POST" action="{{ url('/sewing-rejection-post')}}" id="sewing-rejection-form">
              @csrf
              <input type="hidden" name="id" value="">

              <div class="row form-group">
                <div class="col-sm-6 col-sm-offset-3">
                  <label>Sewing Rejection</label>
                  <input type="number" class="form-control form-control-sm has-value" id="sewing_rejection"
                    placeholder="Please enter sewing rejection. eg: only for numeric value 1,2.." autofocus=""
                    name="sewing_rejection" required="required">
                  <span class="text-danger sewing_rejection_error"></span>
                </div>
              </div>
              <div class="row form-group m-t-md">
                <div class="col-sm-offset-3 col-sm-6 text-center">
                  <button name="submit" class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="loader">
    <div class="text-center spin-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
<script>
  $(function () {
      let scanSectionDom = $('.scan-section');
      let rejectionSectionDom = $('.rejection-section');
      let responseMessageDom = $('.js-response-message');
      // sewing output scan
      $(document).on('submit', '#sewingFormSubmit', function (e) {
        let current = $(this);
        if (current.data('requestRunning')) {
          return false;
        }
        var message;
        var input = {};
        var output_challan_no = $('.sewing-challan-no').attr('sewing-challan-no');
        var bundleCardId = $('#sewingBarcode').val().trim();
        input['output_challan_no'] = output_challan_no;
        input['bundle_card_id'] = bundleCardId;
        input['_token'] = $('meta[name="csrf-token"]').attr('content');

        var serial = $('#sewingScanResult tr').length;
        if (output_challan_no && bundleCardId.length == 9) {
          current.data('requestRunning', true);
          var lastTr = $('#sewingScanResult').find('tr:last');
          showLoader();
          $.ajax({
            type: 'POST',
            data: input,
            url: '/sewing-output-scan-post',
            success: function (response) {
              $('#sewingBarcode').val('');
              hideLoader();
              if (response.status == 0) {
                var bundle = response.details;
                let bundle_card_id = bundle.bundle_card_id;
                $('#sewingFormSubmit').find('.hide').removeClass('hide');
                if (response.rejection_status == 1) {
                  toggleRejectionSection(bundle_card_id);
                  return;
                }
                var bundleQty = bundle.sewing_qty;

                var resultRows = '<tr>' +
                    '<td>' + serial + '</td>' +
                    '<td>' + bundle.line_no + '</td>' +
                    '<td>' + bundle.buyer + '</td>' +
                    '<td>' + bundle.style_name + '</td>' +
                    '<td>' + bundle.po_no + '</td>' +
                    '<td>' + bundle.color + '</td>' +
                    '<td>' + bundle.size + '</td>' +
                    '<td>' + bundle.bundle_no + '</td>' +
                    '<td>' + bundleQty + '</td>' +
                    '</tr>';

                lastTr.find('.totalBundle').html(serial);
                lastTr.before(resultRows);

                var totalQty = lastTr.find('.totalQty').text();
                totalQty = totalQty ? parseInt(totalQty) : 0;
                totalQty = totalQty + bundleQty;
                lastTr.find('.totalQty').html(totalQty);

              } else {
                responseMessageDom.html(getMessage(response.message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
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
          responseMessageDom.html(getMessage('Please scan valid bundle', 'danger')).fadeIn().delay(2000).fadeOut(2000);
          current.data('requestRunning', false);
        }
        e.preventDefault();
      });

      $(document).on("submit", "#sewing-rejection-form", function (e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

        $('.text-danger').html('');
        showLoader();
        $.ajax({
          type: "POST",
          url: url,
          data: form.serialize()
        }).done(function (response) {
          hideLoader();
          if (response.status == 200) {
            var serial = $('#sewingScanResult tr').length;
            var lastTr = $('#sewingScanResult').find('tr:last');
            var bundle = response.details;
            let bundle_card_id = bundle.bundle_card_id;
            $('#sewingFormSubmit').find('.hide').removeClass('hide');
            var bundleQty = bundle.sewing_qty;

            var resultRows = '<tr>' +
              '<td>' + serial + '</td>' +
                  '<td>' + bundle.line_no + '</td>' +
                  '<td>' + bundle.buyer + '</td>' +
                  '<td>' + bundle.style_name + '</td>' +
                  '<td>' + bundle.po_no + '</td>' +
                  '<td>' + bundle.color + '</td>' +
                  '<td>' + bundle.size + '</td>' +
                  '<td>' + bundle.bundle_no + '</td>' +
                  '<td>' + bundleQty + '</td>' +
                '</tr>';

            lastTr.find('.totalBundle').html(serial);
            lastTr.before(resultRows);

            var totalQty = lastTr.find('.totalQty').text();
            totalQty = totalQty ? parseInt(totalQty) : 0;
            totalQty = totalQty + bundleQty;
            lastTr.find('.totalQty').html(totalQty);

            responseMessageDom.html(getMessage(response.message, 'success')).fadeIn().delay(2000).fadeOut(2000);
            toggleRejectionSection();
          } else {
            responseMessageDom.html(getMessage(response.message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
          }
        }).fail(function (response) {
          hideLoader();
          $.each(response.responseJSON.errors, function (errorIndex, errorValue) {
            let errorDomElement, error_index, errorMessage;
            errorDomElement = '' + errorIndex;
            errorDomIndexArray = errorDomElement.split(".");
            errorDomElement = '.' + errorDomIndexArray[0];
            error_index = errorDomIndexArray[1];
            errorMessage = errorValue[0];
            if (errorDomIndexArray.length == 1) {
              $(errorDomElement + '_error').html(errorMessage);
            }
          });
        });
      });

      function toggleRejectionSection(bundle_card_id = '') {
        scanSectionDom.toggleClass('hide');
        rejectionSectionDom.toggleClass('hide');
        $('[name="id"]').val(bundle_card_id);
        $('[name="sewing_rejection"]').val('');
      }
    });
</script>
@endsection
