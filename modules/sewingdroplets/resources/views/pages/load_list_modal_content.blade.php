<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">Load List</h5>
</div>
<div class="modal-body">
    <div class="load-list-flash-message">
    </div>
    {!! Form::open(['url' => '/generate-load-list', 'method' => 'POST', 'id' => 'loadListForm']) !!}
    <div class="form-group">
        <div class="row">
            <div class="col-sm-3">
                <label>Buyer</label>
                {!! Form::select('buyer_id', $buyers ?? [], null, ['class' => 'form-control form-control-sm', 'id' => 'load-list-buyer-id', 'placeholder' => 'Select Buyer']) !!}
                <span class="text-danger buyer_id"></span>
            </div>
            <div class="col-sm-3">
                <label>Style/Order</label>
                {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Style/Order', 'id' => 'load-list-order-id']) !!}
                <span class="text-danger order_id"></span>
            </div>
            <div class="col-sm-3">
                <label>Garments Item</label>
                {!! Form::select('garments_item_id', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Item', 'id' => 'load-list-garments-item-id']) !!}
                <span class="text-danger garments_item_id"></span>
            </div>
            <div class="col-sm-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary" style="margin-top: 25px;">Search</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="row loadListTable">
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
</div>
