@extends('basic-finance::layout')
@section('title','Basic Finance')
@section('styles')
    <style type="text/css">
        .borderless td, .borderless th {
            border: none;
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
                           href="{{ url('basic-finance/vouchers/'.$voucher->id.'/print') }}">
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
                           href="{{ url('basic-finance/vouchers/'.$voucher->id.'/edit') }}">
                            Edit
                        </a>
                        @endpermission
                    </div>
                @elseif($voucher->status_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::CHECKED)
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('basic-finance/vouchers/'.$voucher->id.'/print') }}">
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
                           href="{{ url('basic-finance/vouchers/'.$voucher->id.'/print') }}">
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
                           href="{{ url('basic-finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                        @permission('permission_of_vouchers_list_edit')
                        <a class="btn btn-xs btn-primary"
                           href="{{ url('basic-finance/vouchers/'.$voucher->id.'/edit') }}">
                            Edit
                        </a>
                        @endpermission
                    </div>
                @else
                    <div class="pull-right">
                        <a class="btn btn-xs btn-primary print"
                           href="{{ url('basic-finance/vouchers/'.$voucher->id.'/print') }}">
                            Print
                        </a>
                    </div>
                @endif
                <h2>Voucher Preview</h2>
            </div>
            <div class="box-body b-t">
                <div class="voucher-meta">
                    <table class="borderless voucher-head pull-right" style="width: 50%">
                        <tbody>
                        <tr>
                            <th class="text-right" style="padding-left: 0;">Transaction Date</th>
                            <td style="width: 1%" nowrap>
                                <strong>:</strong> {{ $voucher->trn_date->toFormattedDateString() }}</td>
                        </tr>
                        <tr>
                            <th class="text-right" style="padding-left: 0">Reference No</th>
                            <td style="width: 1%" nowrap><strong>:</strong> {{ $voucher->file_no }}</td>
                        </tr>
                        {{-- <tr>
                            <th style="padding-left: 0px">Status</th>
                            <td><strong>:</strong> {{ $voucher->status }}</td>
                        </tr> --}}
                        </tbody>
                    </table>

                    <table class="borderless voucher-head" style="width: 50%">
                        <tbody>
                        <tr>
                            <th style="width: 1%; padding-left: 0" nowrap>Voucher</th>
                            <td><strong>:</strong> {{  $voucher->type }}</td>
                        </tr>
                        <tr>
                            <th style="width: 1%; padding-left: 0" nowrap>Voucher No.</th>
                            <td><strong>:</strong> {{ str_pad($voucher->id, 8, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        {{-- <tr>
                            <th style="padding-left: 0px">Status</th>
                            <td><strong>:</strong> {{ $voucher->status }}</td>
                        </tr> --}}
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th>ACCOUNT CODE</th>
                            <th>HEAD OF ACCOUNT</th>
                            <th>PARTICULARS</th>
                            <th class="text-right">DEBIT</th>
                            <th class="text-right">CREDIT</th>
                        </tr>
                        </thead>
                        <tbody class="voucher-items">
                        @foreach($voucher->details->items as $item)
                            <tr>
                                <td>{{ $item->account_code }}</td>
                                <td>{{ $item->account_name }}</td>
                                <td>{{ $item->particulars ?? '' }}</td>
                                <td class="text-right">{{ $item->debit != 0 ? number_format($item->debit, 2) : '' }}</td>
                                <td class="text-right">{{ $item->credit != 0 ? number_format($item->credit, 2) : '' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class='text-center' colspan="3"><strong>TOTAL</strong></td>
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
                    <strong>Amount in Words: </strong>{{ makeMoneyFormat($voucher->amount) . ' only' }}
                </p>
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
    {{--<div id="voucher-ok" class="modal fade" style="display: none;">--}}
    {{--    <div class="modal-dialog">--}}
    {{--        <div class="modal-content">--}}
    {{--            {!! Form::open(['url' => url('basic-finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}--}}
    {{--            <div class="modal-header">--}}
    {{--                <h5 class="modal-title">Confirmation</h5>--}}
    {{--            </div>--}}
    {{--            <div class="modal-body text-center p-lg">--}}
    {{--                <p>Are you sure this voucher is all right?</p>--}}
    {{--                <input type="hidden" name="status_id" value="1">--}}
    {{--            </div>--}}
    {{--            <div class="modal-footer">--}}
    {{--                <button type="button" class="btn danger p-x-md" data-dismiss="modal">No</button>--}}
    {{--                <button type="submit" class="btn success p-x-md">Yes</button>--}}
    {{--            </div>--}}
    {{--            {!! Form::close() !!}--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}
    {{--<div id="voucher-authorize" class="modal fade" style="display: none;">--}}
    {{--    <div class="modal-dialog">--}}
    {{--        <div class="modal-content">--}}
    {{--            {!! Form::open(['url' => url('basic-finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}--}}
    {{--            <div class="modal-header">--}}
    {{--                <h5 class="modal-title">Confirmation</h5>--}}
    {{--            </div>--}}
    {{--            <div class="modal-body text-center p-lg">--}}
    {{--                <p>Do you really want to authorize this voucher?</p>--}}
    {{--                <input type="hidden" name="status_id" value="2">--}}
    {{--            </div>--}}
    {{--            <div class="modal-footer">--}}
    {{--                <button type="button" class="btn danger p-x-md" data-dismiss="modal">No</button>--}}
    {{--                <button type="submit" class="btn success p-x-md">Yes</button>--}}
    {{--            </div>--}}
    {{--            {!! Form::close() !!}--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}
    <div id="voucher-post" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => url('basic-finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}
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
                {!! Form::open(['url' => url('basic-finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}
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
                {!! Form::open(['url' => url('basic-finance/vouchers/'.$voucher->id.'/approval'), 'method' => 'put']) !!}
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
