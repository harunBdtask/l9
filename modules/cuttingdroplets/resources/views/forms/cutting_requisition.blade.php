@extends('cuttingdroplets::layout')
@section('title', 'Cutting Requisition')
@push('style')
  <style xmlns="http://www.w3.org/1999/html">
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      width: 120px;
    }

    table {
      display: block;
      overflow-x: auto;
      white-space: nowrap;
    }
  </style>
@endpush
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h2>{{ $cutting_requisition ? 'Update Cutting Requisition' : 'New Cutting Requisition' }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            {!! Form::model($cutting_requisition, ['url' => $cutting_requisition ? 'cutting-requisitions/'.$cutting_requisition->id : 'cutting-requisitions', 'method' => $cutting_requisition ? 'PUT' : 'POST', 'autocomplete' => 'off']) !!}

            <div class="form-group">
              <div class="col-sm-3">
                <label for="cutting_requisition_no"><b>Cutting Requisition No:</b></label>
                {!! Form::text('cutting_requisition_no', $cutting_requisition_no ?? $cutting_requisition->cutting_requisition_no, ['class' => 'form-control form-control-sm', 'id' => 'cutting_requisition_no', 'required', 'readonly' => 'readonly', 'style' => $errors->has("cutting_requisition_no") ? 'border: 1px solid red;' : '']) !!}
                <span class="text-danger">{{ $errors->first('cutting_requisition_no') }}</span>
              </div>
            </div>

            <div class="table-responsive">
              <table class="reportTable">
                <thead class="text-center">
                <tr>
                  <th width="9%">Buyer</th>
                  <th width="8%">Style</th>
                  <th width="8%">Order/Style No</th>
                  <th width="10%">Fabric Composition</th>
                  <th width="10%">Fabric Type</th>
                  <th width="8%">Color</th>
                  <th width="10%">Garments Part</th>
                  <th width="6%">Batcn No</th>
                  <th width="10%">Requisition Amount</th>
                  <th width="8%">Remark</th>
                  <th width="10%">Action</th>
                </tr>
                </thead>
                <tbody class="table-row">
                @if($cutting_requisition && !old('row_count'))
                  @foreach($cutting_requisition->cuttingRequisitionDetails as $key => $requisitionDetail)
                    <tr style="height: 45px;" class="tr-row">
                      <td>
                        {!! Form::hidden('row_count[]', 1) !!}
                        {!! Form::select("buyer_id[]", $buyers, $requisitionDetail->buyer_id ?? null, ['class' => 'requisition-buyer form-control form-control-sm select2-input', 'id' => 'buyer_id', 'placeholder' => 'Select a Buyer']) !!}
                      </td>
                      <td>
                        @php
                          $orderBookingNos = \SkylarkSoft\GoRMG\Merchandising\Models\Order::getOrdersWithBookingNo($requisitionDetail->buyer_id ?? null);
                          $orders = $orderBookingNos->pluck('order_style_no', 'id')->all();
                          $bookingNos = $orderBookingNos->pluck('booking_no', 'id')->all();
                        @endphp
                        {!! Form::select("order_id[]", $bookingNos, $requisitionDetail->order_id ?? null, ['class' => 'booking-no form-control form-control-sm select2-input', 'id' => 'order_id']) !!}
                      </td>
                      <td>
                        {!! Form::select("booking_no[]", $orders, $requisitionDetail->order_id ?? null, ['class' => 'requisition-order select2-input form-control form-control-sm ', 'id' => 'booking_no', 'disabled']) !!}
                      </td>
                      <td>
                        {!! Form::select('composition_fabric_id[]', $compositions, $requisitionDetail->composition_fabric_id, ['class' => 'form-control form-control-sm select2-input composition_fabric_id','autocomplete'=>'off']) !!}
                      </td>
                      <td>
                        {!! Form::select("fabric_type[]", $fabric_types, $requisitionDetail->fabric_type ?? null, ['class' => 'fabric_type form-control form-control-sm select2-input', 'placeholder' => 'Select a Fabric Type.']) !!}
                      </td>
                      <td>
                        @php
                          $colors = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorsByOrder($requisitionDetail->order_id ?? null);
                        @endphp
                        {!! Form::select("color_id[]", $colors, $requisitionDetail->color_id ?? null, ['class' => 'requisition_color form-control form-control-sm select2-input', 'id' => 'buyer_id', 'placeholder' => 'Select a Color']) !!}
                      </td>
                      <td>
                        {!! Form::select('garments_part_id[]', $garments_part, $requisitionDetail->garments_part_id ?? null, ['class' => 'form-control form-control-sm select2-input garments_part_id', 'autocomplete'=>'off']) !!}
                      </td>
                      <td>
                        {!! Form::text('batch_no[]', $requisitionDetail->batch_no ?? null, ['class' => '', 'id' => 'batch', 'placeholder' => 'Batch no', 'size' => '13']) !!}
                      </td>
                      <td>
                        {!! Form::hidden('unit_of_measurement_id[]',  $requisitionDetail->unit_of_measurement_id ?? null, ['class' => 'unit_of_measurement_id']) !!}
                        {!! Form::text("requisition_amount[]", $requisitionDetail->requisition_amount ?? null, ['class' => 'number-right requisition_amount', 'size' => '18']) !!}
                      </td>
                      <td>
                        {!! Form::textarea("remark[]", $requisitionDetail->remark ?? null, ['class' => 'remark', 'rows' => 2, 'cols' => 12, 'id' => 'remark']) !!}
                      </td>
                      <td>
                        <span class="btn btn-xs add-more-btn btn-success"><i class="fa fa-plus"></i> </span>
                        <span
                            class="btn btn-xs del-btn btn-danger @if($cutting_requisition->cuttingRequisitionDetails->count() == 1) hide @endif"><i
                              class="fa fa-times"></i> </span>
                      </td>
                    </tr>
                  @endforeach
                @elseif(old('row_count'))
                  @php
                    $rows = old('row_count') ?? '' ;
                  @endphp
                  @foreach($rows as $key => $row)
                    <tr style="height: 45px;" class="tr-row">
                      <td>
                        {!! Form::hidden("row_count[$key]", 1) !!}
                        {!! Form::select("buyer_id[$key]", $buyers, old('buyer_id')[$key] ?? null, ['class' => 'requisition-buyer form-control form-control-sm select2-input', 'id' => 'buyer_id', 'placeholder' => 'Select a Buyer']) !!}

                        @if($errors->has("buyer_id.$key"))
                          <div class="text-danger">
                            This is required
                          </div>
                        @endif
                      </td>
                      <td>
                        @php
                          $orderBookingNos = \SkylarkSoft\GoRMG\Merchandising\Models\Order::getOrdersWithBookingNo(old('buyer_id')[$key] ?? null);
                          $orders = $orderBookingNos->pluck('order_style_no', 'id')->all();
                          $bookingNos = $orderBookingNos->pluck('booking_no', 'id')->all();
                        @endphp
                        {!! Form::select("order_id[$key]", $bookingNos, old('order_id')[$key] ?? null, ['class' => 'booking-no form-control form-control-sm select2-input', 'id' => 'order_id']) !!}

                        @if($errors->has("order_id.$key"))
                          <div class="text-danger">
                            {{-- {{ $errors->first("order_id.$key") }} --}}
                            This is required
                          </div>
                        @endif
                      </td>
                      <td>
                        {!! Form::select("booking_no[$key]", $orders, old('booking_no')[$key] ?? null, ['class' => 'form-control form-control-sm requisition-order select2-input', 'id' => '', 'disabled' => 'disabled']) !!}
                      </td>
                      @php
                        if (isset(old('order_id')[$key])) {
                            $fabric_compositions = \SkylarkSoft\GoRMG\Merchandising\Models\BudgetFabricBooking::where('order_id', old('order_id')[$key])
                                ->pluck('fabric_composition', 'composition_fabric_id');
                        }
                      @endphp
                      <td>
                        {!! Form::select("composition_fabric_id[$key]", $fabric_compositions ?? [], old('composition_fabric_id')[$key] ?? null, ['class' => 'form-control form-control-sm select2-input composition_fabric_id','autocomplete'=>'off']) !!}
                      </td>
                      @php
                        if (isset(old('order_id')[$key]) && isset(old('composition_fabric_id')[$key])) {
                            $bookings = \SkylarkSoft\GoRMG\Merchandising\Models\BudgetFabricBooking::with('color')->where([
                                'order_id' => old('order_id')[$key],
                                'composition_fabric_id' => old('composition_fabric_id')[$key]]
                            )->get();

                            $fabricTypes = \SkylarkSoft\GoRMG\SystemSettings\Models\FabricType::whereIn('id', $bookings->pluck('fabric_type_id')->unique()->all())
                                ->pluck('fabric_type_name', 'id');
                            $colors = $bookings->pluck('color.name', 'color.id');
                        }
                      @endphp
                      <td>
                        {!! Form::select("fabric_type[$key]", $fabricTypes ?? [], old('fabric_type')[$key] ?? null, ['class' => 'fabric_type form-control form-control-sm select2-input', 'id' => 'fabric_type', 'placeholder' => 'Select a Fabric Type.']) !!}

                        @if($errors->has("fabric_type.$key"))
                          <div class="text-danger">
                            {{-- {{ $errors->first("fabric_type.$key") }} --}}
                            This is required
                          </div>
                        @endif
                      </td>
                      <td>
                        @php
                          /*$colors = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorsByOrder( old('order_id')[$key] ?? null);*/
                        @endphp
                        {!! Form::select("color_id[$key]", $colors ?? [], old('color_id')[$key] ?? null, ['class' => 'requisition_color form-control form-control-sm select2-input', 'id' => 'color_id', 'placeholder' => 'Select a Color']) !!}

                        @if($errors->has("color_id.$key"))
                          <div class="text-danger">
                            {{ $errors->first("color_id.$key") }}
                            {{-- This is required --}}
                          </div>
                        @endif
                      </td>
                      <td>
                        {!! Form::select("garments_part_id[$key]", $garments_part ?? [], old('garments_part_id')[$key] ?? null, ['class' => 'form-control form-control-sm select2-input garments_part_id','autocomplete'=>'off']) !!}

                        @if($errors->has("garments_part_id.$key"))
                          <div class="text-danger">
                            {{ $errors->first("garments_part_id.$key") }}
                            {{-- This is required --}}
                          </div>
                        @endif
                      </td>
                      <td>
                        {!! Form::text("batch_no[$key]", null, ['class' => '', 'id' => 'batch', 'placeholder' => 'Batch no', 'size' => '13']) !!}

                        @if($errors->has("batch_no.$key"))
                          <div class="text-danger">
                            {{ $errors->first("batch_no.$key") }}
                            {{-- This is required --}}
                          </div>
                        @endif
                      </td>
                      <td>
                        {!! Form::hidden("unit_of_measurement_id[$key]", old('unit_of_measurement_id')[$key] ?? null, ['class' => 'unit_of_measurement_id']) !!}
                        {!! Form::text("requisition_amount[$key]", old('requisition_amount')[$key] ?? null, ['class' => 'number-right requisition_amount', 'size' => '18']) !!}

                        @if($errors->has("requisition_amount.$key"))
                          <div class="text-danger">
                            {{ $errors->first("requisition_amount.$key") }}
                          </div>
                        @endif
                      </td>
                      <td>
                        {!! Form::textarea("remark[$key]", old('remark')[$key] ?? null, ['class' => 'remark', 'rows' => 2, 'cols' => 12, 'id' => 'remark']) !!}
                      </td>
                      <td>
                        <span class="btn btn-xs add-more-btn btn-success"><i class="fa fa-plus"></i> </span>
                        <span class="btn btn-xs del-btn btn-danger hide"><i class="fa fa-times"></i> </span>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr style="height: 45px;" class="tr-row">
                    <td>
                      {!! Form::hidden('row_count[]', 1) !!}
                      {!! Form::select('buyer_id[]', $buyers, null, ['class' => 'requisition-buyer form-control form-control-sm select2-input', 'id' => 'buyer_id', 'placeholder' => 'Select a Buyer']) !!}
                    </td>
                    <td>
                      {!! Form::select('order_id[]', [], null, ['class' => 'booking-no form-control form-control-sm select2-input', 'id' => 'booking_no']) !!}
                    </td>
                    <td>
                      {!! Form::select('booking_no[]', [], null, ['class' => 'requisition-order form-control form-control-sm select2-input', 'id' => 'order_id', 'disabled']) !!}
                    </td>
                    <td>
                      {!! Form::select('composition_fabric_id[]',[], null, ['class' => 'form-control form-control-sm select2-input composition_fabric_id','autocomplete'=>'off']) !!}
                    </td>
                    <td>
                      {!! Form::select('fabric_type[]', [], null, ['class' => 'fabric_type form-control form-control-sm select2-input', 'id' => 'fabric_type', 'placeholder' => 'Select a Fabric Type.']) !!}
                    </td>
                    <td>
                      {!! Form::select('color_id[]', [], null, ['class' => 'requisition_color form-control form-control-sm select2-input', 'id' => 'color_id', 'placeholder' => 'Select a Color']) !!}
                    </td>
                    <td>
                      {!! Form::select('garments_part_id[]', $garments_part ?? [], null, ['class' => 'form-control form-control-sm select2-input garments_part_id','autocomplete'=>'off']) !!}
                    </td>
                    <td>
                      {!! Form::text('batch_no[]', null, ['class' => '', 'id' => 'batch', 'placeholder' => 'Batch no', 'size' => '13']) !!}
                    </td>
                    <td>
                      {!! Form::hidden('unit_of_measurement_id[]', null, ['class' => 'unit_of_measurement_id']) !!}
                      {!! Form::text('requisition_amount[]', null, ['class' => 'number-right requisition_amount', 'placeholder' => 'Enter requisition amount', 'size' => '18']) !!}
                    </td>
                    <td>
                      {!! Form::textarea('remark[]', null, ['class' => 'remark', 'rows' => 2, 'cols' => 12, 'id' => 'remark']) !!}
                    </td>
                    <td>
                                        <span class="btn btn-xs add-more-btn btn-success">
                                            <i class="fa fa-plus" aria-hidden="true"></i></span>
                      <span class="btn btn-xs del-btn btn-danger hide">
                                                <i class="fa fa-times"></i>
                                            </span>
                    </td>
                  </tr>
                @endif
                <tr style="height:60px">
                  <td colspan="11">
                    <button type="submit" class="btn btn-success">Submit</button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>

            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  <style type="text/css">
    td {
      padding-left: 5px;
      padding-right: 5px;
    }

    .number-right {
      padding-right: 5px !important;
      height: 27px;
    }

    .requisition_amount {
      font-weight: bold;
    }
  </style>
@endsection
@section('scripts')
  <script type="text/javascript">
    $(document).on('change', '.garments_part_id', function (e) {
      e.preventDefault();
      let parentTr = $(this).parents('tr');
      let garments_part_id = $(this).val();
      let order_id = parentTr.find('.booking-no').val();
      let fabric_type_id = parentTr.find('.fabric_type').val();
      let color_id = parentTr.find('.requisition_color').val();
      let composition_fabric_id = parentTr.find('.composition_fabric_id').val();

      var params = {
        order_id: order_id,
        garments_part_id: garments_part_id,
        fabric_type_id: fabric_type_id,
        color_id: color_id,
        composition_fabric_id: composition_fabric_id
      };

      if (garments_part_id) {
        $.ajax({
          type: 'GET',
          url: '/get-fabric-fabric-received-store?' + $.param(params),
          success: function (response) {
            parentTr.find('.requisition_amount').val('');
            let uomId = response.unit_of_measurement_id ? response.unit_of_measurement_id : '';
            let availableAmount = 'Available= '
                + (response.available_amount ? response.available_amount : 0)
                + (response.uom ? response.uom : '');

            parentTr.find('.unit_of_measurement_id').val(uomId);
            parentTr.find('.requisition_amount')
            .attr('placeholder', availableAmount);
          }
        });
      }
    })


    $(document).on('click', '.add-more-btn', function () {
      // for select2 destroy
      $(this).parents('tr').find('.select2-input').each(function (index) {
        if ($(this).data('select2')) {
          $(this).select2('destroy');
        }
      });

      var tr = $(this).closest('tr');
      var trClone = tr.clone();
      tr.after(trClone);
      $('.table-row').find('.del-btn').removeClass('hide');
      $('.select2-input').select2();
    });

    $(document).on('click', '.del-btn', function () {
      if ($('.tr-row').length > 1) {
        if (confirm('are you sure to delete this?') === true) {
          $(this).closest('tr').remove();
          if ($('.tr-row').length === 1) {
            $('.table-row').find('.del-btn').addClass('hide');
          }
        }
      }
    });

    $(document).on('change', '.requisition-buyer', function (e) {
      e.preventDefault();
      var buyer_id = $(this).val();
      var parentTr = $(this).closest('tr');
      parentTr.find('.booking-no').empty();
      parentTr.find('.booking-no').val('').select2();
      parentTr.find('.requisition_color').empty();
      parentTr.find('.requisition_color').val('').select2();
      parentTr.find('.requisition-order').empty();
      parentTr.find('.requisition-order').val('').select2();

      if (buyer_id) {
        $.ajax({
          type: 'GET',
          url: '/get-orders-with-booking-no/' + buyer_id,
          success: function (response) {
            var orderDropdown = '<option value="">Select a Order/Style</option>';
            var bookingNoDropdown = '<option value="">Select a Booking no</option>';
            if (Object.keys(response).length > 0) {
              $.each(response, function (index, data) {
                orderDropdown += '<option value="' + data.id + '">' + data.order_style_no + '</option>';
                bookingNoDropdown += '<option value="' + data.id + '">' + data.booking_no + '</option>';
              });
              parentTr.find('.requisition-order').html(orderDropdown);
              parentTr.find('.booking-no').html(bookingNoDropdown);
            }
          }
        });
      }
    });

    $(document).on('change', '.booking-no', function (e) {
      e.preventDefault();
      var order_id = $(this).val();
      var parentTr = $(this).closest('tr');
      parentTr.find('.requisition_color').empty();
      parentTr.find('.requisition_color').val('').select2();
      parentTr.find('.requisition-order').val(order_id).select2();

      if (order_id) {
        $.ajax({
          type: 'GET',
          url: '/get-colors-by-order/' + order_id,
          success: function (response) {
            var colorDropdown = '<option value="">Select a Color</option>';
            if (Object.keys(response).length > 0) {
              $.each(response, function (index, val) {
                colorDropdown += '<option value="' + index + '">' + val + '</option>';
              });
              parentTr.find('.requisition_color').html(colorDropdown);
            }
          }
        });

        $.ajax({
          type: 'GET',
          url: '/get-fabric-compositions-by-order/' + order_id,
          success: function (response) {
            let compositionDropdown = '<option value="">Select A Fabric Composition</option>';
            if (Object.keys(response).length > 0) {
              $.each(response, function (idx, val) {
                compositionDropdown += `<option value="${idx}"">${val}</option>`
              })
            }
            parentTr.find('.composition_fabric_id').html(compositionDropdown);
          }
        })
      }
    });

    $(document).on('change', '.composition_fabric_id', function (e) {
      e.preventDefault();
      let composition_id = $(this).val();
      let parentTr = $(this).parents('tr');
      parentTr.find('.fabric_type').empty();
      parentTr.find('.requisition_color').empty();
      parentTr.find('.fabric_type').val('').select2();
      parentTr.find('.requisition_color').val('').select2();

      if (composition_id) {
        let order_id = $(this).parents('tr').find('.booking-no').val();
        $.ajax({
          type: 'GET',
          url: '/get-fabric-type-by-order-composition/' + composition_id + '/' + order_id,
          success: function (response) {
            let fabricTypeDropdown = '<option value="">Select a Type</option>';
            let colorDropdown = '<option value="">Select a Color</option>';

            if (Object.keys(response.types).length > 0) {
              $.each(response.types, function (index, val) {
                fabricTypeDropdown += '<option value="' + index + '">' + val + '</option>';
              });
              parentTr.find('.fabric_type').html(fabricTypeDropdown);
            }
            if (Object.keys(response.colors).length > 0) {
              $.each(response.colors, function (index, val) {
                colorDropdown += '<option value="' + index + '">' + val + '</option>';
              });
              parentTr.find('.requisition_color').html(colorDropdown);
            }
          }
        });
      }
    });
  </script>
@endsection
