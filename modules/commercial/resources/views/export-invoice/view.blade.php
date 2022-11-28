@extends('skeleton::layout')
@section('title','Export Invoice List')
@push('style')
    <style>
        table, td, th {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            text-align: center;
        }

        td {
            text-align: center;
        }

        h4 {
            text-align: center;
        }

        h5 {
            text-align: center;
        }
    </style>
@endpush
@section('content')

    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    Export Invoice List
                </h2>
            </div>

            <div class="box-body">
                <div class="container">
                    <div class="row">
                        <h4>{{ factoryName() }}</h4>
                        <h5>Commercial Invoice</h5>
                    </div>
                    <hr>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">Shipper :</div>
                                <br>
                                <br>
                                <br>
                                <div class="col-md-12">Applicant : &nbsp;{{ $invoice->applicant->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">Invoice No : &nbsp;{{ $invoice->invoice_no }}</div>
                                <div class="col-md-12">
                                    Lc/Sc No :
                                    &nbsp;{{ $invoice->export_lc_id ? $invoice->exportLc->lc_number : ($invoice->sales_contract_id ? $invoice->salesContract->contract_number : '--') }}
                                </div>
                                <div class="col-md-12">EXP NO:</div>
                                <div class="col-md-12">LC Issue Bank:
                                    &nbsp; {{ $invoice->exportLc->issuing_bank ?? '--' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">Date:</div>
                                <div class="col-md-12">Date:</div>
                                <div class="col-md-12">Date:</div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">Notify :</div>
                                <div class="col-md-12">Also Notify :</div>
                                <div class="col-md-12">Country Of Orgin :</div>
                                <div class="col-md-12">HAWB/BL No :</div>
                                <div class="col-md-12">Port Of Loading:</div>
                                <div class="col-md-12">Port Of Discharg:</div>
                                <div class="col-md-12">Payment Terms :</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">Negotiating Bank:</div>
                                <div class="col-md-12">Inco Term:</div>
                                <div class="col-md-12">HAWB/BL Date :</div>
                                <div class="col-md-12">Fedder Vessel :</div>
                                <div class="col-md-12">Mode Of Shipment:</div>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-lg">
                            <table>
                                <thead>
                                <tr>
                                    <th colspan="3">Description</th>
                                    <th rowspan="2">Style No</th>
                                    <th rowspan="2">Art No</th>
                                    <th rowspan="2">Hs Code</th>
                                    <th colspan="2">Qnty</th>
                                    <th rowspan="2">Ctns Qnty</th>
                                    <th rowspan="2">Unit Price</th>
                                    <th rowspan="2">Amount</th>
                                </tr>
                                <tr>
                                    <th>Po No</th>
                                    <th>Description</th>
                                    <th>Description</th>
                                    <th>Qnty</th>
                                    <th>UOM</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($invoice->details)
                                    @forelse($invoice->details as $detail)
                                        <tr>
                                            <td>{{ $detail->po->po_no }}</td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $detail->order->style_name }}</td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $detail->current_invoice_qty }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11">No Data</td>
                                        </tr>
                                    @endforelse
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg">
                            <div class="col-md-8">
                                <table>
                                    <tbody>
                                    <tr>
                                        <td>Total Ctns:</td>
                                        <td>00</td>
                                        <td>Ctns</td>
                                    </tr>
                                    <tr>
                                        <td>Total Quantity</td>
                                        <td>00</td>
                                        <td>Pcs</td>
                                    </tr>
                                    <tr>
                                        <td>Total Net Wt:</td>
                                        <td>00</td>
                                        <td>KG</td>
                                    </tr>
                                    <tr>
                                        <td>Total Gross Wt</td>
                                        <td>00</td>
                                        <td>KG</td>
                                    </tr>
                                    <tr>
                                        <td>Total CBM:</td>
                                        <td>00</td>
                                        <td>CBM</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4"></div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
