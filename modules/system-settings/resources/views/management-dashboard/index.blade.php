@extends('skeleton::layout')
@section("title", "All report dashboard")

@section('styles')
    <style>
        .innerBox {
            min-height: 300px;
            padding: 0 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .box1 {
            /*background-image: linear-gradient( 108.1deg,  rgba(167,220,225,1) 11.2%, rgba(217,239,242,1) 88.9% );*/

        }

        .box2 {
            /*background-image: linear-gradient( 108.1deg,  rgba(167,220,225,1) 11.2%, rgba(217,239,242,1) 88.9% );*/
        }

        .box3 {
            /*background-image: linear-gradient( 108.1deg,  rgba(167,220,225,1) 11.2%, rgba(217,239,242,1) 88.9% );*/
        }

        .box4 {
            /*background-image: linear-gradient( 108.1deg,  rgba(167,220,225,1) 11.2%, rgba(217,239,242,1) 88.9% );*/
        }

        .linkCard {
            width: 100%;
            text-align: center;
            background: #f5f5f5;
            cursor: pointer;
            font-size: 13px;
            padding: 4px;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header text-center">
                <h2>Major Reports</h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4 p-2">
                        <div class="box" style="border: 1px solid #efefef;">
                            <div class="box-header">
                                <h4>Merchandising</h4>
                            </div>
                            <div class="innerBox box2">
                                <div class="card linkCard" onclick="handleClick('/order-volume-report')">
                                    Order Volume Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/order-entry-report')">
                                    Color & Size Breakdown Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/buyer-season-order?type=images')">
                                    Buyer Season Order with Images
                                </div>
                                <div class="card linkCard" onclick="handleClick('/budget-wise-wo-report')">
                                    Budget wise WO Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/bom-report')">
                                    BOM Report
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="box" style="border: 1px solid #efefef;">
                            <div class="box-header">
                                <h4>Fabric Store</h4>
                            </div>
                            <div class="innerBox box1">
                                <div class="card linkCard" onclick="handleClick('/inventory/finish-fabric-report')">
                                    Finish Fabric Store Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/inventory/fabric-stock-summery-report')">
                                    Finish Stock Summary Report
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="box" style="border: 1px solid #efefef;">
                            <div class="box-header">
                                <h4>Yarn Store</h4>
                            </div>
                            <div class="innerBox box3">
                                <div class="card linkCard" onclick="handleClick('/inventory/yarn-item-ledger')">
                                    Yarn Item Ledger
                                </div>
                                <div class="card linkCard">
                                    Daily Yarn Issue Statement
                                </div>
                                <div class="card linkCard">
                                    Daily Party Wise Yarn Receive Statement
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 p-2">
                        <div class="box" style="border: 1px solid #efefef;">
                            <div class="box-header">
                                <h4>Cutting</h4>
                            </div>
                            <div class="innerBox box4">
                                <div class="card linkCard" onclick="handleClick('/daily-cutting-report')">
                                    Daily Cutting Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/month-wise-cutting-report')">
                                    Month Wise Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/monthly-table-wise-cutting-production-summary-report')">
                                    Monthly Table Wise Cutting Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/buyer-style-wise-cutting-report')">
                                    Buyer Style Wise Cutting Report
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="box" style="border: 1px solid #efefef;">
                            <div class="box-header">
                                <h4>Print</h4>
                            </div>
                            <div class="innerBox box4">
                                <div class="card linkCard" onclick="handleClick('/date-wise-print-send-report')">
                                    Date Wise Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/buyer-wise-print-send-receive-report')">
                                    Buyer Wise Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/cutting-no-wise-color-print-send-receive-report')">
                                    Cutting Wise Report
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="box" style="border: 1px solid #efefef;">
                            <div class="box-header">
                                <h4>Input</h4>
                            </div>
                            <div class="innerBox box4">
                                <div class="card linkCard" onclick="handleClick('/date-wise-sewing-input')">
                                    Date Wise Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/buyer-sewing-line-input')">
                                    Buyer Wise Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/date-range-or-month-wise-sewing-input')">
                                    Month Wise Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/floor-line-wise-sewing-report')">
                                    Floor & Line Wise Report
                                </div>
                                <div class="card linkCard" onclick="handleClick('/input-closing')">
                                    Input Closing Report
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="box" style="border: 1px solid #efefef;">
                            <div class="box-header">
                                <h4>Sewing</h4>
                            </div>
                            <div class="innerBox box4">
                                <div class="card linkCard" onclick="handleClick('/line-wise-hourly-sewing-output')">
                                    Line Wise Hr Prod.
                                </div>
                                <div class="card linkCard" onclick="handleClick('/date-wise-hourly-sewing-output')">
                                    Date Wise Hr Prod.
                                </div>
                                <div class="card linkCard" onclick="handleClick('/monthly-line-wise-production-summary-report')">
                                    Monthly Production Summary
                                </div>
                                <div class="card linkCard" onclick="handleClick('/daily-input-output-report')">
                                    Daily Input Output Summary
                                </div>
                                <div class="card linkCard" onclick="handleClick('/production-dashboard')">
                                    Production on Graph
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function handleClick(url) {
            window.open(url, '_blank')
        }
    </script>
@endsection
