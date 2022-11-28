@extends('skeleton::layout')
@section("title","Quotation inquiries")
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
    .reportable td {
        border: 1px solid #6887ff !important;
    }

    input {
        font-size: 12px !important;
    }

    #loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(226, 226, 226, 0.75) no-repeat center center;
        width: 100%;
        z-index: 1000;
    }

    .spin-loader {
        position: relative;
        top: 46%;
        left: 5%;
    }
</style>
@endsection
@section('content')
<div class="padding">
    <div class="box">
        <div class="box-header">
            <h2>{{ $quotation_inquiry ? 'Quotation Inquiry Update' : 'Quotation Inquiry Entry' }}</h2>
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
            {!! Form::model($quotation_inquiry, ['url' => $quotation_inquiry ? 'quotation-inquiries/'.$quotation_inquiry->id : 'quotation-inquiries', 'method' => $quotation_inquiry ? 'PUT' : 'POST', 'files' => true, 'id' => 'quotation-inquiry-form']) !!}
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="quotation_id">Inquiry Id</label>
                                {!! Form::text('quotation_id', $quotation_id ?? null, ['class' => 'form-control form-control-sm', 'id' => 'quotation_id', 'readonly' => true]) !!}
                                @if($errors->has('quotation_id'))
                                <span class="text-danger small">{{ $errors->first('quotation_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="factory_id">Company Name <span class="text-danger req">*</span></label>
                                {!! Form::select('factory_id', $factories ?? [], factoryId() ?? null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'factory_id', 'placeholder' => "Select Factory"]) !!}
                                @if($errors->has('factory_id'))
                                <span class="text-danger small">{{ $errors->first('factory_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="buyer_id">Buyer <span class="text-danger req">*</span></label>
                                {!! Form::select('buyer_id', $buyers ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'buyer_id', 'placeholder' => "Select Buyer"]) !!}
                                @if($errors->has('buyer_id'))
                                <span class="text-danger small">{{ $errors->first('buyer_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="style_name">Style
                                    <span class="text-danger req">*</span>
                                    <span class="text-danger" style="font-size: 8px;">
                                        [N:B:|&;$%@"<>+,./ Not Allowed]
                                    </span>
                                </label>
                                {!! Form::text('style_name', null, ['class' => 'form-control form-control-sm', 'id' => 'style_name', 'placeholder' => "Write Style no"]) !!}
                                @if($errors->has('style_name'))
                                <span class="text-danger small">{{ $errors->first('style_name') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="style_description">Style Description</label>
                                {!! Form::text('style_description', null, ['class' => 'form-control form-control-sm', 'id' => 'style_description', 'placeholder' => "Write Style Description"]) !!}
                                @if($errors->has('style_description'))
                                <span class="text-danger small">{{ $errors->first('style_description') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="garment_item_id">Item <span class="text-danger req">*</span></label>
                                {!! Form::select('garment_item_id', $garment_items ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'garment_item_id', 'placeholder' => "Select Item"]) !!}
                                @if($errors->has('garment_item_id'))
                                <span class="text-danger small">{{ $errors->first('garment_item_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="season_id">Season</label>
                                {!! Form::select('season_id', $seasons ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'season_id', 'placeholder' => "Select Season"]) !!}
                                @if($errors->has('season_id'))
                                <span class="text-danger small">{{ $errors->first('season_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                {!! Form::select('status', $status ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'status']) !!}
                                @if($errors->has('status'))
                                <span class="text-danger small">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inquiry_date">Inquiry Date <span class="text-danger req">*</span></label>

                                {{-- {!! Form::text('inquiry_date', null, ['class' => 'form-control form-control-sm', 'id' => 'inquiry_date', 'placeholder' => "dd/mm/yyyy"]) !!}--}}

                                {!! Form::text('inquiry_date',isset($quotation_inquiry->inquiry_date) ? date('d-m-Y', strtotime($quotation_inquiry->inquiry_date )) : date('d-m-Y', strtotime(now())), [
                                'class' => 'form-control form-control-sm',
                                'id' => isset($quotation_inquiry->inquiry_date) ? '' : 'inquiry_date',
                                'placeholder' => "dd/mm/yyyy",
                                'readonly' => isset($quotation_inquiry->inquiry_date) ? true : false
                                ]) !!}

                                @if($errors->has('inquiry_date'))
                                <span class="text-danger small">{{ $errors->first('inquiry_date') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dealing_merchant">Dealing Merchant</label>
                                {!! Form::select('dealing_merchant', $dealing_merchants ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'dealing_merchant', 'placeholder' => "Select Merchant"]) !!}
                                @if($errors->has('dealing_merchant'))
                                <span class="text-danger small">{{ $errors->first('dealing_merchant') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="submission_date">Submission Date</label>

                                {!! Form::text('submission_date',
                                isset($quotation_inquiry->submission_date) ? date('d-m-Y', strtotime($quotation_inquiry->submission_date )) : date('d-m-Y', strtotime(now()))
                                , ['class' => 'form-control form-control-sm', 'id' => 'submission_date', 'placeholder' => "dd/mm/yyyy"]) !!}

                                @if($errors->has('submission_date'))
                                <span class="text-danger small">{{ $errors->first('submission_date') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="approval_date">Approval Date</label>

                                {!! Form::text('approval_date', isset($quotation_inquiry->approval_date) ? date('d-m-Y', strtotime($quotation_inquiry->approval_date)) : null, ['class' => 'form-control form-control-sm', 'id' => 'approval_date', 'placeholder' => "dd/mm/yyyy"]) !!}

                                @if($errors->has('approval_date'))
                                <span class="text-danger small">{{ $errors->first('approval_date') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="required_sample">Required Sample</label>
                                {!! Form::select('required_sample', $required_samples ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'required_sample']) !!}
                                @if($errors->has('required_sample'))
                                <span class="text-danger small">{{ $errors->first('required_sample') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                {!! Form::textarea('remarks', null, ['class' => 'form-control form-control-sm', 'id' => 'remarks', 'rows' => 2]) !!}
                                <span class="text-danger small remarks"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="reportEntryTable">
                                <thead>
                                    <tr>
                                        <td class="width-33p" style="padding: 6px;">Fabric Type</td>
                                        <td class="width-40p" style="padding: 6px;">Fabric Composition</td>
                                        <td class="width-20p">GSM</td>
                                        <td class="width-20p">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {!! Form::text('fabrication_val', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Write
                                            Fabrication']) !!}
                                            @if($errors->has('fabrication_val'))
                                            <span class="text-danger small">{{ $errors->first('fabrication_val') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! Form::text('fabric_composition', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Write
                                            Fabric Composition']) !!}
                                            @if($errors->has('fabric_composition'))
                                            <span class="text-danger small">{{ $errors->first('fabric_composition') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! Form::text('gsm_val', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Write
                                            gsm']) !!}
                                            @if($errors->has('gsm_val'))
                                            <span class="text-danger small">{{ $errors->first('gsm_val') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-success add-row" title="Add"><i class="fa fa-plus"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 table-responsive child-entry-section" style="margin-top:6px">
                            <table class="reportTable">
                                <thead>
                                    <tr class="info">
                                        <td class="width-33p" style="padding: 6px;">Fabric Type</td>
                                        <td class="width-40p" style="padding: 6px;">Fabric Composition</td>
                                        <td class="width-20p">GSM</td>
                                        <td class="width-20p">Action</td>
                                    </tr>
                                </thead>
                                <tbody class="child-entry-table-body">
                                    @if(old('fabrication') || old('gsm') || old('fabric_composition'))
                                    @foreach(old('fabrication') as $key => $fabrication)
                                    <tr>
                                        <td>
                                            {{ $fabrication }}
                                            {!! Form::hidden('quotation_details_id[]', old('quotation_details_id')[$key]) !!}
                                            {!! Form::hidden('fabrication[]', $fabrication) !!}
                                        </td>
                                        <td>
                                            {{ old('fabric_composition')[$key] }}
                                            {!! Form::hidden('fabric_composition[]', old('fabric_composition')[$key]) !!}
                                        </td>
                                        <td>
                                            {{ old('gsm')[$key] }}
                                            {!! Form::hidden('gsm[]', old('gsm')[$key]) !!}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-danger remove-row" title="Remove"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @elseif($quotation_inquiry && $quotation_inquiry->quotationInquiryDetails->count())
                                    @foreach($quotation_inquiry->quotationInquiryDetails as $key => $quotation_inquiry_detail)
                                    <tr>
                                        <td>
                                            {{ $quotation_inquiry_detail->fabrication }}
                                            {!! Form::hidden('quotation_details_id[]', $quotation_inquiry_detail->id) !!}
                                            {!! Form::hidden('fabrication[]', $quotation_inquiry_detail->fabrication) !!}
                                        </td>
                                        <td>
                                            {{ $quotation_inquiry_detail->fabric_composition }}
                                            {!! Form::hidden('fabric_composition[]', $quotation_inquiry_detail->fabric_composition) !!}
                                        </td>
                                        <td>
                                            {{ $quotation_inquiry_detail->gsm }}
                                            {!! Form::hidden('gsm[]', $quotation_inquiry_detail->gsm) !!}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-danger remove-row" title="Remove"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Image File <span class="text-danger req">(Max File Size 2 MB)</span></label>
                        {!! Form::file('file_name', ['class'=>'form-control form-control-sm']) !!}
                        <span class="text-danger small file_name"></span>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    @php
                    $fileName = isset($quotation_inquiry) ? 'quotation_inquiry/'.$quotation_inquiry->file_name : null;
                    @endphp
                    @if($fileName && File::exists('storage/'.$fileName))
                    <img src="{{ asset("storage/$fileName")  }}" alt="quotation file" width="150">
                    @else
                    <img src="{{ asset('images/no_image.jpg') }}" alt="no image" width="100">
                    @endif
                </div>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="{{ $quotation_inquiry ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-success' }}"><i class="fa fa-save"></i> {{ $quotation_inquiry ? 'Update' : 'Create' }}</button>
                <a class="btn btn-sm btn-danger" href="{{ url('quotation-inquiries') }}"><i class="fa fa-remove"></i>
                    Cancel</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div id="loader">
        <div class="text-center spin-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    var flashMessageDom = $('.flash-message');

    function showLoader() {
        $('#loader').show();
    }

    function hideLoader() {
        $('#loader').hide();
    }

    // Scroll To Top
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function goToListPage() {
        window.location.href = window.location.protocol + "//" + window.location.host + "/quotation-inquiries";
    }

    $(document).on('keyup', '#style_name', function(e) {
        let val = (e.target.value).replace(/[|&;$%@"<>+/'.,]/g, "");
        $('#style_name').val(val)
    })

    $(document).on('click', '.add-row', function(e) {
        e.preventDefault();
        let fabrication_val = $('[name="fabrication_val"]').val();
        let gsm_val = $('[name="gsm_val"]').val();
        let fabric_composition = $('[name="fabric_composition"]').val();
        let child_entry_table_body_dom = $('.child-entry-table-body');
        if (fabrication_val || gsm_val || fabric_composition) {
            let newTableRow = '<tr>' +
                '<td>' + fabrication_val +
                '<input name="quotation_details_id[]" type="hidden" value="">' +
                '<input name="fabrication[]" type="hidden" value="' + fabrication_val + '">' +
                '<span class="fabrication text-danger small"></span>' +
                '</td>' +
                '<td>' + fabric_composition +
                '<input name="fabric_composition[]" type="hidden" value="' + fabric_composition + '">' +
                '<span class="fabric_composition text-danger small"></span>' +
                '</td>' +

                '<td>' + gsm_val +
                '<input name="gsm[]" type="hidden" value="' + gsm_val + '">' +
                '<span class="gsm text-danger small"></span>' +
                '</td>' +
                '<td><button type="button" class="btn btn-xs btn-danger remove-row" title="Remove"><i class="fa fa-trash"></i></button></td>' +
                '</tr>';
            child_entry_table_body_dom.append(newTableRow);
            $('[name="fabrication_val"]').val('');
            $('[name="gsm_val"]').val('');
            $('[name="fabric_composition"]').val('');
        } else {
            alert("Please write or select the relevant fields!");
        }
    });

    $(document).on('click', '.remove-row', function(e) {
        e.preventDefault();
        var confirmAction = confirm("Are you sure?");
        var thisHtml = $(this);
        var quotation_details_id = $(this).parents('tr').find('[name="quotation_details_id[]"]').val();
        if (confirmAction) {
            if (quotation_details_id) {
                showLoader();
                $.ajax({
                    type: "DELETE",
                    url: "/quotation-inquiry-details/" + quotation_details_id
                }).done(function(response) {
                    hideLoader();
                    if (response.status === 'success') {
                        thisHtml.parents('tr').remove();
                    }
                    if (response.status === 'danger') {
                        alert('Something went wrong');
                        console.log(response.errors);
                    }
                }).fail(function(response) {
                    hideLoader();
                    console.log("Something went wrong!");
                })
            } else {
                $(this).parents('tr').remove();
            }
        }
    });

    $(document).on('change', '#buyer_id', function() {
        getBuyer();
    });

    function getBuyer() {
        let buyerId = $("#buyer_id").val();
        let factoryId = $('#factory_id').val();
        let seasonId = $('#season_id').val();
        $('#season_id').empty().append(`<option value="">Select Season</option>`).val('').trigger('change');
        $.ajax({
            method: 'get',
            url: `/price-quotations/get-buyer-season/${factoryId}/${buyerId}`,
            success: function(result) {
                $.each(result, function(key, value) {
                    let element = `<option value="${value.id}">${value.season_name}</option>`;
                    $('#season_id').append(element);
                })
                $("#season_id").val(seasonId).trigger('change');
            },
            error: function(error) {
                console.log(error)
            }
        })
    }


    $("#inquiry_date").datepicker({
        format: 'dd-mm-yyyy'
    });

    $("#submission_date").datepicker({
        format: 'dd-mm-yyyy'
    });

    $("#approval_date").datepicker({
        format: 'dd-mm-yyyy'
    });
</script>
@endsection