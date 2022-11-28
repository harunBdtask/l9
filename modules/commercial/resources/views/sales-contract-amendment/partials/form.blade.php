<div class="row">
    <div class="col-md-12">
        <div class="row table-responsive" id="amendment-form">
            <div class="col-md-6 col-sm-12" style="border-right: 1px solid #D1C4E9">
                @include("commercial::sales-contract-amendment.partials.sales-contract")
            </div>
            <div class="col-md-6 col-sm-12">
                @include("commercial::sales-contract-amendment.partials.amendment-form")
            </div>
        </div>
    </div>
</div>

@if(isset($contract) && $contract)
    @include('commercial::sales-contract.partials.details')
@endif
