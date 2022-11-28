@extends('skeleton::layout')
@section('title','Commercial Realization Form')
@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <h2>{{ $commercial_realization ? 'Update': 'Create' }} Commercial Realization</h2>
    </div>

    <div class="box-body">
      {!! Form::model($commercial_realization, ['url' => $commercial_realization ? url('/commercial/realizations/'.$commercial_realization->id) : url('/commercial/realizations/store'), 'method' => $commercial_realization ? 'PUT' : 'POST', 'autocomplete' => 'off', 'id' => 'commercial-realization-form']) !!}
      {!! Form::hidden('bank_ref_bill', null) !!}
      {!! Form::hidden('buyer_id', null) !!}
      {!! Form::hidden('factory_id', null) !!}
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="realization_date" class="form-control-label">Realization Date<span class="text-danger">*</span></label>
            {!! Form::date('realization_date', optional($commercial_realization)->realization_date ?? date('Y-m-d'), ['class' => 'form-control form-control-sm']) !!}
            <span class="text-danger realization_date"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="dbp_type" class="form-control-label">DBC Type<span class="text-danger">*</span></label>
            {!! Form::select('dbp_type', $dbp_types ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Here', $commercial_realization ? 'disabled' : '']) !!}
            @if($commercial_realization)
              {!! Form::hidden('dbp_type', $commercial_realization->dbp_type) !!}
            @endif
            <span class="text-danger dbp_type"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="document_submission_id" class="form-control-label">Bank Ref<span class="text-danger">*</span></label>
            {!! Form::select('document_submission_id', $document_submission_ids ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Bank Ref', $commercial_realization ? 'disabled' : '']) !!}
            @if($commercial_realization)
              {!! Form::hidden('document_submission_id', $commercial_realization->document_submission_id) !!}
            @endif
            <span class="text-danger document_submission_id"></span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="table-responsive">
            <table class="reportTable">
              <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th>Buyer Information</th>
                </tr>
                <tr>
                  <th class="w-xs" style="text-align: left!important;">Buyer</th>
                  <td id="buyer-name-container" class="w-lg">{{ optional($commercial_realization)->buyer->name ?? null }}</td>
                </tr>
                <tr>
                  <th style="text-align: left!important;">Primary Contract No</th>
                  <td id="primary-contract-container">{{ $primary_contract_no ?? null }}</td>
                </tr>
                <tr>
                  <th style="text-align: left!important;">SC Number</th>
                  <td id="sales-contract-container">{{ $sales_contract_no ?? null }}</td>
                </tr>
                <tr>
                  <th style="text-align: left!important;">LC Number</th>
                  <td id="lc-container">{{ $lc_no ?? null }}</td>
                </tr>
                <tr>
                  <th style="text-align: left!important;">Conversion Rate</th>
                  <td id="conversion-rate-container">
                    {!! Form::number('conversion_rate', $commercial_realization->conversion_rate ?? 85.00, ['class' => 'w-full text-center b', 'placeholder' => 'Write Conversion Rate', 'step' => '.09']) !!}
                  </td>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table class="reportTable">
              <thead>
                <tr>
                  <th>Invoice Number</th>
                  <th>Invoice Date</th>
                  <th>Invoice Amount</th>
                  <th>Submission Date</th>
                  <th>Submission Amount</th>
                  <th>Prev. Realized Amount</th>
                  <th>Realized Amount</th>
                  <th>Short Realization</th>
                  <th>Due Realization</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="realization-invoices">
                @php
                  $submitShow = false;
                @endphp
                @if($commercial_realization && $commercial_realization->commercialRealizationInvoices && $commercial_realization->commercialRealizationInvoices->count())
                  @php
                    $submitShow = true;
                  @endphp
                  @foreach ($commercial_realization->commercialRealizationInvoices as $invoice)
                    <tr>
                      <td>
                        {!! Form::hidden('commercial_realization_invoices_id[]', $invoice->id) !!}
                        {!! Form::hidden('document_submission_invoice_id[]', $invoice->document_submission_invoice_id) !!}
                        {!! Form::hidden('primary_contract_id[]', $invoice->primary_contract_id) !!}
                        {!! Form::hidden('export_invoice_id[]', $invoice->export_invoice_id) !!}
                        {!! Form::hidden('sales_contract_id[]', $invoice->sales_contract_id) !!}
                        {!! Form::hidden('export_lc_id[]', $invoice->export_lc_id) !!}
                        {{ $invoice->exportInvoice->invoice_no }}
                        <span class="text-danger document_submission_invoice_id"></span>
                      </td>
                      <td>
                        {!! Form::hidden('invoice_date[]', $invoice->invoice_date) !!}
                        {{ $invoice->invoice_date ? date('d M, Y', strtotime($invoice->invoice_date)) : null }}
                        <span class="text-danger invoice_date"></span>
                      </td>
                      <td>
                        {!! Form::hidden('net_invoice_value[]', $invoice->net_invoice_value) !!}
                        {{ $invoice->net_invoice_value }}
                        <span class="text-danger net_invoice_value"></span>
                      </td>
                      <td>
                        {!! Form::hidden('document_submission_date[]', $invoice->document_submission_date) !!}
                        {{ $invoice->document_submission_date ? date('d M, Y', strtotime($invoice->document_submission_date)) : null }}
                        <span class="text-danger document_submission_date"></span>
                      </td>
                      <td>
                        {!! Form::hidden('submission_value[]', $invoice->submission_value) !!}
                        {{ $invoice->submission_value }}
                        <span class="text-danger submission_value"></span>
                      </td>
                      <td>
                        <span class="prev_realized_value_html">{{ $invoice->prev_realized_value ?? null }}</span>
                        <span class="text-danger prev_realized_value"></span>
                      </td>
                      <td>
                        {!! Form::number('realized_value[]', $invoice->realized_value, ['class' => 'text-right', 'step' => '.0001']) !!}
                        <br>
                        <span class="text-danger realized_value"></span>
                      </td>
                      <td>
                        {!! Form::number('short_realized_value[]', $invoice->short_realized_value, ['class' => 'text-right', 'step' => '.0001']) !!}
                        <br>
                        <span class="text-danger short_realized_value"></span>
                      </td>
                      <td>
                        {!! Form::hidden('due_realized_value[]', $invoice->due_realized_value, ['class' => 'text-right']) !!}
                        <span class="due_realized_value_html">{{ $invoice->due_realized_value }}</span>
                        <span class="text-danger due_realized_value"></span>
                      </td>
                      <td>
                        <button type="button" class="btn btn-sm btn-danger remove-invoice" data-id="{{ $invoice->id }}"><i class="fa fa-times"></i></button>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <button type="submit" class="btn btn-md btn-success {{ !$submitShow ? 'hide' : '' }}" id="submit-button">Submit</button>
          <a href="{{ url('/commercial/realizations') }}" class="btn btn-md btn-danger">Cancel</a>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
  toastr.options.positionClass = 'toast-top-center';
  $(document).on('change', '[name="dbp_type"]', function() {
    let dbp_type = $(this).val();
    let documentSubmissionIdSelectDOM = $('[name="document_submission_id"]');
    let documentSubmissionIdSelectInnerHtml = '';
    documentSubmissionIdSelectDOM.val('').change();
    if (dbp_type) {
      $.ajax({
        url: '/commercial-api/v1/document-submissions/get-bank-refs',
        type: 'GET',
        data: {
          dbp_type: dbp_type
        }
      }).done(function(response) {
        documentSubmissionIdSelectInnerHtml += '<option value="">Select Bank Ref</option>'
        if (response.status === 200 && response.data.length > 0) {
          response.data.forEach(element => {
            documentSubmissionIdSelectInnerHtml += '<option value="'+ element.id +'">'+ element.text +'</option>'
          });
        }
        documentSubmissionIdSelectDOM.html(documentSubmissionIdSelectInnerHtml);
      }).fail(function(response) {
        console.log(response)
      });
    }
  });

  $(document).on('change', '[name="document_submission_id"]', function() {
    let document_submission_id = $(this).val();
    let realizationInvoiceDom = $('#realization-invoices');
    let buyerNameContainer = $("#buyer-name-container");
    let primaryContractContainer = $("#primary-contract-container");
    let salesContractContainer = $("#sales-contract-container");
    let lcContainer = $("#lc-container");
    let bankRefBillField = $('[name="bank_ref_bill"]');
    let buyerIdField = $('[name="buyer_id"]');
    let factoryIdField = $('[name="factory_id"]');
    let submitBtn = $("#submit-button");
    realizationInvoiceDom.empty();
    buyerNameContainer.empty();
    primaryContractContainer.empty();
    salesContractContainer.empty();
    lcContainer.empty();
    bankRefBillField.val('');
    buyerIdField.val('');
    factoryIdField.val('');
    submitBtn.addClass('hide');
    if (document_submission_id) {
      $.ajax({
        url: '/commercial/realizations/invoice/create/'+document_submission_id,
        type: 'GET'
      }).done(function(response) {
        if (response.status === 200 && response.view) {
          realizationInvoiceDom.html(response.view);
          buyerNameContainer.html(response.buyer_info.buyer);
          primaryContractContainer.html(response.buyer_info.primary_contract_no);
          salesContractContainer.html(response.buyer_info.sales_contract_no);
          lcContainer.html(response.buyer_info.lc_no);
          bankRefBillField.val(response.data.document_submission.bank_ref_bill);
          buyerIdField.val(response.data.document_submission.buyer_id);
          factoryIdField.val(response.data.document_submission.factory_id);
          submitBtn.removeClass('hide');
        }
      }).fail(function(response) {
        console.log(response)
      })
    }
  });

  $(document).on('keyup', '[name="realized_value[]"]', function() {
    let realized_value = parseFloat($(this).val());
    let thisHtml = $(this);
    let invoiceValue = parseFloat(thisHtml.parents('tr').find('[name="net_invoice_value[]"]').val())
    let prevRealizedValue = parseFloat(thisHtml.parents('tr').find('.prev_realized_value_html').text())
    let shortRealizedValue = parseFloat(thisHtml.parents('tr').find('[name="short_realized_value[]"]').val())
    if (realized_value) {
      if (realized_value > (invoiceValue - prevRealizedValue - shortRealizedValue)) {
        toastr.warning('Invalid value given')
        $(this).val('')
      }
      calculateDueRealization(thisHtml);
    }
  });

  $(document).on('keyup', '[name="short_realized_value[]"]', function() {
    let short_realized_value = $(this).val();
    let thisHtml = $(this);
    let invoiceValue = parseFloat(thisHtml.parents('tr').find('[name="net_invoice_value[]"]').val())
    let prevRealizedValue = parseFloat(thisHtml.parents('tr').find('.prev_realized_value_html').text())
    let realizedValue = parseFloat(thisHtml.parents('tr').find('[name="realized_value[]"]').val())
    if (short_realized_value) {
      if (short_realized_value > (invoiceValue - prevRealizedValue - realizedValue)) {
        toastr.warning('Invalid value given')
        $(this).val('')
      }
      calculateDueRealization(thisHtml);
    }
  });

  function calculateDueRealization(thisHtml)
  {
    let invoiceValue = parseFloat(thisHtml.parents('tr').find('[name="net_invoice_value[]"]').val())
    let prevRealizedValue = parseFloat(thisHtml.parents('tr').find('.prev_realized_value_html').text())
    let realizedValue = parseFloat(thisHtml.parents('tr').find('[name="realized_value[]"]').val() || 0)
    let shortRealizedValue = parseFloat(thisHtml.parents('tr').find('[name="short_realized_value[]"]').val() || 0)
    let dueRealizedValue = (invoiceValue - prevRealizedValue - realizedValue - shortRealizedValue).toFixed(2)
    thisHtml.parents('tr').find('[name="due_realized_value[]"]').val(dueRealizedValue)
    thisHtml.parents('tr').find('.due_realized_value_html').html(dueRealizedValue)
  }

  $(document).on('click', '.remove-invoice', function(e) {
    e.preventDefault();
    let parentsTr = $(this).parents('tr');
    let trCount = $('#realization-invoices tr').length;
    if (trCount <= 1) {
      toastr.warning('At least one data is required!')
      return false
    }
    let commercialRealizationInvoicesId = parentsTr.find('[name="commercial_realization_invoices_id[]"]').val();
    if (commercialRealizationInvoicesId) {
      showLoader();
      $.ajax({
        type: 'DELETE',
        url: `/commercial/realizations/invoice/${commercialRealizationInvoicesId}`,
        data: {
          _token: $('meta[name="csrf-token"]').attr('content')
        }
      }).done(function(response) {
        hideLoader();
        if (response.status === 200) {
          toastr.success(response.message);
          parentsTr.remove();
          checkTrCount();
        }
      }).fail(function(response){
        hideLoader();
        toastr.error("Something went wrong! Try again!")
        console.log(response);
      });
    } else {
      parentsTr.remove();
    }
  })

  function checkTrCount()
  {
    let trCount = $('#realization-invoices tr').length;
    if (trCount < 1) {
      $('#submit-button').addClass('hide');
    }
  }

  $(document).on('submit', '#commercial-realization-form', function(e) {
    e.preventDefault();
    let form = $(this);
    let data = form.serialize();
    let url = form.attr('action');
    let method = form.attr('method');
    showLoader();
    $.ajax({
      type: method,
      url: url,
      data: data
    }).done(function(response) {
      hideLoader();
      if (response.status === 200) {
        toastr.success(response.message);
        setTimeout(() => {
          gotoList();
        }, 2000);
      }
    }).fail(function(response) {
      hideLoader();
      if (response.status === 422) { // validation error
        $.each(response.responseJSON.errors, function (errorIndex, errorValue) {
          let errorDomElement, error_index, errorMessage;
          errorDomElement = '' + errorIndex;
          errorDomIndexArray = errorDomElement.split(".");
          errorDomElement = '.' + errorDomIndexArray[0];
          error_index = errorDomIndexArray[1];
          errorMessage = errorValue[0];
          if (errorDomIndexArray.length == 1) {
            $(errorDomElement).html(errorMessage);
          } else {
            $("#realization-invoices tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
          }
        });
      } else if (response.status === 500) {
        toastr.error(response.responseJSON.message);
      }
    })
  });

  function gotoList() {
    window.location.href = window.location.protocol + "//" + window.location.host + "/commercial/realizations"
  }

</script>
@endsection