@extends('finance::layout')
@section('title', 'Voucher')
@section('styles')
<style type="text/css">
    .addon-btn-primary {
        padding: 0;
        margin: 0px;
        background: #0275d8;
    }
    .addon-btn-primary:hover {
        background: #025aa5;
    }
    select.c-select {
        min-height: 2.375rem;
    }
    input[type=date].form-control form-control-sm, input[type=time].form-control form-control-sm, input[type=datetime-local].form-control form-control-sm, input[type=month].form-control form-control-sm {
        line-height: 1rem;
    }
    .reportTable th.text-left, .reportTable td.text-left {
        text-align: left;
        padding-left: 5px;
    }

    .reportTable th.text-right, .reportTable td.text-right {
        text-align: right;
        padding-right: 5px;
    }

    .reportTable th.text-center, .reportTable td.text-center {
        text-align: center;
    }
    tbody tr:hover {
        background-color: cyan !important;
    }
</style>
@endsection

@section('content')
<div class="padding">
    <div class="box">
        <div class="box-header">
            <h2>Voucher List</h2>
        </div>
        <div class="box-body b-t">
            <div class="row">
                <form action="{{ url('finance/vouchers') }}" method="GET">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Voucher Type</label>
                            <select class="form-control form-control-sm c-select" name="voucher_type">
                                <option value="0">All Vouchers</option>

                                @foreach($voucherTypes as $key => $type)
                                    <option value="{{ $key }}" {{ request('voucher_type') == $key ? 'selected' : ''}}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>File No</label>
                            <input type="text" name="file_no" class="form-control form-control-sm" value="{{ request('file_no') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Voucher No</label>
                            <input type="text" name="voucher_no" class="form-control form-control-sm" value="{{ request('voucher_no') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="form-control form-control-sm btn white">GO</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                @if(Session::has('success'))
                <div class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <small>{{ Session::get('success') }}</small>
                </div>
                @elseif(Session::has('failure'))
                <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <small>{{ Session::get('failure') }}</small>
                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="reportTable">
                        <thead class="thead-light" style="background-color: rgb(148, 218, 251);">
                            <tr>
                                <th class="text-left">Voucher No.</th>
                                <th class="text-left">Transaction Date</th>
                                <th class="text-left">Refrence No</th>
                                <th class="text-left">Voucher Type</th>
                                <th class="text-left">Status</th>
                                <th class="text-right">Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vouchers as $voucher)
                            <tr style="background-color: {{ $voucher->status_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::POSTED ? 'azure' : ''}}">
                                <td class='text-left'>{{ str_pad($voucher->id, 8, '0', STR_PAD_LEFT) }}</td>
                                <td class="text-left">{{ $voucher->trn_date->toFormattedDateString() }}</td>
                                <td class="text-left">{{ $voucher->file_no ?? '' }}</td>
                                <td class="text-left">{{ $voucher->type }}</td>
                                <td class="text-left">{{ $voucherStatuses[$voucher->status_id]??'' }}</td>
                                <td class="text-right">{{ number_format($voucher->amount, 2) }}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs" href="{{ url('finance/vouchers/'.$voucher->id) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="btn btn-xs print" href="{{ url('finance/vouchers/'.$voucher->id.'/print') }}">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    @if($voucher->status_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::CREATED || $voucher->status_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::AMEND)
                                        <a class="btn btn-xs" href="{{ url('finance/vouchers/'.$voucher->id.'/edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-danger">No Voucher Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            @if($vouchers->total() > 15)
                            <tr>
                                <td colspan="10" align="center">{{ $vouchers->appends(request()->except('page'))->links() }}</td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.print').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            printPage(url);
        });

        function closePrint () {
            document.body.removeChild(this.__container__);
        }

        function setPrint () {
            this.contentWindow.__container__ = this;
            this.contentWindow.onbeforeunload = closePrint;
            this.contentWindow.onafterprint = closePrint;
            this.contentWindow.focus(); // Required for IE
            this.contentWindow.print();
        }

        function printPage (sURL) {
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
