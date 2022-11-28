@extends('skeleton::layout')
@section('title','Knitting Roll')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                @includeIf('knitting::knitting-roll.view-body')
            </div>
            <div class="col-sm-3">
                <button class="btn btn-sm btn-primary print-btn">
                    <i class="fa fa-print"></i>
                </button>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h2>Roll Details</h2>
            </div>
            <div class="box-body">
                <table class="reportTable">
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Roll No</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->barcode_no }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Factory</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->factory->factory_name }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Knit Card</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->knitCard->knit_card_no }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Buyer</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->planningInfo->buyer_name }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Order/Style</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->planningInfo->style_name }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Booking No</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->planningInfo->booking_no }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Color</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->knitCard->color }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Weight</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->roll_weight ? $knitProgramRoll->roll_weight." KG" : "" }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">QC Weight</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->qc_roll_weight ? $knitProgramRoll->qc_roll_weight." KG" : "" }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Operator</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->operator->operator_name }}</td>
                    </tr>
                    <tr>
                        <th style="background: #c9f3ef; width: 20%; text-align: left;">Shift</th>
                        <td style="text-align: left;">{{ $knitProgramRoll->shift->shift_name }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).on('click', '.print-btn', function() {
            var divToPrint = document.getElementById('printId');
            var newWin = window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><body onload="window.print()"><center><div style="width: 50%">'+divToPrint.innerHTML+'</div></center></body></html>');
            newWin.document.close();
            setTimeout(function(){ newWin.close(); }, 10);
        })
    </script>
@endsection
