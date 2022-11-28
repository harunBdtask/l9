@extends('skeleton::layout')
@section('title', 'Lots')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $lot ? 'Update Lot' : 'New Lot' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($lot, ['url' => $lot ? 'lots/'.$lot->id : 'lots', 'method' => $lot ? 'PUT' : 'POST', 'id' =>
                        'lotForm']) !!}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="lotNo">Lot No.</label>
                                    {!! Form::text('lot_no', null, ['class' => 'form-control form-control-sm', 'id' => 'lotNo', 
                                    'placeholder' => 'Write lot\'s no. here']) !!}

                                    @if($errors->has('lot_no'))
                                        <span class="text-danger">{{ $errors->first('lot_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="buyer">Buyer</label>
                                    {!! Form::select('buyer_id', $buyers, null, ['class' => 'form-control form-control-sm c-select select2-input', 'id' =>
                                        'buyer', 'placeholder' => 'Select an buyer']) !!}

                                    @if($errors->has('buyer_id'))
                                        <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-3">
                                @php
                                    if (old('order_id') || isset($lot) ) {
                                    $buyerId = old('buyer_id') ?? $lot->buyer_id;

                                    $styleNames = \SkylarkSoft\GoRMG\Merchandising\Models\Order::where('buyer_id', $buyerId)
                                    ->pluck('style_name', 'id')
                                    ->all();
                                    $order_id = $lot->order_id ?? old('order_id');
                                    $purchaseOrders = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder::where('order_id', $order_id)
                                    ->pluck('po_no','id')->all();
                                    }
                                @endphp
                                <div class="form-group">
                                    <label for="order">Style Name</label>
                                    {!! Form::select('order_id', $styleNames ?? [], $lot->order_id ?? null, ['class' => 'form-control form-control-sm c-select
                                        select2-input', 'id' => 'orderStyle', 'placeholder' => 'Select Style']) !!}
                                    <span class="text-danger font-italic font-weight-bold" id="ref_no">{{ ($lot && $lot->order_id) ? $lot->order->reference_no : '' }}</span>
                                    @if($errors->has('order_id'))
                                        <span class="text-danger">{{ $errors->first('order_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                  <label for="order">Purchase Order</label>
                                  {!! Form::select('purchase_order_id[]', $purchaseOrders ?? [], null, ['class' => 'form-control form-control-sm c-select
                                      select2-input', 'id' => 'purchaseOrder', 'multiple' => 'multiple']) !!}

                                  @if($errors->has('purchase_order_id'))
                                      <span class="text-danger">{{ $errors->first('purchase_order_id') }}</span>
                                  @endif
                              </div>

                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-3">
                              @php
                                  if((old('buyer_id') && old('order_id')) || isset($lot)) {
                                  $buyer_id = old('buyer_id') ?? $lot->buyer_id;
                                  $order_id = old('order_id') ?? $lot->order_id;
                                  $colors = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder::with(['colors' => function ($query) {
                                  $query->withoutGlobalScope('factoryId');
                                  }])
                                  ->where('buyer_id', $buyer_id)
                                  ->where('order_id', $order_id)
                                  ->get()
                                  ->map(function ($item) {
                                  return $item->colors;
                                  })
                                  ->flatten()
                                  ->pluck('name','id')->all();
                                  }
                              @endphp
                              <div class="form-group">
                                  <label for="color">Color</label>
                                  {!! Form::select('color_id', $colors ?? [], null, ['class' => 'form-control form-control-sm c-select select2-input', 'id'
                                      => 'color', 'placeholder' => 'Select a color']) !!}

                                  @if($errors->has('color_id'))
                                      <span class="text-danger">{{ $errors->first('color_id') }}</span>
                                  @endif
                              </div>

                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="fabricReceived">Fabric
                                        Received</label>
                                    {!! Form::text('fabric_received', null, ['class' => 'form-control form-control-sm', 'id' => 'fabricReceived',
                                        'placeholder' => 'Fabric received quantity in kg']) !!}

                                    @if($errors->has('fabric_received'))
                                        <span class="text-danger">{{ $errors->first('fabric_received') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group">
                                  <label for="fabricReceivedAt">Fabric
                                      Received Date</label>
                                  {!! Form::date('fabric_received_at', null, ['class' => 'form-control form-control-sm', 'id' => 'fabricReceivedAt',
                                      'placeholder' => 'Select fabric received date', 'style' => 'max-height: 2.375rem']) !!}

                                  @if($errors->has('fabric_received_at'))
                                      <span class="text-danger">{{ $errors->first('fabric_received_at') }}</span>
                                  @endif
                              </div>

                          </div>
                          <div class="col-lg-3">
                              <div class="form-group m-t-md">
                                  <button type="submit" class="btn white">{{ $lot ? 'Update' : 'Create' }}</button>
                                  <a class="btn white" href="{{ url('lots') }}">Cancel</a>
                              </div>
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
    <script type="text/javascript" src="{{ asset('protracker/lot.js') }}"></script>
@endsection
