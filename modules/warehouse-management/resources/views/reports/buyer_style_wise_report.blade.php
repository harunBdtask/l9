@extends('warehouse-management::layout')
@section('styles')
    <style>
        .select2-container .select2-selection--single {
            height: 40px;
            border-radius: 0px;
            line-height: 50px;
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
@section('title', 'Buyer Style Wise Report')
@section('content')
    <div class="padding">
        <div class="row buyer-style-wise-warehouse-report">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Buyer Style Wise Report
                            <span class="pull-right">
                                <a href="#" class="download-btn" download-type="pdf"> <i style="color: #DC0A0B"
                                                                                         class="fa fa-file-pdf-o"></i></a> |
                                <a href="#" class="download-btn" download-type="excel"><i style="color: #0F733B"
                                                                                          class="fa fa-file-excel-o"></i></a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="row m-b">
                                <div class="col-sm-3">
                                    <label>Buyer</label>
                                    {!! Form::select('buyer_id', $buyers ?? [], null, ['class' => 'form-control form-control-sm', 'id' => 'buyer_id']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Order/ Style</label>
                                    {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'order_id']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Purchase Order</label>
                                    {!! Form::select('purchase_order_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'purchase_order_id']) !!}
                                </div>
                                <div class="col-sm-3">
                                    <label>Color</label>
                                    {!! Form::select('color_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'color_id']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive reportDiv">
                        </div>
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
    const colorSelectDom = $('[name="color_id"]');

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

    colorSelectDom.select2({
      ajax: {
        url: '/utility/get-colors-for-po-select2-search',
        data: function (params) {
          const orderId = orderSelectDom.val();
          const purchaseOrderId = poSelectDom.val();
          return {
            order_id: orderId,
            purchase_order_id: purchaseOrderId,
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
      placeholder: 'Select Color',
      allowClear: true
    });

    $(document).on('change', '.buyer-style-wise-warehouse-report #color_id', function () {
      var color_id = $(this).val();
      var purchase_order_id = $('#purchase_order_id').val();
      $('.reportDiv').html('');
      if (color_id && purchase_order_id) {
          showLoader();
          $.ajax({
              type: 'GET',
              url: '/get-color-wise-warehouse-report/' + purchase_order_id + '/' + color_id,
          }).done(function (response) {
            hideLoader();
              $('.reportDiv').html(response.html)
          }).fail(function(response) {
            hideLoader();
            console.log(response)
          });
      }
    });

    $(document).on('click', '.buyer-style-wise-warehouse-report .download-btn', function () {
      var type = $(this).attr('download-type');
      var purchase_order_id = $('#purchase_order_id').val();
      var color_id = $('#color_id').val();
      if (purchase_order_id) {
          let url = window.location.protocol + "//" + window.location.host + "/warehouse-buyer-style-wise-status-report-download/"+ type + "/" + purchase_order_id + "/" + color_id;
          window.location.href = url;
      }
    });
  });
</script>
@endsection