@extends('finance::layout')

@section('styles')
    <style type="text/css">
        .custom-padding {
            padding: 0 200px 0 200px;
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
                @if($voucher->status_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::CREATED)
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                        <span class="btn btn-xs btn-primary" data-toggle="modal" data-target="#voucher-post">Post</span>
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-amend">Amend</span>
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-cancel">Cancel</span>
                        <a class="btn btn-xs btn-primary" href="{{ url('finance/vouchers/'.$voucher->id.'/edit') }}">
                            Edit
                        </a>
                    </div>
                @elseif($voucher->status_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::CHECKED)
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                        <span class="btn btn-xs btn-primary" data-toggle="modal" data-target="#voucher-authorize">Authorize</span>
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-amend">Amend</span>
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-cancel">Cancel</span>
                    </div>
                @elseif($voucher->status_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::AUTHORIZED)
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                        <span class="btn btn-xs btn-primary" data-toggle="modal" data-target="#voucher-post">Post</span>
                        <span class="btn btn-xs btn-primary" data-toggle="modal"
                              data-target="#voucher-cancel">Cancel</span>
                    </div>
                @elseif($voucher->status_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::AMEND)
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                        <a class="btn btn-xs btn-primary" href="{{ url('finance/vouchers/'.$voucher->id.'/edit') }}">
                            Edit
                        </a>
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
                <div class="voucher-meta">
                    <div class="col-md-4 col-md-offset-1">
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label text-right"><b>Voucher :</b></label>
                            <div class="col-sm-8 form-control-label text-left">
                                <p>{{  $voucher->type }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label text-right"><b>Company :</b></label>
                            <div class="col-sm-8 form-control-label text-left">
                                <p>{{  $voucher->company->name ?? '--' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2"></div>

                    <div class="col-md-4 text-right">
                        <div class="form-group row">
                            <label class="col-sm-7 form-control-label text-right"><b>Transaction Date :</b></label>
                            <div class="col-sm-5  form-control-label text-left">
                                <p>{{  $voucher->trn_date->toFormattedDateString() ?? '--' }}</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-7 form-control-label text-right"><b>Reference No :</b></label>
                            <div class="col-sm-5  form-control-label text-left">
                                <p>{{  $voucher->reference_no ?? '--' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabular-form">
                        <thead class="thead-light">
                        <tr>
                            <th rowspan="2">ACCOUNT CODE</th>
                            <th rowspan="2">ACCOUNT HEAD</th>
                            <th rowspan="2">NARRATION</th>
                            <th rowspan="2">UNIT</th>
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
                        @foreach($voucher->details->items as $item)
                            <tr>
                                <td>{{ $item->account_code }}</td>
                                <td>{{ $item->account_name }}</td>
                                <td>{{ $item->narration }}</td>
                                <td>{{ $item->unit_name }}</td>
                                <td>{{ $item->const_center_name }}</td>
                                <td>{{ $item->currency_name . '@' . $item->conversion_rate }}</td>
                                <td class="text-right">{{ $item->dr_fc != 0 ? number_format($item->dr_fc, 2) : '' }}</td>
                                <td class="text-right">{{ $item->cr_fc != 0 ? number_format($item->cr_fc, 2) : '' }}</td>
                                <td class="text-right">{{ $item->debit != 0 ? number_format($item->debit, 2) : '' }}</td>
                                <td class="text-right">{{ $item->credit != 0 ? number_format($item->credit, 2) : '' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class='text-center' colspan="6"><strong>TOTAL</strong></td>
                            <td class="text-right">
                                <strong>{{ $voucher->details->total_debit_fc ? number_format($voucher->details->total_debit_fc, 2) : '' }}</strong>
                            </td>
                            <td class="text-right">
                                <strong>{{ $voucher->details->total_credit_fc ? number_format($voucher->details->total_credit_fc, 2) : '' }}</strong>
                            </td>
                            <td class="text-right">
                                <strong>{{ number_format($voucher->details->total_debit, 2) }}</strong>
                            </td>
                            <td class="text-right">
                                <strong>{{ number_format($voucher->details->total_credit, 2) }}</strong>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p>
                    @php
                        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                    @endphp
                    <strong>Amount in Words: </strong>{{ ucwords($digit->format($voucher->amount)) }}
                    {{--                    <strong>Amount in Words: </strong>{{ ucwords(in_words($voucher->amount)) }}--}}
                </p>
                <div class="signature" style='margin-top: 45px'>
                    <div class="table-responsive">
                        <table class="table borderless">
                            <tbody>
                            <tr>
                                <td class="text-center">
                                    {{-- {{ $voucher->prepared_by ? ucwords($voucher->prepared_by->screen_name) : '' }} --}}
                                <img src="{{ asset('storage/'.$voucher->createdBy->signature)}}"/>
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
                                <th class="text-center"><u>Prepared By</u></th>
                                <th class='text-center'><u>Checked By</u></th>
                                <th class='text-center'><u>Audit Department</u></th>
                                <th class='text-center'><u>Manager (Account)</u></th>
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
