@extends('warehouse-management::layout')
@section('styles')
<style>
  .select2-container .select2-selection--single {
    height: 40px;
    border-radius: 0px;
    line-height: 50px;
    border: 1px solid #e7e7e7;
  }

  .reportTable .select2-container .select2-selection--single {
    border: 1px solid #e7e7e7;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
    width: 150px;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 8px;
  }

  .select2-container--default .select2-selection--multiple {
    min-height: 40px !important;
    border-radius: 0px;
    width: 100%;
  }
</style>
@endsection
@section('title', $warehouse_carton ? 'Update Carton Entry' : 'New Carton Entry')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>{{ $warehouse_carton ? 'Update Carton Entry' : 'New Carton Entry' }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <div class="flash-message" style="margin-bottom: 20px;">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                @endif
            @endforeach
          </div>
          {!! Form::open(['url' => $warehouse_carton ? '/warehouse-cartons/'.$warehouse_carton->id : '/warehouse-cartons', 'method' => $warehouse_carton ? 'PUT' : 'POST', 'id' => 'warehouse-carton-entry-form']) !!}
          @if($warehouse_carton)
            {!! Form::hidden('id', $warehouse_carton->id) !!}
          @endif
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="buyer_id" class="form-control-label">Buyer <span class="text-danger">*</span></label>
                {!! Form::select('buyer_id', $buyers, $warehouse_carton ? $warehouse_carton->buyer_id: null, ['class' => 'form-control form-control-sm', 'id' => 'buyer_id']) !!}
                <span class="text-danger buyer_id"></span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="order_id" class="form-control-label">Order/ Style <span class="text-danger">*</span></label>
                {!! Form::select('order_id', $orders, $warehouse_carton ? $warehouse_carton->order_id: null, ['class' => 'form-control form-control-sm', 'id' => 'order_id']) !!}
                <span class="text-danger order_id"></span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="purchase_order_id" class="form-control-label">Purchase Order <span
                    class="text-danger">*</span></label>
                {!! Form::select('purchase_order_id', $purchase_orders, $warehouse_carton ? $warehouse_carton->purchase_order_id: null, ['class' => 'form-control form-control-sm', 'id' => 'purchase_order_id']) !!}
                <span class="text-danger purchase_order_id"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 table-responsive color-size-breakdown-form">
              @if($warehouse_carton)
              @include('warehouse-management::forms.po_size_breakdown_form')
              @endif
            </div>
          </div>

          <div class="form-group row m-t-md">
            <div class="col-sm-10">
              <button type="submit" class="btn white">{{ $warehouse_carton ? 'Update' : 'Create' }}</button>
              <a class="btn white" href="{{ url('/warehouse-cartons') }}">Cancel</a>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
  $(function() {
    const buyerSelectDom = $('[name="buyer_id"]');
    const orderSelectDom = $('[name="order_id"]');
    const poSelectDom = $('[name="purchase_order_id"]');

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

    poSelectDom.select2({
      ajax: {
        url: '/utility/get-pos-for-select2-search',
        data: function (params) {
          const orderId = orderSelectDom.val();
          return {
            order_id: orderId,
            search: params.term
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
      placeholder: 'Select PO',
      allowClear: true
    });

    $(document).on('change', '#purchase_order_id', function () {
        $('.purchase_order_id').html('');
        var purchase_order_id = $(this).val();
        if (purchase_order_id) {
            $.ajax({
                type: 'GET',
                url: '/get-purchase-order-details-for-warehouse/' + purchase_order_id,
            }).done(function (response) {
                $('.color-size-breakdown-form').html(response.po_size_breakdown_html);
            });
        }
    });

    $(document).on('keyup', '.color_size_qty', function () {
        var quantity = $(this).val();
        var quantityValue = Number(quantity);

        if (isNaN(quantityValue)) {
            alert('Positive Integer value required!');
            $(this).val(0);
            return false;
        }

        if (quantityValue < 0) {
            alert('Negative value is not permitted!');
            $(this).val(0);
            return false;
        }

    });

    $(document).on('keyup', '.color_size_qty', function () {
        var color_size_qty = $(this).val();

        if (color_size_qty) {
            $(this).parents('td').find('.text-danger').html('');
        }
    });

    $(document).on('submit', '#warehouse-carton-entry-form', function (e) {
        e.preventDefault();
        var form = $(this);
        var messageElement = $('.flash-message');
        messageElement.html('');
        showLoader();

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
        }).done(function (response) {
            hideLoader();
            if (response.status == 'error') {
                $.each(response.errors, function (errorIndex, errorValue) {
                    let errorDomElement, error_index, errorMessage;
                    errorDomElement = '' + errorIndex;
                    errorDomIndexArray = errorDomElement.split(".");
                    errorDomElement = '.' + errorDomIndexArray[0];
                    error_index = errorDomIndexArray[1];
                    errorMessage = errorValue[0];
                    if (errorDomIndexArray.length == 2) {
                        errorDomElement = errorDomElement + '_' + error_index;
                    }
                    $(errorDomElement).html(errorMessage);
                });
            }

            if (response.status == 'success') {
                messageElement.append(response.message);
                messageElement.fadeIn().delay(2000).fadeOut(2000);
                setTimeout(redirectToList(response.warehouse_carton_id), 2000);
            }

            if (response.status == 'danger') {
                messageElement.append(response.message);
                messageElement.fadeIn().delay(2000).fadeOut(2000);
            }
        });
    });
  });
        
  function redirectToList(warehouse_carton_id) {
    let url = window.location.protocol + "//" + window.location.host + "/warehouse-cartons/" + warehouse_carton_id + "/show";
    window.location.href = url;
  }
</script>
@endsection