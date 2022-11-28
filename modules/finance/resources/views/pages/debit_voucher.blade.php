@extends('finance::layout')

@section('styles')
    <style type="text/css">
        .borderless td, .borderless th {
            border: none;
        }

        #tabular-form thead {
            background-color: #00b0ff;
        }

        #tabular-form table, #tabular-form thead, #tabular-form tbody, #tabular-form th, #tabular-form td {
            padding: 3px !important;
            vertical-align: middle !important;
            font-size: 12px;
            text-align: center;
            border-color: black;
        }

        .form-control-label {
            padding: 0 0 0 5px !important;
        }

        .form-group {
            margin-bottom: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                @if($voucher->status_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::CREATED)
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                        @permission('permission_of_vouchers_list_post')
                        <span class="btn btn-xs btn-primary" data-toggle="modal" data-target="#voucher-post">Post</span>
                        @endpermission
                        @permission('permission_of_vouchers_list_amend')
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-amend">Amend</span>
                        @endpermission
                        @permission('permission_of_vouchers_list_cancel')
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-cancel">Cancel</span>
                        @endpermission
                        @permission('permission_of_vouchers_list_edit')
                        <a class="btn btn-xs btn-primary"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/edit') }}">
                            Edit
                        </a>
                        @endpermission
                    </div>
                @elseif($voucher->status_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::CHECKED)
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                        @permission('permission_of_vouchers_list_authorize')
                        <span class="btn btn-xs btn-primary" data-toggle="modal" data-target="#voucher-authorize">Authorize</span>
                        @endpermission
                        @permission('permission_of_vouchers_list_amend')
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-amend">Amend</span>
                        @endpermission
                        @permission('permission_of_vouchers_list_cancel')
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-cancel">Cancel</span>
                        @endpermission
                    </div>
                @elseif($voucher->status_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::AUTHORIZED)
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                        @permission('permission_of_vouchers_list_post')
                        <span class="btn btn-xs btn-primary" data-toggle="modal" data-target="#voucher-post">Post</span>
                        @endpermission
                        @permission('permission_of_vouchers_list_cancel')
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-cancel">Cancel</span>
                        @endpermission
                    </div>
                @elseif($voucher->status_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::AMEND)
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                        @permission('permission_of_vouchers_list_edit')
                        <a class="btn btn-xs btn-primary"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/edit') }}">
                            Edit
                        </a>
                        @endpermission
                    </div>
                @else
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                    </div>
                @endif
                <h2>Voucher Preview</h2>
            </div>
            <div class="box-body b-t">
                <div class="header-section" align="center" style="padding-bottom: 0px;">
                    <table class="borderless">
                        <thead>
                        <tr>
                            <td class="text-center" style="height: 30px;">
                                <span style="font-size: 16pt; font-weight: bold;">{{ factoryName() }}</span><br>
                                {{ factoryAddress() }} <br> &nbsp;
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center" style="height: 30px; border: 1px solid black;">
                                <span
                                    style="font-size: 14pt; font-weight: bold;">{{$voucher->paymode == 1 ? 'BANK' : 'CASH'}} {{strtoupper($voucher->type)}}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr>
                </div>
                <div class="voucher-meta">
                    <div class="col-md-4">
                        <table class="" style="padding-top: 20px;">
                            <tr>
                                <th style="text-align:left; width:150px;">Voucher No:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->voucher_no}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Project Name:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->project->project}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Unit Name:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->unit->unit}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Payment To:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->to}}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-4">
                    </div>

                    <div class="col-md-4">
                        <table class="" style="padding-top: 20px;">
                            <tr>
                                <th style="text-align:left; width:150px;">Transaction Date:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->trn_date ? $voucher->trn_date->toFormattedDateString() : ''}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Currency:</th>
                                <td style="text-align:left; width:150px;">{{collect(\SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService::currencies())
                                                            ->where('id', $voucher->currency_id)->first()['name'] ?? null}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Bank Name:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->bank->account->name}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Cheque No:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->cheque_no}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Due Date:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->cheque_due_date ? $voucher->cheque_due_date : ''}}</td>
                            </tr>
                            <tr>
                                <th style="text-align:left; width:150px;">Reference:</th>
                                <td style="text-align:left; width:150px;">{{$voucher->reference_no}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <div class="table-responsive" style="padding-top:20px;">
                    <table class="table table-bordered" id="tabular-form">
                        <thead class="thead-light">
                        <tr>
                            <th rowspan="2">ACCOUNT</th>
                            <th rowspan="2">DEPARTMENT</th>
                            <th rowspan="2">COST CENTER</th>
                            <th rowspan="2">CON. RATE</th>
                            <th colspan="2">FC</th>
                            <th colspan="2">BDT</th>
                        </tr>
                        <tr>
                            <th>DEBIT</th>
                            <th>CREDIT</th>
                            <th>DEBIT</th>
                            <th>CREDIT</th>
                        </tr>
                        </thead>
                        <tbody class="voucher-items">
                        @php
                            $currency =  collect(\SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService::currencies())->where('id', $voucher->details->currency_id)->first()['name'] ?? null;
                        @endphp
                        @foreach($voucher->details->items as $item)
                            <tr>
                                <td style="text-align:left;">{{ $item->account_name }} ({{ $item->account_code }}) <br> {{ $item->narration }}
                                </td>
                                <td style="text-align:left;">{{ $item->department_name??null }}</td>
                                <td style="text-align:left;">{{ $item->const_center_name }}</td>
                                <td>{{ $currency.'@'.$item->conversion_rate }}</td>
                                <td style="text-align:right;">{{isset($item->dr_fc) && $item->dr_fc != 0 ? number_format($item->dr_fc, 2) : '' }}</td>
                                <td style="text-align:right;"></td>
                                <td style="text-align:right;">{{isset($item->debit) && $item->debit != 0 ? number_format($item->debit, 2) : '' }}</td>
                                <td style="text-align:right;"></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="text-align:left;">{{ $voucher->details->credit_account_name??null }}
                                ({{ $voucher->details->credit_account_code??null }})
                                <br> {{ $voucher->details->items[0]->narration }} </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right;"></td>
                            <td style="text-align:right;">
                                {{ isset($voucher->details->total_debit_fc) && $voucher->details->total_debit_fc != 0 ? number_format($voucher->details->total_debit_fc, 2) : '' }}
                            </td>
                            <td style="text-align:right;"></td>
                            <td style="text-align:right;">{{ $voucher->details->total_credit != 0 ? number_format($voucher->details->total_credit, 2) : '' }}</td>
                        </tr>
                        <tr>
                            <td class='text-center' colspan="4"><strong>TOTAL</strong></td>
                            <td style="text-align:right;">
                                <strong>{{ isset($voucher->details->total_debit_fc) ? number_format($voucher->details->total_debit_fc, 2) : '' }}</strong>
                            </td>
                            <td style="text-align:right;">
                                <strong>{{ isset($voucher->details->total_debit_fc) ? number_format($voucher->details->total_debit_fc, 2) : '' }}</strong>
                            </td>
                            <td style="text-align:right;">
                                <strong>{{ number_format($voucher->details->total_debit, 2) }}</strong>
                            </td>
                            <td style="text-align:right;">
                                <strong>{{ number_format($voucher->details->total_credit, 2) }}</strong>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="reportTable">
                    @php
                        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                    @endphp
                    <table >
                        <tbody>
                        <tr>
                            <td  style="text-align:left; width:150px;" rowspan="2"><b>Amount in Words:</b></td>

                            <td style="text-align:left;" ><b>FC  :</b> {{ $voucher->details->total_debit_fc ? ucwords($digit->format($voucher->details->total_debit_fc)) : null}}</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;" ><b>Home:</b> {{ $voucher->amount ? ucwords($digit->format($voucher->amount)) : null}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="signature" style='margin-top: 45px'>
                    <div class="table-responsive">
                        <table class="table borderless">
                            <tbody>
                            <tr>
                                <td class="text-center">
                                    {{-- {{ $voucher->prepared_by ? ucwords($voucher->prepared_by->screen_name) : '' }} --}}
                                </td>
                                <td class='text-center'>
                                    {{-- {{ $voucher->checked_by ? ucwords($voucher->checked_by->screen_name) : '' }} --}}
                                </td>
                                <td></td>
                                <td></td>
                                <td class="text-center">
                                    {{-- {{ $voucher->authorized_by ? ucwords($voucher->authorized_by->screen_name) : '' }} --}}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center"><u>Received By</u></th>
                                <th class="text-center"><u>Prepared By</u></th>
                                <th class='text-center'><u>Checked By</u></th>
                                <th class='text-center'><u>Approved By</u></th>
                                <th class="text-center"><u>Authorized By</u></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Ok Modal-->
    <div id="voucher-ok" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => url('finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <p>Are you sure this voucher is all right?</p>
                    <input type="hidden" name="status_id" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn danger p-x-md" data-dismiss="modal">No</button>
                    <button type="submit" class="btn success p-x-md"
                            onClick="this.disabled=true; this.value='Sending…'; this.form.submit();">Yes
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div id="voucher-authorize" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => url('finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <p>Do you really want to authorize this voucher?</p>
                    <input type="hidden" name="status_id" value="2">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn danger p-x-md" data-dismiss="modal">No</button>
                    <button type="submit" class="btn success p-x-md"
                            onClick="this.disabled=true; this.value='Sending…'; this.form.submit();">Yes
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div id="voucher-post" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => url('finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <p>Do you really want to make transaction?</p>
                    <input type="hidden" name="status_id" value="3">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn danger p-x-md" data-dismiss="modal">No</button>
                    <button type="submit" class="btn success p-x-md"
                            onClick="this.disabled=true; this.value='Sending…'; this.form.submit();">Yes
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div id="voucher-amend" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => url('finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}
                <div class="modal-header">
                    <h5 class="modal-title">Explanation</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <input type="hidden" name="status_id" value="4">
                    <textarea class="form-control" name='message' rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn danger p-x-md" data-dismiss="modal">No</button>
                    <button type="submit" class="btn success p-x-md"
                            onClick="this.disabled=true; this.value='Sending…'; this.form.submit();">Yes
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div id="voucher-cancel" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => url('finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}
                <div class="modal-header">
                    <h5 class="modal-title">Explanation</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <input type="hidden" name="status_id" value="5">
                    <textarea class="form-control" name='message' rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn danger p-x-md" data-dismiss="modal">No</button>
                    <button type="submit" class="btn success p-x-md"
                            onClick="this.disabled=true; this.value='Sending…'; this.form.submit();">Yes
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.print').click(function (e) {
            e.preventDefault();

            var url = $(this).attr('href');

            printPage(url);
        });

        function closePrint() {
            document.body.removeChild(this.__container__);
        }

        function setPrint() {
            this.contentWindow.__container__ = this;
            this.contentWindow.onbeforeunload = closePrint;
            this.contentWindow.onafterprint = closePrint;
            this.contentWindow.focus(); // Required for IE
            this.contentWindow.print();
        }

        function printPage(sURL) {
            var oHiddFrame = document.createElement("iframe");
            oHiddFrame.onload = setPrint;
            oHiddFrame.style.visibility = "hidden";
            oHiddFrame.style.position = "fixed";
            oHiddFrame.style.right = "0";
            oHiddFrame.style.bottom = "0";
            oHiddFrame.src = sURL;
            document.body.appendChild(oHiddFrame);
        }
    </script>
@endsection
