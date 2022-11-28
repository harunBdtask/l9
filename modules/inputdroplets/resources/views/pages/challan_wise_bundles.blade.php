@extends('inputdroplets::layout')
@section('title', 'Challan Wise Bundle Card Information')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Challan Wise Bundle Card Information</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <div class="row form-group">
            <div class="col-sm-2">
              <input type="number" class="form-control form-control-sm searching-challan-no" id="barcode" placeholder="Challan No"
                autofocus="" name="barcode" required="required">
            </div>
          </div>

          <table class="reportTable">
            <thead>
              <tr>
                <th colspan="10">Delivered To Sewing Floor: &nbsp;<span class="input-floor-no"></span> Line No: &nbsp;<span class="input-line-no"></span></th>
                <th colspan="11">Date: &nbsp; <span class="input-date"></span></th>
              </tr>
            </thead>
            <thead>
              <tr>
                <th>SL</th>
                <th>Barcode</th>
                <th>Buyer</th>
                <th>Style</th>
                <th>PO</th>
                <th>Color</th>
                <th>Cutt. No.</th>
                <th>Bundle No.</th>
                <th>Size</th>
                <th>Cutt. Qty</th>
                <th>Print Sent</th>
                <th>Print Recv.</th>
                <th>Embr Sent</th>
                <th>Embr Embr.</th>
                <th>Cutt. Rej.</th>
                <th>Print. Rej.</th>
                <th>Embr. Rej.</th>
                <th>Input qty</th>
                <th>Sewing qty</th>
                <th>Sewing Rej.</th>
                <th>Total Rej.</th>
              </tr>
            </thead>
            <tbody class="challan-wise-bndle">

            </tbody>
          </table>
          <div class="loader"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
<script>
  $(document).on('keyup', '.searching-challan-no', function () {
      $('.challan-wise-bndle').empty();
      $('.input-line-no').empty();
      $('.input-date').empty();
      var challan_no = $('.searching-challan-no').val();
      if (challan_no.length) {
        $('.loader').html(loader);
        $.ajax({
          type: 'GET',
          url: '/challan-wise-bundles-list/' + challan_no,
          success: function (response) {
            $('.loader').empty();
            if (response.challan_wise_bundles) {
              $('.input-floor-no').html(response.challan_wise_bundles.line.floor.floor_no);
              $('.input-line-no').html(response.challan_wise_bundles.line.line_no);
              $('.input-date').html(response.challan_wise_bundles.updated_at);
              $('.challan-wise-bndle').html(response.view);
            } else {
              $('.challan-wise-bndle').html(response.view);
            }
          },
          error: function(response) {
            $('.loader').empty();
            console.log(response)
          }
        });
      }
    });
</script>
@endsection
