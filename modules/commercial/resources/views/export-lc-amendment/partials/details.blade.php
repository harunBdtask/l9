<div class="row">
    <div class="col-md-12">

    </div>
</div>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="selectOrder">Style</label>
                    {{ Form::select('selectedStyle', [], null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'selectedStyle']) }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="selectOrder">Purchase Order</label>
                    {{ Form::select('selectedPurchaseOrder', [], null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'selectedPurchaseOrder']) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" >
    <div class="col-md-10 col-md-offset-1" id="detail-select">
    </div>
</div>

<div class="row" >

    {!! Form::hidden('contract_id', $contract->id) !!}

    {!! Form::open(['url' => 'commercial/export-lc-details/' . $contract->id , 'id' => 'detail-form', 'method' => 'post']) !!}
    <div class="col-md-10 col-md-offset-1" id="detail-form-view">

    </div>
    {!! Form::close() !!}
</div>


<div class="row" >
    <div class="col-md-10 col-md-offset-1" id="detail-list">

    </div>
</div>
