@extends('finishingdroplets::layout')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Add Poly & Cartoon || {{ date("jS, F Y") }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body color-finishing">
                        <div class="box-body">
                            {{--
                            @include('partials.response-message')
                            {!! Form::open(['url' => 'add-poly-cartoon-post', 'method' => 'POST']) !!}
                              <div class="form-group">
                                <div class="row m-b">
                                  <div class="col-sm-3">
                                    <label>Buyer</label>
                                    {!! Form::select('buyer_id', $buyers, null, ['class' => 'select-buyer-poly form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                                    @if($errors->has('buyer_id'))
                                      <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                    @endif
                                  </div>
                                  @php
                                    $style_order_list = [];
                                    $order_id = null;
                                    if(old('buyer_id')) {
                                      $style_order_list = \SkylarkSoft\GoRMG\Merchandising\Models\Order::getOrders(old('buyer_id'));
                                    }
                                    if( old('order_id')) {
                                      $order_id = old('order_id');
                                    }
                                  @endphp
                                  <div class="col-sm-3">
                                    <label>Style/Order No</label>
                                    {!! Form::select('order_id', $style_order_list ?? [], $order_id ?? null, ['class' => 'select-style-poly form-control form-control-sm select2-input','placeholder' => 'Select Style']) !!}
                                    @if($errors->has('order_id'))
                                      <span class="text-danger">{{ $errors->first('order_id') }}</span>
                                    @endif
                                  </div>
                                  @php
                                    $purchase_orders_list = [];
                                    $purchase_order_id = null;
                                    if(old('order_id') && old('purchase_order_id')) {
                                      $purchase_order_id = old('order_id');
                                      $purchase_orders_list = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder::getPurchaseOrder(old('order_id'));
                                    }
                                  @endphp
                                  <div class="col-sm-3">
                                    <label>PO</label>
                                    {!! Form::select('purchase_order_id', $purchase_orders_list ?? [], $purchase_order_id ?? null, ['class' => 'select-order-poly form-control form-control-sm select2-input', 'placeholder' => 'Select a po']) !!}
                                    @if($errors->has('purchase_order_id'))
                                      <span class="text-danger">{{ $errors->first('purchase_order_id') }}</span>
                                    @endif
                                  </div>
                                </div>

                                <table class="reportTable">
                                  <thead class="text-center">
                                    <tr>
                                        <th>Color</th>
                                        <th>Poly Qty</th>
                                        <th>Short/Reject Qty</th>
                                        <th>Reasons</th>
                                    </tr>
                                  </thead>
                                  <tbody class="poly-status-update">
                                  </tbody>
                                </table>
                                <table class="reportTable">
                                  <tr>
                                    <td colspan="4" class="text-center">
                                      <button type="submit" style="display:none" class="btn white poly-status-update-btn">Submit</button>
                                    </td>
                                  </tr>
                                </table>
                              {!! Form::close() !!}
                            --}}
                            @include('partials.response-message')
                            {!! Form::open(['url' => 'poly-cartoons', 'method' => 'POST']) !!}

                            <div class="form-group">
                                <label for="buyer">Buyer</label>
                                {!! Form::select('buyer_id', $buyers, null, ['class' => 'select-buyer-poly form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}

                                @if($errors->has('buyer_id'))
                                    <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="style">Style/Order</label>
                                {!! Form::select('order_id', [], null, ['class' => 'select-style-poly form-control form-control-sm select2-input', 'placeholder' => 'Select a Style/Order']) !!}

                                @if($errors->has('order_id'))
                                    <span class="text-danger">{{ $errors->first('order_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="country">PO</label>
                                {!! Form::select('purchase_order_id', [], null, ['class' => 'select-order-poly form-control form-control-sm select2-input', 'placeholder' => 'Select a PO']) !!}

                                @if($errors->has('purchase_order_id'))
                                    <span class="text-danger">{{ $errors->first('purchase_order_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="country">Color</label>
                                {!! Form::select('color_id', [], null, ['class' => 'select-color-poly form-control form-control-sm select2-input', 'placeholder' => 'Select a Color']) !!}

                                @if($errors->has('color_id'))
                                    <span class="text-danger">{{ $errors->first('color_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="country">Size</label>
                                {!! Form::select('size_id', [], null, ['class' => 'select-size-poly form-control form-control-sm select2-input', 'placeholder' => 'All Sizes']) !!}

                                @if($errors->has('size_id'))
                                    <span class="text-danger">{{ $errors->first('size_id') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="name">Received Qty</label>
                                {!! Form::number('received_qty', null, ['class' => 'form-control form-control-sm', 'id' => 'received_qty', 'placeholder' => 'Give received qty here']) !!}

                                @if($errors->has('received_qty'))
                                    <span class="text-danger">{{ $errors->first('received_qty') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="name">No of qty per poly</label>
                                {!! Form::number('qty_per_poly', 1, ['class' => 'form-control form-control-sm', 'id' => 'qty_per_poly', 'placeholder' => 'Give qty per poly here']) !!}

                                @if($errors->has('qty_per_poly'))
                                    <span class="text-danger">{{ $errors->first('qty_per_poly') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="poly">Poly Qty</label>
                                {!! Form::number('poly_qty', null, ['class' => 'form-control form-control-sm', 'id' => 'poly_qty', 'placeholder' => 'Give an poly qty here']) !!}

                                @if($errors->has('poly_qty'))
                                    <span class="text-danger">{{ $errors->first('poly_qty') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="cartoon_qty">Cartoon Qty</label>
                                {!! Form::number('cartoon_qty', null, ['class' => 'form-control form-control-sm', 'id' => 'cartoon_qty', 'placeholder' => 'Give an cartoon qty here']) !!}

                                @if($errors->has('cartoon_qty'))
                                    <span class="text-danger">{{ $errors->first('cartoon_qty') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="floor">Floor</label>
                                {!! Form::select('floor_id', $floors, null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select a Floor']) !!}

                                @if($errors->has('floor_id'))
                                    <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                                @endif
                            </div>

                            <div class="form-group m-t-md">
                                <button type="submit" class="btn btn-sm white">Submit</button>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
