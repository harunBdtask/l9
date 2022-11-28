<style>
    .table-border th,
    .table-border td {
        padding: 3px;
        text-align: left;
        border: 1px solid black;
    }

    .table-border th.center,
    .table-border td.center {
        text-align: center;
    }

    .table-border th.right,
    .table-border td.right {
        text-align: right;
    }

    .table-border th.no-border-top,
    .table-border td.no-border-top {
        border-bottom-color: transparent !important;
    }

    .table-border th.no-borer-right,
    .table-border td.no-borer-right {
        border-right-color: transparent !important;
    }

    .bottom-info {
        min-width: 100px;
        display: inline-block;
    }

    #factoryName {
        display: none;
    }

    #logo {
        height: 60px;
        margin: 25px auto 0;
    }

    #factoryAddress {
        margin: 0;
        display: block;
    }
</style>

<div style="border: 1px solid; padding: 12px 12px;">

    <table class="table-border" style="width: 100%;">
        <tr>
            <th class="no-border-top center" style="border: 0">
                @php
                    $company_logo = asset('flatkit/assets/images/company-image.png');
                    if (session()->get('getCompanyLogo') && Storage::disk('public')->exists('company/'.session()->get('getCompanyLogo'))) {
                      $company_logo = asset('storage/company/'.session()->get('getCompanyLogo'));
                    }
                @endphp
                <img id="logo" src="{{ $company_logo }}" alt="{{ factoryName() }}">
                <h3 id="factoryName" style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</h3>
                <p id="factoryAddress" style="font-size: 8pt">{{ factoryAddress() }}</p>
            </th>
        </tr>
        <tr>
            <th colspan="5" style="border: 0" class="no-border-top center">( KNITTING DIVISION )</th>
        </tr>
        <tr>
            <th colspan="5" style="border: 0" class="no-border-top center">GATE PASS CHALLAN</th>
        </tr>
    </table>
    <br>
    <table class="no-border">
        <tr>
            <td style="width: 50%">
                <table class="table-border">
                    <tr>
                        <th>CHALLAN / ISSUE ID:</th>
                        <td>{{$yarnIssue->issue_no}}</td>
                    </tr>
                    <tr>
                        <th>Party Name:</th>
                        <td colspan="2">{{optional($yarnIssue->loanParty)->name}}</td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td colspan="2">{{optional($yarnIssue->loanParty)->address_1}}</td>
                    </tr>
                    <tr>
                        <th>Lock No:</th>
                        <td colspan="2">{{$yarnIssue->lock_no}}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 25%">
                <table class="table-border">
                    <tr>
                        <th class="no-borer-right">Date:</th>
                        <td>{{ date('d-m-Y', strtotime($yarnIssue->issue_date) )}}</td>
                    </tr>
                    <tr>
                        <th>Gate Pass No</th>
                        <td>{{$yarnIssue->gate_pass_no}}</td>
                    </tr>
                    <tr>
                        <th style="max-width: 40px;">Vehicle Number</th>
                        <td>{{$yarnIssue->vehicle_number}}</td>
                    </tr>
                    <tr>
                        <th style="max-width: 40px;">Driver Name</th>
                        <td>{{$yarnIssue->driver_name}}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 25%">
                <center>
                    <p>{{ $yarnIssue->issue_no }}</p>
                    {!! DNS1D::getBarcodeSVG(($yarnIssue->issue_no), "C128A", 1, 16, '', false) !!}
                </center>
            </td>
        </tr>
    </table>

    <table style="margin-top: 25px" class="table-border">
        <tr>
            <th>Buyer :</th>
            <td>{{ $yarnIssue->buyer->name }}</td>
            <th>Style/Job No :</th>
            <td>{{ $yarnIssue->buyer_job_no }}</td>
            <th>Style Ref/PI No :</th>
            <td>{{ $yarnIssue->style_reference }}</td>
            <th>
                <span>Issue Against Knitting</span>
                <br>
                <span>Requisition No</span>:
            </th>
            <td>{{ $yarnIssue->details ? $yarnIssue->details->pluck('demand_no')->implode(', ') : '' }}</td>
        </tr>
        <tr>
            <th>LC No :</th>
            <td>{{ $lc_no }}</td>
            <th>LC Rcv Date :</th>
            <td>{{ $lc_date }}</td>
            <th>Create Date & Time:</th>
            <td>{{ date('d-m-Y H:s a', strtotime($yarnIssue->created_at)) }}</td>
            <th></th>
            <td></td>
        </tr>
    </table>

    <table class="table-border" style="margin-top: 25px">
        <tr>
            <th>SL No</th>
            <th>Yarn Count</th>
            <th>Yarn Composition</th>
            <th>Yarn Type</th>
            <th>Yarn Color</th>
            <th>Yarn Brand</th>
            <th>Yarn LOT No</th>
            <th>Fabric Type</th>
            <th>Knit Mc. Dia</th>
            <th>Bag/CTN</th>
            <th>Bag/CTN Wt.</th>
            <th>Gross Wt.</th>
            <th>Net Wt.kg</th>
            <th>Remarks</th>
        </tr>
        @php
            $totalQty = 0;
            $totalBag = 0;
            $totalWeightPerBag = 0;
        @endphp
        @if(count($yarnIssue->details) > 0)
            @foreach($yarnIssue->details as $detail)
                @php
                    $fabricType = optional($detail->requisition->program->planInfo)->fabric_description;
                    if ($fabricType) {
                        $fabricType = explode('[', $fabricType);
                        $fabricType = $fabricType[0] ?? null;
                    }
                @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ optional($detail->yarn_count)->yarn_count }}</td>
                <td>{{ optional($detail->composition)->yarn_composition }}</td>
                <td>{{ optional($detail->type)->name }}</td>
                <td>{{ $detail->yarn_color }}</td>
                <td>{{ $detail->yarn_brand }}</td>
                <td>{{ $detail->yarn_lot }}</td>
                <td>{{ $fabricType }}</td>
                <td>{{ optional($detail->requisition->program)->machine_dia }}</td>
                <td>{{ $detail->no_of_bag }}</td>
                <td>{{ $detail->weight_per_bag }}</td>
                <td></td>
                <td>{{ $detail->issue_qty }}</td>
                <td>{{ $detail->remarks }}</td>
            </tr>
            @php
                $totalBag += $detail->no_of_bag;
                $totalQty += $detail->issue_qty;
                $totalWeightPerBag += (int)$detail->weight_per_bag;
            @endphp
        @endforeach
            <tr>
                <th colspan="9">Total</th>
                <th>{{ $totalBag }}</th>
                <th></th>
                <th></th>
                <th>{{ $totalQty }}</th>
                <th></th>
            </tr>
        @else
            <tr>
                <td colspan="13">No data found!</td>
            </tr>
        @endif
    </table>
    @php
        $formatter = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
    @endphp
    <p style="text-transform: capitalize;"><strong>In word : </strong> {{ $formatter->format($totalQty) }} (KG)</p>

    <table style="margin-top: 150px" class="table-border">
        <tr>
            <td class="center">Received Signature</td>
            <td class="center">Prepared By</td>
            <td class="center">Security Signature</td>
            <td class="center">Store Officer</td>
            <td class="center">Authorised Signature</td>
        </tr>
    </table>

</div>
