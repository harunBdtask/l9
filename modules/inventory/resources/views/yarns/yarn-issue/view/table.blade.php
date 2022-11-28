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
    #factoryName{
        display: none;
    }
    #logo {
        height: 60px;
        margin: 25px auto 0;
    }
    #factoryAddress{
        margin: 0;
        display: block;
    }
</style>
<table class="table-border">
    <tr>
        <th colspan="5" class="no-border-top center">
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
        <th colspan="5" class="no-border-top center">( KNITTING DIVISION )</th>
    </tr>
    <tr>
        <th colspan="5" class="no-border-top center">YARN CHALLAN ( TEXTILE )</th>
    </tr>
    <tr>
        <th class="no-borer-right">CHALLAN / ISSUE ID:</th>
        <th class="no-borer-right">{{$yarnIssue->issue_no}}</th>
        <th class="no-borer-right">
            <div style="width:120px"></div>
        </th>
        <th class="no-borer-right">Date:</th>
        <th>{{date('d.m.Y', strtotime($yarnIssue->issue_date) )}}</th>
    </tr>
    <tr>
        <th>Party Name:</th>
        <td colspan="2">{{optional($yarnIssue->loanParty)->name}}</td>
        <th>Gate Pass No</th>
        <td>{{$yarnIssue->gate_pass_no}}</td>
    </tr>
{{--    <tr>--}}
{{--        <th colspan="3"></th>--}}
{{--        <th style="max-width: 40px;">Vehicle Type</th>--}}
{{--        <td>{{$yarnIssue->vehicle_type}}</td>--}}
{{--    </tr>--}}
    <tr>
        <th>Address:</th>
        <td colspan="2">{{optional($yarnIssue->loanParty)->address_1}}</td>
        <th style="max-width: 40px;">Vehicle Number</th>
        <td>{{$yarnIssue->vehicle_number}}</td>
    </tr>
    <tr>
        <th>Lock No:</th>
        <td colspan="2">{{$yarnIssue->lock_no}}</td>
        <th style="max-width: 40px;">Driver Name</th>
        <td>{{$yarnIssue->driver_name}}</td>
    </tr>
</table>
<table style="margin-top: 25px" class="table-border">
    <thead>
    <tr>
        <th rowspan="3">SL. No.</th>
        <th rowspan="3" colspan="2" class="center">Description</th>
        <th rowspan="3" class="center" style="width: 50px">Bag / Ctn.</th>
        <th rowspan="3" class="center" style="width: 50px">Bag / Ctn. Wt.</th>
        <th colspan="2" class="center">TOTAL QUANTITY</th>
        <th rowspan="3" class="center">Remarks</th>
    </tr>
    <tr>
        <th colspan="2" class="center">YARN QUANTITY</th>
    </tr>
    <tr>
        <th>Gross Wt.</th>
        <th>Net Wt.Kg</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="no-border-top" width="20"></td>
        <td colspan="2" class="center"> Yarn Delivery For Knitting</td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
    </tr>
    @foreach($yarnIssue->details as $detail)
        <tr>
            <td class="no-border-top center">{{ $loop->iteration }} </td>
            <td class="no-border-top no-borer-right">
                {{ optional($detail->yarn_count)->yarn_count }} -
                {{ optional($detail->composition)->yarn_composition }} -
                {{ $detail->yarn_brand }}-
                {{ optional($detail->type)->name }}-
                {{ $detail->color?' - ':''}}
                {{ $detail->color}}
            </td>
            <td class="no-border-top">
                LOT - {{$detail->yarn_lot }}
            </td>
            <td class="no-border-top right">{{$detail->no_of_bag}}</td>
            <td class="no-border-top right">{{$detail->weight_per_bag}}</td>
            <td class="no-border-top right"></td>
            <td class="no-border-top right">{{ $detail->issue_qty}}</td>
            <td class="no-border-top">{{ $detail->remarks}}</td>
        </tr>
    @endforeach
    <tr>
        <td class="no-border-top"></td>
        <td class="no-border-top no-borer-right">
            <div style="height: 50px"></div>
        </td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
    </tr>
    <tr>
        <td class="no-border-top"></td>
        <td colspan="2" class="no-border-top">
            <b class="bottom-info">Buyer:</b> {{optional($yarnIssue->buyer)->name}}
        </td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
    </tr>
    <tr>
        <td class="no-border-top"></td>
        <td colspan="2" class="no-border-top">
            <b class="bottom-info">Style/Job No:</b> {{ $yarnIssue->buyer_job_no }}
        </td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
    </tr>
    <tr>
        <td class="no-border-top"></td>
        <td colspan="2" class="no-border-top">
            <b class="bottom-info">LC No: </b>{{$lc_no}}
        </td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
    </tr>
    <tr>
        <td class="no-border-top"></td>
        <td colspan="2" class="no-border-top">
            <b class="bottom-info">LC Date:</b> {{$lc_date}}
        </td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
        <td class="no-border-top"></td>
    </tr>
    <tr>
        <td class="no-border-top"></td>
        <td colspan="1" class="no-border-top no-borer-right">
            <b class="bottom-info">Style Ref:</b> {{ $yarnIssue->style_reference ?? ''}}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <th></th>
        <th></th>
        <th>TOTAL.</th>
        <th class="right">{{ $yarnIssue->details->sum('no_of_bag')??'' }}</th>
        <th class="right">{{ $yarnIssue->details->sum('weight_per_bag')??'' }}</th>
        <th class="right"></th>
        <th class="right">{{ $yarnIssue->details->sum('issue_qty')??''}}</th>
        <th></th>
    </tr>
    </tbody>
</table>
<table style="margin-top: 150px" class="table-border">
    <tr>
        <td class="center">Received Signature</td>
        <td class="center">Prepared By</td>
        <td class="center">Security Signature</td>
        <td class="center">Store Officer</td>
        <td class="center">Authorised Signature</td>
    </tr>
</table>
