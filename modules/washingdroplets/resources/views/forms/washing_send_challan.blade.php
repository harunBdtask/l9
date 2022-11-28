@extends('washingdroplets::layout')
@section('title', 'Washing Send Challan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Washing Scan || {{ date("D\ - F d- Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

             @include('partials.response-message')

              <form method="POST" action="{{ url('/washing-scan-post')}}" accept-charset="UTF-8">
              @csrf

              <input type="hidden" name="washing_challan_no" value="{{ $washing_challan_no ?? '' }}">
              <div class="form-group">
                <div class="col-sm-8 col-sm-offset-2">
                    <input type="text" class="form-control form-control-sm has-value" id="barcode" placeholder="Scan barcode here" autofocus="" name="barcode" required="required">
                    <span> Output challan no: {{ $washing_challan_no }}</span>
                </div>
              </div>
              <div class="form-group m-t-md">
                <div class="col-sm-offset-5 col-sm-7">
                  <a href="{{ url('/washing-challan-close/'.$washing_challan_no)}}" class="btn btn-success">Close Challan</a>
                </div>
              </div>

            </form>

            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Buyer Name</th>
                  <th>Order No.</th>
                  <th>Colour Name</th>
                  <th>Size</th>
                  <th>Bundle Scanned</th>
                  <th>Quantity</th>
                  <th>Country</th>
                </tr>
              </thead>
              <tbody class="print-sent-list">
                @if(isset($washing_bundles))
                  @php $total = 0; @endphp
                  @foreach($washing_bundles as $bundle)
                    @php
                        $total += $bundle->bundlecard->quantity;
                        //$bundle->bundlecard->quantity - $bundle->bundlecard->sewing_rejection
                        //$left_qty += $bundle->bundlecard->quantity;

                    @endphp
                    <tr>
                      <td>{{ $bundle->bundlecard->details->buyer->name }}</td>
                      <td>{{ $bundle->bundlecard->details->order->order_no }}</td>
                      <td>{{ $bundle->bundlecard->details->color->name }}</td>
                      <td>{{ $bundle->bundlecard->size->name }}</td>
                      <td>{{ str_pad($bundle->bundlecard->bundle_no, 2, '0', STR_PAD_LEFT) }}</td>
                      <td>{{ $bundle->bundlecard->quantity }}</td>
                      <td>{{ '' }}</td>
                    </tr>
                  @endforeach
                  <tr>
                    <td colspan="4" class="text-right"><b>Total :</b></td>
                    <td><b>{{ count($washing_bundles) }}</b></td>
                    <td><b>{{ $total }}</b></td>
                    <td></td>
                  </tr>
                @else
                  <tr>
                    <td colspan="7" align="center">Not found<td>
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
   $(document).on('click', '.print-sent-scan', function () {

      $('.loader').html(loader);
      var buyer_id = $(this).val();

      if(buyer_id){
        $.ajax({
            type: 'GET',
            url: '/get-color-wise-cutting-report/'+buyer_id,
            success: function (response) {

              $('.loader').empty();

              if(Object.keys(response).length > 0){

                $('.color-wise-report').empty();
                $.each(response, function (index, report) {
                  var extraQty = 0;
                  var leftQty = 0;
                  var Qty = report.total_quantity - report.total_prod_qty;

                  /*if(new Date().toISOString().slice(0,10) == report.production_date){

                  }*/
                  if(Math.sign(Qty) == -1){
                      extraQty = ((report.total_prod_qty/report.total_quantity)/100).toFixed(2);
                  }else{
                      leftQty = Qty;
                  }
                  var resultRows ='<tr><td>'+report.name+'</td><td>'+report.order_no+'</td><td>'+report.total_quantity+'</td><td>'+report.total_prod_qty+'</td><td>'+report.total_prod_qty+'</td><td>'+leftQty+'</td><td>'+extraQty+' %</td></tr>';

                  $('.color-wise-report').append(resultRows);
                });

              }
            }
        });
      }
    });
</script>
@endsection
