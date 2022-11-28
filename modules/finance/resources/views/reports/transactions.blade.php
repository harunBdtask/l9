@extends('finance::layout')
@section('title', 'Transaction')
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
                <form action="{{ url('finance/transactions') }}" method="GET">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="form-control form-control-sm btn white">Search</button>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="form-control form-control-sm btn white print">Print</button>
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
                        <thead class="thead-light">
                            <tr>
                                <th class="text-left">Date</th>
                                <th class="text-left">Voucher No</th>
                                <th class="text-left">Voucher Type</th>
                                <th class="text-left">File No</th>
                                <th class="text-left">Code</th>
                                <th class="text-left">A/C Name</th>
                                <th class="text-left">Particulars</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trn)
                            <tr>
                                <td class="text-left">{{ $trn->trn_date->toFormattedDateString() }}</td>
                                <td class="text-left">
                                    {{ str_pad($trn->voucher->id, 8, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="text-left">{{ $trn->voucher->type }}</td>
                                <td class="text-left">{{ $trn->voucher->file_no }}</td>
                                <td class="text-left">{{ $trn->account->code }}</td>
                                <td class="text-left">{{ $trn->account->name }}</td>
                                <td class="text-left">{{ $trn->particulars ?? '' }}</td>
                                <td class="text-right">
                                    {{ $trn->trn_type == 'dr' ? number_format($trn->trn_amount, 2) : '' }}
                                </td>
                                <td class="text-right">
                                    {{ $trn->trn_type == 'cr' ? number_format($trn->trn_amount, 2) : '' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-danger">No Voucher Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            @if($transactions->total() > 15)
                                <tr>
                                    <td colspan="10" align="center">
                                        {{ $transactions->appends(request()->except('page'))->links() }}
                                    </td>
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

            var url = window.location.toString();

            if (url.includes('?')) {
                url += '&print=true';
            } else {
                url += '?print=true';
            }

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
