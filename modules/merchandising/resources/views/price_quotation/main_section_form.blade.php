@extends('merchandising::form_layout')
@section("title","Price Quotation")
@section('styles')
    <style>
        .reportEntryTable {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
        }

        .reportEntryTable thead,
        .reportEntryTable tbody,
        .reportEntryTable th {
            padding: 3px;
            font-size: 12px;
            text-align: center;
        }

        .reportEntryTable th,
        .reportEntryTable td {
            border: 1px solid transparent;
        }

        .reportTable th,
        .reportTable td {
            border: 1px solid #6887ff !important;
        }

        input {
            font-size: 12px !important;
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100vh;
            background: #e3dadabf url('/SLS_LOADER.GIF') no-repeat center;
            z-index: 999;
        }

        .spin-loader {
            position: relative;
            top: 46%;
            left: 5%;
        }

        .form-buttons {
            position: fixed;
            z-index: 2000;
            top: 10px;
            right: 10px;
        }

        .head-banner {
            color: #0275d8;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">

            <div class="box-header">
                <h2>{{ $price_quotation ? 'Price Quotation Update' : 'Price Quotation Entry' }}</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body b-t">
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
                {!! Form::model($price_quotation, ['url' => $price_quotation ? 'price-quotations/'.$price_quotation->id :
                'price-quotations', 'method' => $price_quotation ? 'PUT' : 'POST', 'files' => true, 'id' =>
                'quotation-inquiry-form']) !!}
                <input type="hidden" name="is_approve"
                       value="{{ isset($price_quotation) ? $price_quotation->is_approve : 0 }}"></input>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quotation_id">Quotation Id</label>
                                    {!! Form::text('quotation_id', $quotation_id ?? null, ['class' => 'form-control form-control-sm', 'id' =>
                                    'quotation_id', 'readonly' => true]) !!}
                                    @if($errors->has('quotation_id'))
                                        <span class="text-danger small">{{ $errors->first('quotation_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quotation_inquiry_id">Inquiry Id</label>
                                    {!! Form::select('quotation_inquiry_id', $quotation_inquiries ?? [], null, ['class' => 'form-control form-control-sm
                                    select2-input', 'id' => 'quotation_inquiry_id', 'placeholder' => "Select Inquiry Id",
                                    'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('quotation_inquiry_id'))
                                        <span
                                            class="text-danger small">{{ $errors->first('quotation_inquiry_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="revised_no">Revised No</label>
                                    {!! Form::text('revised_no', null, ['class' => 'form-control form-control-sm', 'id' => 'revised_no', 'placeholder' =>
                                    "Revised No",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('revised_no'))
                                        <span class="text-danger small">{{ $errors->first('revised_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="factory_id">Company Name</label>
                                    {!! Form::select('factory_id', $factories ?? [], factoryId() ?? null, ['class' => 'form-control form-control-sm
                                    select2-input', 'id' => 'factory_id', 'placeholder' => "Select Factory",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('factory_id'))
                                        <span class="text-danger small">{{ $errors->first('factory_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    {!! Form::text('location', null, ['class' => 'form-control form-control-sm', 'id' => 'location', 'placeholder' =>
                                    "Location",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('location'))
                                        <span class="text-danger small">{{ $errors->first('location') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="buyer_id">Buyer<span
                                            class="text-danger">*</span></label>
                                    {!! Form::select('buyer_id', $buyers ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' =>
                                    'buyer_id', 'placeholder' => "Select Buyer",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('buyer_id'))
                                        <span class="text-danger small">{{ $errors->first('buyer_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="product_department_id">Product
                                        Department<span class="text-danger">*</span></label>
                                    {!! Form::select('product_department_id', $product_departments ?? [], null, ['class' => 'form-control form-control-sm
                                    select2-input', 'id' =>'product_department_id','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('product_department_id'))
                                        <span
                                            class="text-danger small">{{ $errors->first('product_department_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="style_name">
                                        {{ localizedFor('Style') }}
                                        <span class="text-danger req">*</span>
                                        <span class="text-danger" style="font-size: 10px;">
                                                 [N:B:|&;$%@"<>+,./ Not Allowed]
                                        </span>
                                        {!! Form::text('style_name', null, [
                                        'class' => 'form-control form-control-sm',
                                         'id' => 'style_name',
                                         'placeholder' => "Write Style no",
                                         'style' => 'margin-top: 4%;',
                                         'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                        @if($errors->has('style_name'))
                                            <span class="text-danger small">{{ $errors->first('style_name') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="style_desc">Style Desc</label>
                                    {!! Form::text('style_desc', null, ['class' => 'form-control form-control-sm', 'id' => 'style_desc', 'placeholder' =>
                                    "Write here",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('style_desc'))
                                        <span class="text-danger small">{{ $errors->first('style_desc') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="offer_qty">Offer Qty</label>
                                    {!! Form::text('offer_qty', null, ['class' => 'form-control form-control-sm', 'id' => 'offer_qty', 'placeholder' =>
                                    "Write here",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('offer_qty'))
                                        <span class="text-danger small">{{ $errors->first('offer_qty') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="season_id">Season<span
                                            class="text-danger">*</span></label>
                                    {!! Form::select('season_id', $seasons ?? [], isset($price_quotation) ? $price_quotation->season_id : null,
                                    ['class' => 'form-control form-control-sm select2-input',
                                     'id' => 'season_id',
                                     'placeholder' => "Select Season",
                                     'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    {{--                                    {!! Form::select('season_id', $seasons ?? [], $price_quotation->season_id ?? null, ['class' => 'form-control form-control-sm select2-input', 'id' =>--}}
                                    {{--                                    'season_id', 'placeholder' => "Select Season",'disabled'=> disableFieldForApproval($price_quotation)]) !!}--}}
                                    @if($errors->has('season_id'))
                                        <span class="text-danger small">{{ $errors->first('season_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="season_grp">Size Group</label>
                                    {!! Form::text('season_grp',null, ['class' => 'form-control form-control-sm select2-input', 'id' =>
                                    'season_grp', 'placeholder' => "Season Grp.",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('season_grp'))
                                        <span class="text-danger small">{{ $errors->first('season_grp') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="style_uom">Style UOM<span
                                            class="text-danger">*</span></label>
                                    {!! Form::select('style_uom', $style_uoms ?? [], null, ['class' => 'form-control form-control-sm
                                    select2-input', 'id' => 'style_uom','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('style_uom'))
                                        <span class="text-danger small">{{ $errors->first('style_uom') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="costing_per">Costing Per<span
                                            class="text-danger">*</span></label>
                                    {!! Form::select('costing_per', $costing_per_vals ?? [], null, ['class' => 'form-control form-control-sm select2-input',
                                    'id' =>
                                    'costing_per','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('costing_per'))
                                        <span class="text-danger small">{{ $errors->first('costing_per') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="buying_agent_id">Agent</label>
                                    {!! Form::select('buying_agent_id', $buying_agents ?? [], null, ['class' => 'form-control form-control-sm
                                    select2-input', 'id' =>
                                    'buying_agent_id', 'placeholder' => 'Select Agent','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('buying_agent_id'))
                                        <span class="text-danger small">{{ $errors->first('buying_agent_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="bh_merchant">BH Merchant</label>
                                    {!! Form::select('bh_merchant', $buying_agent_merchant ?? [], null, ['class' => 'form-control form-control-sm
                                    select2-input', 'id' => 'bh_merchant', 'placeholder' => 'Select here','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('bh_merchant'))
                                        <span class="text-danger small">{{ $errors->first('bh_merchant') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="region">Region</label>
                                    {!! Form::select('region', $regions ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' =>
                                    'region', 'placeholder' => 'Select Region','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('region'))
                                        <span class="text-danger small">{{ $errors->first('region') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="currency_id">Currency</label>
                                    {!! Form::select('currency_id', $currencies ?? [], 2, ['class' => 'form-control form-control-sm select2-input', 'id'
                                    =>'currency_id', 'placeholder' => 'Select Currency','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('currency_id'))
                                        <span class="text-danger small">{{ $errors->first('currency_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="er">E.R</label>
                                    {!! Form::text('er', null, ['class' => 'form-control form-control-sm', 'id' => 'er', 'placeholder' =>
                                    "Write here",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('er'))
                                        <span class="text-danger small">{{ $errors->first('er') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="incoterm_id">Incoterm</label>
                                    {!! Form::select('incoterm_id', $incoterms ?? [], 1, ['class' => 'form-control form-control-sm select2-input', 'id'
                                    =>'incoterm_id', 'placeholder' => 'Select Incoterm','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('incoterm_id'))
                                        <span class="text-danger small">{{ $errors->first('incoterm_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="incoterm_place">Incoterm Place</label>
                                    {!! Form::text('incoterm_place', null, ['class' => 'form-control form-control-sm', 'id'
                                    =>'incoterm_place', 'placeholder' => 'Select Incoterm','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('incoterm_place'))
                                        <span class="text-danger small">{{ $errors->first('incoterm_place') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="machine_line">Machine/Line</label>
                                    {!! Form::number('machine_line', null, ['class' => 'form-control form-control-sm', 'id'
                                    =>'machine_line', 'placeholder' => 'Write here','step'=>".0001",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('machine_line'))
                                        <span class="text-danger small">{{ $errors->first('machine_line') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quotation_date">Quot. Date</label>
                                    {!! Form::text('quotation_date', isset($quot_date) ? date('d-m-Y', strtotime($quot_date)) : null, ['class' => 'form-control form-control-sm datepicker', 'id' =>
                                    'quotation_date', 'placeholder'
                                    => "dd/mm/yyyy",'disabled'=> disableFieldForApproval($price_quotation),'autocomplete'=>'off']) !!}
                                    @if($errors->has('quotation_date'))
                                        <span class="text-danger small">{{ $errors->first('quotation_date') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="op_date">OP Date</label>
                                    {!! Form::text('op_date', isset($price_quotation->op_date) ? date('d-m-Y', strtotime($price_quotation->op_date)) : date('d-m-Y', strtotime(now())), ['class' => 'form-control form-control-sm datepicker', 'id' => 'op_date', 'placeholder'
                                    => "dd/mm/yyyy",'disabled'=> disableFieldForApproval($price_quotation),'autocomplete'=>'off']) !!}
                                    @if($errors->has('op_date'))
                                        <span class="text-danger small">{{ $errors->first('op_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="est_shipment_date">Est. Shipment Date</label>
                                    {!! Form::text('est_shipment_date', isset($price_quotation->est_shipment_date) ? date('d-m-Y', strtotime($price_quotation->est_shipment_date)) : date('d-m-Y', strtotime(now())), ['class' => 'form-control form-control-sm datepicker', 'id' => 'est_shipment_date',
                                    'placeholder' => "dd/mm/yyyy",'disabled'=> disableFieldForApproval($price_quotation),'autocomplete'=>'off']) !!}
                                    @if($errors->has('est_shipment_date'))
                                        <span class="text-danger small">{{ $errors->first('est_shipment_date') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="color_range_id">Days &amp; Weeks</label>
                                    {!! Form::text('date_diff',$price_quotation->date_diff ?? null,['class'=>'form-control form-control-sm date_diff','readonly'=>true]) !!}
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="color_range_id">Color Range</label>
                                    {!! Form::select('color_range_id', $color_ranges ?? [], null, ['class' => 'form-control form-control-sm select2-input',
                                    'id'
                                    =>
                                    'color_range_id', 'placeholder' => 'Select Color Range','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('color_range_id'))
                                        <span class="text-danger small">{{ $errors->first('color_range_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="prod_line_hr">Prod/Line/Hr</label>
                                    {!! Form::number('prod_line_hr', null, ['class' => 'form-control form-control-sm', 'id'
                                    =>'prod_line_hr', 'placeholder' => 'Write here','step'=>".0001",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('prod_line_hr'))
                                        <span class="text-danger small">{{ $errors->first('prod_line_hr') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sew_smv">Sew. SMV</label>
                                    {!! Form::number('sew_smv', null, ['class' => 'form-control form-control-sm', 'id'
                                    =>'sew_smv', 'placeholder' => 'Write here','step'=>".0001",'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('sew_smv'))
                                        <span class="text-danger small">{{ $errors->first('sew_smv') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sew_eff">Sew. Eff. &#37;</label>
                                    {!! Form::number('sew_eff', null, ['class' => 'form-control form-control-sm', 'id'
                                    =>'sew_eff', 'placeholder' => 'Write here','step'=>'.0001','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('sew_eff'))
                                        <span class="text-danger small">{{ $errors->first('sew_eff') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cut_smv">Cut. SMV</label>
                                    {!! Form::number('cut_smv', null, ['class' => 'form-control form-control-sm', 'id'
                                    =>'cut_smv', 'placeholder' => 'Write here','step'=>'.0001','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('cut_smv'))
                                        <span class="text-danger small">{{ $errors->first('cut_smv') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cut_eff">Cut. Eff. &#37;</label>
                                    {!! Form::number('cut_eff', null, ['class' => 'form-control form-control-sm', 'id'
                                    =>'cut_eff', 'placeholder' => 'Write here','step'=>'.0001','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('cut_eff'))
                                        <span class="text-danger small">{{ $errors->first('cut_eff') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fin_smv">Fin. SMV</label>
                                    {!! Form::number('fin_smv', null, ['class' => 'form-control form-control-sm', 'id'
                                    =>'fin_smv', 'placeholder' => 'Write here','step'=>'.0001','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('fin_smv'))
                                        <span class="text-danger small">{{ $errors->first('fin_smv') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fin_eff">Fin. Eff. &#37;</label>
                                    {!! Form::number('fin_eff', null, ['class' => 'form-control form-control-sm', 'id'
                                    =>'fin_eff', 'placeholder' => 'Write here','step'=>'.0001','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('fin_eff'))
                                        <span class="text-danger small">{{ $errors->first('fin_eff') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cut_eff">PCS/CARTON</label>
                                    {!! Form::text('pcs_per_carton', isset($price_quotation) ? $price_quotation->styleEntry->pcs_per_carton : null,
                                    [
                                        'class' => 'form-control form-control-sm',
                                        'id' =>'pcs_per_carton',
                                         'placeholder' => 'Write here',
                                         'disabled'=> disableFieldForApproval($price_quotation)
                                    ]) !!}
                                    @if($errors->has('pcs_per_carton'))
                                        <span class="text-danger small">{{ $errors->first('pcs_per_carton') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cut_eff">CBM/CARTOON</label>
                                    {!! Form::text('cbm_per_carton', isset($price_quotation) ? $price_quotation->styleEntry->cbm_per_carton : null ,
                                    [
                                        'class' => 'form-control form-control-sm',
                                         'id'=>'cbm_per_carton',
                                         'placeholder' => 'Write here',
                                         'disabled'=> disableFieldForApproval($price_quotation)
                                    ]) !!}
                                    @if($errors->has('cbm_per_carton'))
                                        <span class="text-danger small">{{ $errors->first('cbm_per_carton') }}</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ready_to_approve">Ready to Approve</label>
                                    {!! Form::select('ready_to_approve', $ready_to_approve_status ?? [], null, ['class' => 'form-control form-control-sm
                                    select2-input', 'id' => 'ready_to_approve', 'placeholder' => 'Select here','disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    @if($errors->has('ready_to_approve'))
                                        <span class="text-danger small">{{ $errors->first('ready_to_approve') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="file">Files</label>
                                    <br>
                                    <button type="button"
                                            class="btn btn-xs btn-primary"
                                            data-toggle="modal"
                                            data-target="#fileModal"
                                            style="padding: 2px 7px; font-size: 12px;">
                                        Upload Files
                                    </button>
                                    @if($errors->has('files.*'))
                                        <p class="text-danger small">{{ $errors->first('files.*') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-left: 75px;">
                                <div class="form-group">
                                    <label for="image">Image</label><br>
                                    <button type="button"
                                            class="btn btn-xs btn-primary"
                                            data-toggle="modal"
                                            data-target="#imagePreviewModal"
                                            style="padding: 2px 7px; font-size: 12px;">
                                        Upload Image
                                    </button>
                                    @if($errors->has('image'))
                                        <span class="text-danger small">{{ $errors->first('image') }}</span>
                                    @endif

                                    <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog"
                                         aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close btn btn-danger btn-sm "
                                                            data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h5 class="modal-title" id="imagePreviewModalLabel">Upload</h5>
                                                </div>
                                                <div class="modal-body">
                                                    @if(isset($price_quotation) &&
                                                        Storage::disk('public')->exists('/price_quotation_images/' . $price_quotation->image))
                                                        <img class="img-preview"
                                                             width="30%"
                                                             style="border-radius: 5%;"
                                                             src="{{ asset('storage/price_quotation_images/'. $price_quotation->image) }}"
                                                             alt="Image">
                                                    @else
                                                        <img class="img-preview"
                                                             width="30%"
                                                             style="border-radius: 5%;"
                                                             src="{{ asset('/flatkit/assets/images/avatar.png') }}"
                                                             alt="Image">
                                                    @endif
                                                    <div class="form-group">
                                                        {!! Form::file('image',['class' => 'image', 'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button id="add_image" data-type="image"
                                                            class="btn btn-xs btn-info"
                                                            data-dismiss="modal"
                                                            type="button">
                                                        Save
                                                    </button>
                                                    <button id="remove_image" data-type="image"
                                                            class="btn btn-xs btn-danger delete_image"
                                                            @if ( disableFieldForApproval($price_quotation))
                                                                disabled
                                                            @endif
                                                            type="button">
                                                        <!-- <i class="fa fa-trash"></i> -->
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--MULTI ATTACHMENT MODAL--}}
                                    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog"
                                         aria-labelledby="fileModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close btn btn-danger btn-sm "
                                                            data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h5 class="modal-title" id="imagePreviewModalLabel">Upload</h5>
                                                </div>

                                                <div class="modal-body">
                                                    @if(isset($price_quotation->attachments))
                                                        <div class="row">
                                                            @foreach($price_quotation->attachments as $attachment)
                                                                <div class="col-md-4"
                                                                     id="attachment-{{ $attachment->id }}">
                                                                    <div class="card text-center"
                                                                         style="height: 70px;
                                                                         align-items: center;
                                                                         display: flex;
                                                                         justify-content: center;
                                                                         ">
                                                                        <a
                                                                            target="_blank"
                                                                            href="/price-quotations/{{ $attachment->price_quotation_id }}/attachment/{{ $attachment->id }}"
                                                                            style="color: #0ab4e6">
                                                                            <i class="fa fa-link"></i> {{ $attachment->name }}
                                                                        </a>
                                                                        <button style="margin: 5px;text-decoration: none;border: none;background: none" type="button"
                                                                                class="text-danger"
                                                                                onclick="deleteAttachment({{ $attachment->price_quotation_id }} ,{{ $attachment->id }})">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    <div class="row" id="file-multi-rows">
                                                        <div id="row_0">
                                                            <div class="form-group col-md-10">
                                                                <input type="file" name="files[]"
                                                                       class="form-control form-control-sm">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button class="btn btn-primary btn-sm"
                                                                        onclick="addFileRow()" type="button">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button id="add_image" data-type="image"
                                                            class="btn btn-xs btn-info"
                                                            data-dismiss="modal"
                                                            type="button">
                                                        Update
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    {{--MULTI ATTACHMENT MODAL--}}
                                </div>
                            </div>
                        </div>
                        <div class="row">


                            {{--                            @if ($price_quotation && $price_quotation->file)--}}
                            {{--                                <div class="col-md-3">--}}
                            {{--                                    <div class="form-group">--}}
                            {{--                                        <label for="remove_file" >Remove File</label>--}}
                            {{--                                        {!! Form::checkbox('remove_file') !!}--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}
                            {{--                            @if ($price_quotation && $price_quotation->image != null)--}}
                            {{--                                <div class="col-md-3">--}}
                            {{--                                    <div class="form-group">--}}
                            {{--                                        <label for="remove_image" >Remove Image</label>--}}
                            {{--                                        {!! Form::checkbox('remove_image') !!}--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    {!! Form::textarea('remarks', null, ['class' => 'form-control form-control-sm', 'id' => 'remarks', 'rows' => 2,'disabled'=> disableFieldForApproval($price_quotation)]) !!}
                                    <span class="text-danger small remarks"></span>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                            @if($price_quotation && $price_quotation->step >  0)
                                <div class="col-md-12">

                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="modal"
                                            data-target="#exampleModalCenter">
                                        Unapproved Request
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <label for="unapproved_request">Unapproved
                                                        Request</label>
                                                    {!! Form::textarea('unapproved_request', null, ['class' => 'form-control form-control-sm', 'id' => 'unapproved_request', 'rows' => 1]) !!}
                                                    <span class="text-danger small unapproved_request"></span>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm white m-b"
                                                            data-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                                                            id="save_unapproved_request">Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- End Modal -->
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="reportEntryTable">
                                    <thead>
                                    <tr>
                                        <td class="width-40p" style="padding: 6px;">
                                            <span>Item</span>
                                            <button type="button" class="btn btn-xs btn-primary" data-toggle="modal"
                                                    data-target="#itemModal">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <div class="col-md-12">

                                                <!-- Modal -->
                                                <div class="modal" id="itemModal" tabindex="-1" role="dialog"
                                                     aria-labelledby="itemModalTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="box-body">
                                                                    {{--                                                                    {!! Form::model(['url' => 'garments-items', 'method' => 'POST']) !!}--}}
                                                                    {{--                                                                    {!! Form::open(['id' => 'itemModal']) !!}--}}

                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="name">Garments Item</label>

                                                                                {!! Form::text('name', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'name', 'placeholder' => 'Garments Item']) !!}
                                                                                <span class="text-danger"
                                                                                      id="nameError"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="name">Commercial
                                                                                    Name</label>

                                                                                {!! Form::text('commercial_name', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'commercial_name', 'placeholder' => 'Commercial Name']) !!}

                                                                                <span class="text-danger"
                                                                                      id="commercial_nameError"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12 col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="product_category_id">Product
                                                                                    Category</label>
                                                                                {!! Form::select('product_category_id', $productCategories, null, ['class' => 'form-control form-control-sm', 'id' => 'product_category_id', 'placeholder' => 'Product Category']) !!}

                                                                                <span class="text-danger"
                                                                                      id="product_category_idError"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12 col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="product_type">Product
                                                                                    Type</label>
                                                                                {!! Form::select('product_type', ['Top' => 'Top', 'Bottom' => 'Bottom'], null, ['class' => 'form-control form-control-sm', 'id' => 'product_type', 'placeholder' => 'Product Type']) !!}

                                                                                <span class="text-danger"
                                                                                      id="product_typeError"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="standard_smv">Standard
                                                                                    SMV</label>

                                                                                {!! Form::text('standard_smv', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'standard_smv', 'placeholder' => 'Standard SMV']) !!}

                                                                                @if($errors->has('standard_smv'))
                                                                                    <span
                                                                                        class="text-danger">{{ $errors->first('standard_smv') }}</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="efficiency">Efficiency
                                                                                    %</label>

                                                                                {!! Form::number('efficiency', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'efficiency', 'placeholder' => 'Efficiency %', 'step' => '.1']) !!}

                                                                                @if($errors->has('efficiency'))
                                                                                    <span
                                                                                        class="text-danger">{{ $errors->first('efficiency') }}</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-4">
                                                                            <div class="form-group">
                                                                                <label for="status">Status</label>
                                                                                {!! Form::select('status', ['Active' => 'Active', 'In Active' => 'In Active'], null, ['class' => 'form-control form-control-sm', 'id' => 'status']) !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12 col-md-12">
                                                                            <div class="form-group">
                                                                                <div class="text-right">
                                                                                    <a class="btn btn-sm btn-danger"
                                                                                       data-dismiss="modal">
                                                                                        <i class="fa fa-remove"></i>
                                                                                        Cancel
                                                                                    </a>
                                                                                    <button
                                                                                        {{--                                                                                            data-dismiss="modal"--}}
                                                                                        class="btn btn-sm btn-success"
                                                                                        id="saveItem"
                                                                                    >
                                                                                        <i class="fa fa-save"></i>
                                                                                        Create
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    {{--                                                                    {!! Form::close() !!}--}}
                                                                </div>
                                                            </div>
                                                            {{--                <div class="modal-footer">--}}
                                                            {{--                    <button type="button" class="btn btn-sm white m-b"--}}
                                                            {{--                            data-dismiss="modal">--}}
                                                            {{--                        Close--}}
                                                            {{--                    </button>--}}
                                                            {{--                    <button type="button" class="btn btn-primary"--}}
                                                            {{--                            data-dismiss="modal"--}}
                                                            {{--                            id="item_save">Save--}}
                                                            {{--                    </button>--}}
                                                            {{--                </div>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--                                            @include('merchandising::price_quotation.item_modal')--}}
                                        </td>
                                        <td class="width-20p">Set Item Ratio</td>
                                        <td class="width-20p">SMV</td>
                                        <td>Action</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            {!! Form::select('garment_item_val', $garment_items ?? [], null, ['class' => 'form-control form-control-sm select2-input garment_item_val', 'id' => 'garment_item_val', 'placeholder' => 'Select item']) !!}
                                            @if($errors->has('garment_item_val'))
                                                <span
                                                    class="text-danger small">{{ $errors->first('garment_item_val') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! Form::text('item_ratio_val', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Write
                                            ratio']) !!}
                                            @if($errors->has('item_ratio_val'))
                                                <span
                                                    class="text-danger small">{{ $errors->first('item_ratio_val') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! Form::text('smv_val', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Write
                                            smv']) !!}
                                            @if($errors->has('smv_val'))
                                                <span class="text-danger small">{{ $errors->first('smv_val') }}</span>
                                            @endif
                                        </td>
                                        <td style="padding: 4px;">
                                            <button
                                                style="{{ disableFieldForApproval($price_quotation) ? 'display:none;' : ''}}"
                                                type="button" class="btn btn-xs btn-success add-row" title="Add"><i
                                                    class="fa fa-plus"></i></button>
                                            <button
                                                style="{{ disableFieldForApproval($price_quotation) ? 'display:none;' : ''}}"
                                                type="button" class="btn btn-xs btn-success update-row"
                                                title="Update"><i
                                                    class="fa fa-refresh"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 table-responsive child-entry-section" style="margin-top:6px">
                                <table class="reportTable">
                                    <thead>
                                    <tr class="info">
                                        <td class="width-25p" style="padding: 6px;">Item</td>
                                        <td class="width-25p">Set Item Ratio</td>
                                        <td class="width-25p">SMV</td>
                                        <td class="width-25p">Action</td>
                                    </tr>
                                    </thead>
                                    <tbody class="child-entry-table-body">

                                    @if(old('garment_item_id'))
                                        @foreach(old('garment_item_id') as $key => $garment_item_id)
                                            <tr>
                                                <td>
                                                    {{ $garment_items[$garment_item_id] }}
                                                    {!! Form::hidden('garment_item_id[]', $garment_item_id) !!}
                                                </td>
                                                <td>
                                                    {{ old('item_ratio')[$key] }}
                                                    {!! Form::hidden('item_ratio[]', old('item_ratio')[$key]) !!}
                                                </td>
                                                <td>
                                                    {{ old('smv')[$key] }}
                                                    {!! Form::hidden('smv[]', old('item_ratio')[$key]) !!}
                                                </td>
                                                <td style="padding: 4px;">
                                                    {{-- <button type="button" class="btn btn-xs btn-success edit-row"
                                                            title="Remove"><i
                                                            class="fa fa-edit"></i></button> --}}
                                                    <button
                                                        style="{{ disableFieldForApproval($price_quotation) ? 'display:none;' : ''}}"
                                                        type="button" class="btn btn-xs btn-danger remove-row"
                                                        title="Remove"><i
                                                            class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @elseif($price_quotation && $price_quotation->item_details && count($price_quotation->item_details))
                                        @foreach($price_quotation->item_details as $key => $item_detail)
                                            @php
                                                if(array_key_exists('total_ratio', $item_detail)) {
                                                    continue;
                                                }
                                            @endphp
                                            <tr class="item-details">
                                                <td>
                                                    {{ $garment_items[$item_detail['garment_item_id']] }}
                                                    {!! Form::hidden('garment_item_id[]', $item_detail['garment_item_id']) !!}
                                                    {!! Form::hidden('key_id', $key) !!}
                                                </td>
                                                <td>
                                                    {{ $item_detail['item_ratio'] }}
                                                    {!! Form::hidden('item_ratio[]', $item_detail['item_ratio']) !!}
                                                </td>
                                                <td>
                                                    {{ $item_detail['smv'] }}
                                                    {!! Form::hidden('smv[]', $item_detail['smv']) !!}
                                                    {!! Form::hidden('smv_given[]', $item_detail['smv_given'] ?? '') !!}
                                                </td>
                                                <td style="padding: 4px;">
                                                    <button
                                                        style="{{ disableFieldForApproval($price_quotation) ? 'display:none;' : ''}}"
                                                        type="button" class="btn btn-xs btn-success edit-row"
                                                        title="Remove"><i
                                                            class="fa fa-edit"></i></button>
                                                    <button
                                                        style="{{isset($price_quotation) && $price_quotation->step > 0 ? 'display:none;' : ''}}"
                                                        type="button" class="btn btn-xs btn-danger remove-row"
                                                        title="Remove"><i
                                                            class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        @if(old('total_ratio'))
                                            <th>

                                                <span id="total_ratio">{{ old('total_ratio') }}</span>
                                                <input type="text" hidden name="total_ratio"
                                                       value="{{ old('total_ratio') }}">
                                                {{--                                                {!! Form::hidden("total_ratio", old('total_ratio')) !!}--}}
                                            </th>
                                            <th>
                                                <span id="total_smv">{{ old('total_smv') }}</span>
                                                <input type="text" hidden name="total_smv"
                                                       value="{{ old('total_smv') }}">
                                                {{--                                                {!! Form::hidden("total_smv", old('total_smv')) !!}--}}
                                            </th>
                                        @elseif($price_quotation && $price_quotation->item_details && count($price_quotation->item_details))
                                            @php
                                                $total_ratio = collect($price_quotation->item_details)->sum('item_ratio');
                                                $total_smv = collect($price_quotation->item_details)->sum('smv');
////                                            @endphp
                                            {{-- foreach($price_quotation->item_details as $key => $item_detail){
                                                if(array_key_exists('garment_item_id', $item_detail)) {
                                                continue;
                                                }
                                                $total_ratio = $item_detail['total_ratio'];
                                                $total_smv = $item_detail['total_smv'];
                                            } --}}
                                            <th>
                                                <span id="total_ratio">{{ $total_ratio }}</span>
                                                <input type="text" hidden name="total_ratio" value="{{ $total_ratio }}">
                                                {{--                                                {!! Form::text("total_ratio", $total_ratio) !!}--}}
                                            </th>
                                            <th>
                                                <span id="total_smv">{{ $total_smv }}</span>
                                                <input type="text" hidden name="total_smv" value="{{ $total_smv }}">
                                                {{--                                                {!! Form::hidden("total_smv", $total_smv) !!}--}}
                                            </th>
                                        @else
                                            <th>
                                                <span id="total_ratio"></span>
                                                <input type="text" hidden name="total_ratio" value="">
                                                {{--                                                {!! Form::hidden("total_ratio", null) !!}--}}
                                            </th>
                                            <th>
                                                <span id="total_smv"></span>
                                                <input type="text" hidden name="total_smv" value="">
                                            </th>
                                        @endif
                                        <th>&nbsp;</th>
                                    </tr>
                                    </tfoot>
                                </table>

                                @if((isset($price_quotation) && $price_quotation->step >  0))
                                    <small class="text-danger"> [N.B: This Price Quotation is
                                        Approved.@if(!disableFieldForApproval($price_quotation))
                                            &nbsp;But rework mode is enable.
                                        @endif]</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group form-buttons">
                    <div class="text-right">
                        <button type="submit"
                         class="btn btn-sm {{ $price_quotation ? 'btn-primary' : 'btn-success' }}"
                        >
                            <i class="fa fa-save"></i>
                            {{ $price_quotation ? 'Update' : 'Create' }}
                        </button>
                        <a type="button" class="btn btn-sm btn-danger close_btn"><i
                                class="fa fa-remove"></i>
                            Close</a>
                        <button class="btn btn-sm btn-warning"
                                onclick="redirectCostingForm()">
                            <i class="fa fa-database"></i>
                            Costing Section
                        </button>
                        {{--Additional Costing--}}
                        @if($price_quotation)
                            <a class="btn btn-sm btn-info"
                               href="/price-quotations/additional-costing-form?id={{$price_quotation->id}}">
                                <i class="fa fa-dollar"></i> Additional Costing
                            </a>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div id="loader">
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js"></script>
    <script>
        $('.update-row').hide();

        let buyerId = $("#buyer_id").val();
        if (!!buyerId) {
            getBuyer();
        }
        const image_preview = jQuery('.img-preview');

        $(document).on('change', '.image', function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    image_preview.attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
                image_preview.css('display', 'block');
                $(".delete_image").prop('disabled', false);
                ;
            }
        })

        const $flashMessageDom = $('.flash-message');

        $('.close_btn').click(function () {
            if (confirm('Are You sure??')) {
                window.location.replace('{{ url('price-quotations') }}')
            }
        })

        function redirectCostingForm() {
            const form = $('#quotation-inquiry-form')
            form.append(`<input type="hidden" name="costing_section" value="true">`);
        }

        function showLoader() {
            $('#loader').show();
        }

        function hideLoader() {
            $('#loader').hide();
        }

        // Scroll To Top
        function scrollToTop() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function goToListPage() {
            window.location.href = window.location.protocol + "//" + window.location.host + "/price-quotations";
        }

        function deleteAttachment(quotationId, attachmentId) {
            $.ajax({
                type: "GET",
                url: "/price-quotations/" + quotationId + "/attachment/" + attachmentId + "/delete",
            }).done(function (response) {
                if (response.status === 'success') {
                    $('#attachment-' + attachmentId).remove();
                    toastr.success("Delete Successfully");
                }
            }).fail(function (response) {
                console.log("Something went wrong!");
            });
        }

        var total_ratio = $('[name="total_ratio"]').val();
        var total_smv = $('[name="total_smv"]').val();
        $(document).on('keyup', '[name="item_ratio_val"]', function (e) {
            let item_ratio_val = Number($(this).val());
            if (isNaN(item_ratio_val)) {
                alert('Please give a number!');
                $(this).val('');
                return false;
            } else if (item_ratio_val < 0) {
                alert('Please give a positive number!');
                $(this).val('');
                return false;
            }
        });

        $(document).on('keyup', '[name="smv_val"]', function (e) {
            let smv_val = Number($(this).val());
            if (isNaN(smv_val)) {
                alert('Please give a number!');
                $(this).val('');
                return false;
            } else if (smv_val < 0) {
                alert('Please give a positive number!');
                $(this).val('');
                return false;
            }
        });

        $(document).on('click', '.add-row', function (e) {
            e.preventDefault();
            let garment_item_val = $('[name="garment_item_val"]').val();
            let garment_item = $('[name="garment_item_val"] option:selected').text();
            let item_ratio_val = Number($('[name="item_ratio_val"]').val());
            let smv_val = Number($('[name="smv_val"]').val()) * item_ratio_val;
            let child_entry_table_body_dom = $('.child-entry-table-body');
            if (garment_item_val && item_ratio_val && smv_val) {
                total_ratio = Number(total_ratio) + Number(item_ratio_val);
                total_smv = Number(total_smv) + Number(smv_val);
                let newTableRow = '<tr>' +
                    '<td>' + garment_item +
                    '<input name="garment_item_id[]" type="hidden" value="' + garment_item_val + '">' +
                    '<span class="garment_item_id text-danger small"></span>' +
                    '</td>' +
                    '<td>' + item_ratio_val +
                    '<input name="item_ratio[]" type="hidden" value="' + item_ratio_val + '">' +
                    '<span class="item_ratio text-danger small"></span>' +
                    '</td>' +
                    '<td>' + smv_val +
                    '<input name="smv[]" type="hidden" value="' + smv_val + '">' +
                    '<input name="smv_given[]" type="hidden" value="' + Number($('[name="smv_val"]').val()) + '">' +
                    '<span class="smv text-danger small"></span>' +
                    '</td>' +
                    '<td style="padding: 4px;">' +
                    '<button type="button" class="btn btn-xs btn-success edit-row" title="Edit"><i class="fa fa-edit"></i></button>' +
                    '<button style="margin-left: 4px;" type="button" class="btn btn-xs btn-danger remove-row" title="Remove"><i class="fa fa-trash"></i></button>' +
                    '</td>' +
                    '</tr>';
                child_entry_table_body_dom.append(newTableRow);
                $('[name="garment_item_val"]').val('').select2();
                $('[name="item_ratio_val"]').val('');
                $('[name="smv_val"]').val('');
                $('[name="total_ratio"]').val(total_ratio);
                $('span#total_ratio').text(total_ratio);
                $('[name="total_smv"]').val(total_smv);
                $('span#total_smv').text(total_smv);

            } else {
                alert("Please write or select the relevant fields!");
            }
        });

        $(document).on('click', '.remove-row', function (e) {
            e.preventDefault();
            var confirmAction = confirm("Are you sure?");
            let price_quotation_id = "{{$price_quotation->id ?? null }}";
            if (confirmAction) {
                let item_ratio_val = $(this).parents('tr').find('[name="item_ratio[]"]').val();
                let item_id = $(this).parents('tr').find('[name="garment_item_id[]"]').val();
                axios.get("/check_item_in_fabric_and_trim", {
                    params: {
                        item_id: item_id,
                        quotation_id: "{{ request()->query("quotation_id") }}",
                        price_quotation_id: price_quotation_id,
                    }
                })
                    .then(({data}) => {
                        if (data === true) {
                            toastr.warning("Item already exists in Fabric and Trims Costing");
                            return false;
                        } else {
                            let smv_val = $(this).parents('tr').find('[name="smv[]"]').val();
                            console.log(smv_val);
                            total_ratio = Number(total_ratio) - Number(item_ratio_val);
                            total_smv = Number(total_smv) - Number(smv_val);
                            $('[name="total_ratio"]').val(total_ratio);
                            $('span#total_ratio').text(total_ratio);
                            $('[name="total_smv"]').val(total_smv);
                            $('span#total_smv').text(total_smv);
                            $(this).parents('tr').remove();
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })

            }
        });

        var itemTr;
        var itemNumber;
        var oldItemId;
        var oldItemRatio;
        var oldItemSmv;
        $(document).on('click', '.edit-row', function (e) {
            e.preventDefault();
            let item = $(this).parents('tr').find('[name="garment_item_id[]"]').val();
            oldItemId = item;
            let itemRatio = $(this).parents('tr').find('[name="item_ratio[]"]').val();
            oldItemRatio = itemRatio;
            let smv = $(this).parents('tr').find('[name="smv[]"]').val();
            oldItemSmv = smv;
            itemTr = $(this).parents('tr');
            itemNumber = $(this).parents('tr').find('[name="key_id"]').val();
            $('[name="garment_item_val"]').val(item).select2();
            $('[name="item_ratio_val"]').val(itemRatio);
            $('[name="smv_val"]').val(parseInt(smv) / parseInt(itemRatio));
            $('.add-row').hide();
            $('.update-row').show();
        });

        $(".delete_image").click(function () {
            let type = $(this).data("type");
            $(".delete_image").prop('disabled', true);
            image_preview.css('display', 'none');
            $('.image').val('');

            if ("{{ request()->query("quotation_id") }}" == '') {
                return
            }

            if (confirm("Are you sure to delete?")) {
                axios.delete("/delete_price_quotation_files", {
                    params: {
                        quotation_id: "{{ request()->query("quotation_id") }}",
                        type: type
                    }
                }).then((response) => {
                    if (type != "image") {
                        $("#remove_file").hide();
                    }
                }).catch(error => {
                    console.log(error);
                })
            }
        });

        $(document).on('click', '.update-row', function (e) {
            e.preventDefault();
            let price_quotation_id = "{{$price_quotation->id ?? null }}";
            let item_ratio_val = $('[name="item_ratio_val').val();
            let current_item = $('[name="garment_item_val').val();
            let garment_item_val = $('[name="garment_item_val"]').val();
            let garment_item = $('[name="garment_item_val"] option:selected').text();
            let smv_val = Number($('[name="smv_val"]').val()) * item_ratio_val;
            if (garment_item_val && item_ratio_val && smv_val) {
                if (oldItemId !== current_item) {
                    axios.get("/check_item_in_fabric_and_trim", {
                        params: {
                            item_id: oldItemId,
                            quotation_id: "{{ request()->query("quotation_id") }}",
                            price_quotation_id: price_quotation_id,
                        }
                    })
                        .then(({data}) => {
                            if (data === true) {
                                toastr.warning("Item already exists in Fabric and Trims Costing");
                                return false;
                            } else {
                                itemTr.remove();
                                total_ratio = Number(total_ratio) - Number(oldItemRatio);
                                total_ratio = Number(total_ratio) + Number(item_ratio_val);
                                total_smv = Number(total_smv) - Number(oldItemSmv);
                                total_smv = Number(total_smv) + Number(smv_val);
                                let child_entry_table_body_dom = $('.child-entry-table-body');
                                let newTableRow = '<tr>' +
                                    '<td>' + garment_item +
                                    '<input name="garment_item_id[]" type="hidden" value="' + garment_item_val + '">' +
                                    '<span class="garment_item_id text-danger small"></span>' +
                                    '</td>' +
                                    '<td>' + item_ratio_val +
                                    '<input name="item_ratio[]" type="hidden" value="' + item_ratio_val + '">' +
                                    '<span class="item_ratio text-danger small"></span>' +
                                    '</td>' +
                                    '<td>' + smv_val +
                                    '<input name="smv[]" type="hidden" value="' + smv_val + '">' +
                                    '<input name="smv_given[]" type="hidden" value="' + Number($('[name="smv_val"]').val()) + '">' +
                                    '<span class="smv text-danger small"></span>' +
                                    '</td>' +
                                    '<td style="padding: 4px;">' +
                                    '<button type="button" class="btn btn-xs btn-success edit-row" title="Edit"><i class="fa fa-edit"></i></button>' +
                                    '<button style="margin-left: 4px;"  type="button" class="btn btn-xs btn-danger remove-row" title="Remove"><i class="fa fa-trash"></i></button>' +
                                    '</td>' +
                                    '</tr>';
                                child_entry_table_body_dom.append(newTableRow);
                                $('[name="total_ratio"]').val(total_ratio);
                                $('span#total_ratio').text(total_ratio);
                                $('[name="total_smv"]').val(total_smv);
                                $('span#total_smv').text(total_smv);
                                $('[name="total_ratio"]').val(total_ratio);
                                $('span#total_ratio').text(total_ratio);
                                $('[name="total_smv"]').val(total_smv);
                                $('span#total_smv').text(total_smv);
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        })
                } else {
                    itemTr.remove();
                    total_ratio = Number(total_ratio) - Number(oldItemRatio);
                    total_ratio = Number(total_ratio) + Number(item_ratio_val);
                    total_smv = Number(total_smv) - Number(oldItemSmv);
                    total_smv = Number(total_smv) + Number(smv_val);
                    let child_entry_table_body_dom = $('.child-entry-table-body');
                    let newTableRow = '<tr>' +
                        '<td>' + garment_item +
                        '<input name="garment_item_id[]" type="hidden" value="' + garment_item_val + '">' +
                        '<span class="garment_item_id text-danger small"></span>' +
                        '</td>' +
                        '<td>' + item_ratio_val +
                        '<input name="item_ratio[]" type="hidden" value="' + item_ratio_val + '">' +
                        '<span class="item_ratio text-danger small"></span>' +
                        '</td>' +
                        '<td>' + smv_val +
                        '<input name="smv[]" type="hidden" value="' + smv_val + '">' +
                        '<input name="smv_given[]" type="hidden" value="' + Number($('[name="smv_val"]').val()) + '">' +
                        '<span class="smv text-danger small"></span>' +
                        '</td>' +
                        '<td style="padding: 4px;">' +
                        '<button type="button" class="btn btn-xs btn-success edit-row" title="Edit"><i class="fa fa-edit"></i></button>' +
                        '<button style="margin-left: 4px;" type="button" class="btn btn-xs btn-danger remove-row" title="Remove"><i class="fa fa-trash"></i></button>' +
                        '</td>' +
                        '</tr>';
                    child_entry_table_body_dom.append(newTableRow);
                    $('[name="total_ratio"]').val(total_ratio);
                    $('span#total_ratio').text(total_ratio);
                    $('[name="total_smv"]').val(total_smv);
                    $('span#total_smv').text(total_smv);
                    $('[name="total_ratio"]').val(total_ratio);
                    $('span#total_ratio').text(total_ratio);
                    $('[name="total_smv"]').val(total_smv);
                    $('span#total_smv').text(total_smv);
                }
            } else {
                alert("Please write or select the relevant fields!");
            }
            $('[name="garment_item_val"]').val('').select2();
            $('[name="item_ratio_val"]').val('');
            $('[name="smv_val"]').val('');
            $('.add-row').show();
            $('.update-row').hide();
        });

        $(document).on('change', '[name="quotation_inquiry_id"]', function (e) {
            e.preventDefault();
            let quotation_inquiry_id = $(this).val();
            if (quotation_inquiry_id) {
                $.ajax({
                    type: "GET",
                    url: "/get-quotation-inquiry-details",
                    data: {'quotation_inquiry_id': quotation_inquiry_id}
                }).done(function (response) {
                    if (response.status === 'success') {
                        $('[name="factory_id"]').val(response.inquiry_data.factory_id).select2();
                        $('[name="location"]').val(response.inquiry_data.location);
                        $('[name="buyer_id"]').val(response.inquiry_data.buyer_id).select2();
                        $("#buyer_id").change();
                        $('[name="style_name"]').val(response.inquiry_data.style_name);
                        $('[name="style_desc"]').val(response.inquiry_data.style_description);

                    }
                }).fail(function (response) {
                    console.log("Something went wrong!");
                });
            }
        });

        function validateFieldValue(givenVal) {
            if (isNaN(givenVal)) {
                alert('Please give a number!');
                return false;
            }
            if (givenVal < 0) {
                alert('Negative value not allowed!');
                return false;
            }
            return true;
        };
        $(document).on('keyup', '#style_name', function (e) {
            let val = (e.target.value).replace(/[|&;$%@"<>+/'.,]/g, "");
            $('#style_name').val(val)
        })

        $(document).on('change', '#buyer_id', function () {
            getBuyer();
        });
        $(document).on('change', '#buying_agent_id', function () {
            getBuyingAgentMerchant();
        });

        $(document).on('click', '#save_unapproved_request', function () {
            let unapproved_request = $('#unapproved_request').val();
            $flashMessageDom.html('');


            axios.put("/save_unapproved_request_price_quotation", {
                params: {
                    quotation_id: {{  $price_quotation ? $price_quotation->id : 0 }},
                    unapproved_request: unapproved_request
                }
            }).then((response) => {
                console.log(response.status)
                if (response.status == 200) {
                    alert('successfully saved unapproved request');
                    $flashMessageDom.html(response.message);
                    $flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
                }
            }).catch(error => {
                console.log(error);
                $('#unapproved_request').val('')

            })

        });


        $("#op_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $("#quotation_date").datepicker({
            format: 'dd-mm-yyyy'
        });

        $("#est_shipment_date").unbind().change(function () {
            let estShipmentDate = moment($(this).val(), 'DD.MM.YYYY');
            let opDate = moment($("#op_date").val(), 'DD.MM.YYYY');
            let diffInDays = estShipmentDate.diff(opDate, 'days');
            let diffInWeeks = estShipmentDate.diff(opDate, 'weeks');
            if ($(this).val().length === 0 || diffInDays < 0) {
                return false;
            }
            let daysAfterWeeks = (diffInDays % diffInWeeks);
            let daysAfterWeeksFormat = daysAfterWeeks !== 0 ? `${daysAfterWeeks} Days` : '';
            let formatOutput = `${diffInDays} Days (${diffInWeeks} Weeks ${daysAfterWeeksFormat})`;
            $(".date_diff").val('').val(formatOutput);

        }).datepicker({
            format: 'dd-mm-yyyy'
        });

        $(document).on('click', '#saveItem', function () {
            event.preventDefault();

            let name = $('#name').val();
            let commercial_name = $('#commercial_name').val();
            let product_category_id = $('#product_category_id').val();
            let product_type = $('#product_type').val();
            let standard_smv = $('#standard_smv').val();
            let efficiency = $('#efficiency').val();
            let status = $('#status').val();
            $.ajax({
                url: "/garments-items",
                type: "POST",
                data: {
                    name: name,
                    commercial_name: commercial_name,
                    product_category_id: product_category_id,
                    product_type: product_type,
                    standard_smv: standard_smv,
                    efficiency: efficiency,
                    status: status,
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response) {
                        let id = response.id
                        let name = response.name
                        let element = `<option value="${id}">${name}</option>`;
                        $('#garment_item_val').append(element);
                        $('#garment_item_val').val(id).select2();

                        $('#itemModal').modal('hide');
                    }
                },
                error: function (response) {
                    $('#commercial_nameError').text(response.responseJSON.errors.commercial_name);
                    $('#nameError').text(response.responseJSON.errors.name);
                    $('#product_category_idError').text(response.responseJSON.errors.product_category_id);
                    $('#product_typeError').text(response.responseJSON.errors.product_type);

                }
            });


        });

        function getBuyer() {
            let buyerId = $("#buyer_id").val();
            let factoryId = $('#factory_id').val();
            let seasonId = $('#season_id').val();
            let quotation_inquiry_id = $("[name='quotation_inquiry_id']").val();
            // $('#season_id').empty().append(`<option value="">Select Season</option>`).val('').trigger('change');
            $.ajax({
                method: 'get',
                url: `/price-quotations/get-buyer-season/${factoryId}/${buyerId}`,
                success: function (result) {
                    $.each(result, function (key, value) {
                        let element = `<option value="${value.id}">${value.season_name}</option>`;
                        $('#season_id').append(element);
                    })
                    $('#season_id').select2();

                    let isEdit = "{{ isset($price_quotation) ? 1 : 0 }}";
                    console.log({
                        isEdit
                    })
                    if (isEdit === '0') {
                        $.ajax({
                            type: "GET",
                            url: "/get-quotation-inquiry-details",
                            data: {'quotation_inquiry_id': quotation_inquiry_id}
                        }).done(function (response) {
                            $('[name="season_id"]').select2('val', response.inquiry_data.season_id);
                        }).fail(function (response) {
                            console.log("Something went wrong!");
                        });
                    }

                },
                error: function (error) {
                    console.log(error)
                }
            })


        }

        function getBuyingAgentMerchant() {
            let buyingAgentId = $('#buying_agent_id').val();
            let factoryId = $('#factory_id').val();
            $('#bh_merchant').empty().append(`<option value="">Select Buying Agent Merchant</option>`).val('').trigger('change');
            $.ajax({
                method: 'get',
                url: `/price-quotations/get-buying-agent-merchant/${factoryId}/${buyingAgentId}`,
                success: function (result) {
                    $.each(result, function (key, value) {
                        let element = `<option value="${value.id}">${value.buying_agent_merchant_name}</option>`;
                        $('#bh_merchant').append(element);
                    })
                },
                error: function (error) {
                    console.log(error)
                }
            })
        }

        let currentIdx = "{{ isset($price_quotation->attachments) ? $price_quotation->attachments->count() : 0 }}";
        let maxRow = 5;

        function addFileRow() {
            let div = $("#file-multi-rows");
            if (currentIdx < maxRow) {
                currentIdx++;
                div.append(`
                    <div id='row_${currentIdx}'>
                        <div class="form-group col-md-10">
                            <input type="file" name="files[]" class="form-control form-control-sm"/>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger btn-sm" type="button" onclick="removeFileRow('row_${currentIdx}')">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                `);
            }
        }

        function removeFileRow(id) {
            $(`#${id}`).remove();
        }
    </script>
    <script>
        var loader;

        function loadNow(opacity) {
            if (opacity <= 0) {
                displayContent();
            } else {
                loader.style.opacity = opacity;
                window.setTimeout(function () {
                    loadNow(opacity - 0.05);
                }, 5);
            }
        }

        function displayContent() {
            loader.style.display = 'none';
            document.getElementById('content').style.display = 'block';
        }

        document.addEventListener("DOMContentLoaded", function () {
            loader = document.getElementById('loader');
            loadNow(5);
        });
    </script>
@endsection
