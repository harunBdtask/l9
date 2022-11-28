<div class="modal fade bd-example-modal-lg" id="cut-plan-modal" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            {!! Form::open(['id' => 'cutting-plan-form']) !!}
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cutting Plan
                    for {{ date('d-m-Y', strtotime($date)) }}</h5>
            </div>
            <div class="modal-body" style="height: 500px; overflow-y: scroll;">
                <div class="form-group">
                    <div class="col-sm-12">
                        <label for="remarks">Plan Description</label>
                        {!! Form::text('remarks', null, ['class' => 'form-control form-control-sm', 'id' => 'remarks', 'placeholder' => 'Plan description', 'required' => true]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label for="cutting_table_id">Cutting Table</label>
                        {!! Form::select('cutting_table_id', [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'cutting_table_id', 'placeholder' => 'Select Cutting Table', 'required' => true]) !!}
                    </div>
                    <div class="col-sm-6">
                        <label for="buyer_id">Buyer</label>
                        {!! Form::select('buyer_id', $buyers ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'buyer_id', 'placeholder' => 'Select Buyer', 'required' => true]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label for="order_id">Style</label>
                        {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'order_id', 'placeholder' => 'Select Style', 'required' => true]) !!}
                    </div>
                    <div class="col-sm-6">
                        <label for="purchase_order_id">Purchase Order</label>
                        {!! Form::select('purchase_order_id', [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'purchase_order_id', 'placeholder' => 'Select Purchase Order', 'required' => true]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label for="color_id">Color</label>
                        {!! Form::select('color_id', [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'color_id', 'placeholder' => 'Select Color', 'required' => true]) !!}
                    </div>
                    <div class="col-sm-6">
                        <label for="cutting_delivery_date">Delivery Date</label>
                        {!! Form::date('cutting_delivery_date', null, ['class' => 'form-control form-control-sm', 'id' => 'cutting_delivery_date', 'required' => true]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label for="plan_qty">Plan Qty</label>
                        {!! Form::text('plan_qty', null, ['class' => 'form-control form-control-sm', 'id' => 'plan_qty', 'placeholder' => 'Plan Qty', 'required' => true]) !!}
                    </div>
                    <div class="col-sm-6">
                        <label for="no_of_marker">No Of Marker</label>
                        {!! Form::text('no_of_marker', null, ['class' => 'form-control form-control-sm', 'id' => 'no_of_marker', 'placeholder' => 'No of marker']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label for="rating">Rating(%)</label>
                        {!! Form::text('rating', null, ['class' => 'form-control form-control-sm', 'id' => 'rating', 'placeholder' => 'Rating']) !!}
                    </div>
                    <div class="col-sm-6">
                        <label for="smv">SMV</label>
                        {!! Form::text('smv', null, ['class' => 'form-control form-control-sm', 'id' => 'smv', 'placeholder' => 'SMV']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label for="start_time">Start Time (24H)</label>
                        {!! Form::text('start_time', null, ['class' => 'form-control form-control-sm timepicker', 'id' => 'start_time', 'required' => true]) !!}
                    </div>
                    <div class="col-sm-6">
                        <label for="end_time">End Time (24H)</label>
                        {!! Form::text('end_time', null, ['class' => 'form-control form-control-sm timepicker', 'id' => 'end_time', 'required' => true]) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                    <button type="button" class="btn btn-sm white m-b plan_cancel_btn" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary plan_save_btn">Save</button>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-danger plan_delete_btn">Delete</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
