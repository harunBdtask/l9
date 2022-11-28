@extends('iedroplets::layout')
@section('title', 'Shipment Entry Update')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Shipment Entry Update</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          @include('partials.response-message')
          <form action="{{ url('/shipments')}}" method="post">
            @csrf
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, null, ['class' => 'shipment-buyer-select form-control form-control-sm', 'placeholder' => 'Select a Buyer']) !!}
                  @if($errors->has('buyer_id'))
                  <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                  @endif
                </div>
                @php
                  if(old('order_id')) {
                    $orders_list = \SkylarkSoft\GoRMG\Merchandising\Models\Order::query()->where('id', old('order_id'))->pluck('style_name', 'id');
                  }
                @endphp
                <div class="col-sm-2">
                  <label>Style/Order</label>
                  {!! Form::select('order_id', $orders_list ?? [], old('order_id') ?? null, ['class' => 'shipment-style-select form-control form-control-sm']) !!}
                  @if($errors->has('order_id'))
                  <span class="text-danger">{{ $errors->first('order_id') }}</span>
                  @endif
                </div>
              </div>
            </div>

            <table class="reportTable">
              <thead class="text-center">
                <tr>
                  <th>PO</th>
                  <th>PO Qty</th>
                  <th>Total Ship Qty</th>
                  <th>Ship Qty</th>
                  <th>Short/Reject Qty</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody class="shipment-status-update">
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
<script>
  const buyerSelectDom = $('[name="buyer_id"]');
  const orderSelectDom = $('[name="order_id"]');
  const shipmentStatusDom = $(".shipment-status-update");

  buyerSelectDom.select2({
    ajax: {
      url: '/utility/get-buyers-for-select2-search',
      data: function (params) {
        return {
          search: params.term,
        }
      },
      processResults: function (data, params) {
        return {
          results: data.results,
          pagination: {
            more: false
          }
        }
      },
      cache: true,
      delay: 250
    },
    placeholder: 'Select Buyer',
    allowClear: true
  });

  orderSelectDom.select2({
    ajax: {
      url: function (params) {
        return `/utility/get-styles-for-select2-search`
      },
      data: function (params) {
        const buyerId = buyerSelectDom.val();
        return {
          search: params.term,
          buyer_id: buyerId,
        }
      },
      processResults: function (data, params) {
        return {
          results: data.results,
          pagination: {
            more: false
          }
        }
      },
      cache: true,
      delay: 250
    },
    placeholder: 'Select Style',
    allowClear: true
  });

  $(document).on('change', '[name="buyer_id"]', function (e) {
    let orderId = orderSelectDom.val();
    if (orderId) {
      orderSelectDom.val('').change();
    }
    shipmentStatusDom.empty();
  });

  orderSelectDom.change(function () {
      $.ajax({
          type: 'GET',
          url: '/get-shipment-po-information/' + orderSelectDom.val(),
          success: function (response) {
              if (Object.keys(response).length > 0) {
                  let result;
                  $.each(response, function (index, shipment) {
                      result += '<tr style="height: 40px">' +
                          '<td>' + shipment.po_no + '<input type="hidden" name="purchase_order_id[]" class="order_id" value="' + shipment.id + '"></td>' +
                          '<td>' + shipment.po_quantity + '</td>' +
                          '<td>' + shipment.ship_quantity + '</td>' +
                          '<td><input type="number" class="form-control quantity number-right" name="ship_quantity[]"></td>' +
                          '<td><input type="number" class="form-control short_reject_qty number-right" name="short_reject_qty[]"></td>' +
                          '<td><input type="text" class="form-control remarks" name="remarks[]" maxlength="191"></td>' +
                          '</tr>';
                  });
                  shipmentStatusDom.html(result);
                  let btnTr = '<tr style="height:44px">' +
                      '<td colspan="6" class="text-center">' +
                      '<button type="submit" class="btn btn-sm btn-success shipment-status-update">Submit</button>' +
                      '</td>' +
                      '</tr>';
                  shipmentStatusDom.append(btnTr);
              } else {
                  let notFound = '<tr class="tr-height"><td class="text-center text-danger" colspan="6">Not Found</td></tr>';
                  shipmentStatusDom.append(notFound);
              }
              shipmentStatusDom.show();
          }
      });
  })
</script>
@endsection