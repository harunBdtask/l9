                <div class="row text-center">
                    <div class="col-md-6">
                        <span style="font-size: 16px; font-weight: bold">{{ factoryName() }}</span>
                        <br>
                        <span>{{ factoryAddress() }}</span>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <u style="font-size: 15px; font-weight: bold;">Work Order Sheet</u>
                </div>

                <div style="text-align: left; margin-top: 10px;">
                    <u style="font-size: 15px; font-weight: bold;">Basic Information</u>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="reportTable" style="border: none !important;">
                            <tr>
                                <td style="width: 40%; border: none !important;">
                                    <table class="reportTable">
                                        <tr>
                                            <td class="text-left">
                                                <strong>Buyer : </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['buyer']  }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Buying Agent : </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['buying_agent'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Order/Style No : </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['style_no'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Booking No : </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['booking_no'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Repeat No : </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['repeat_no'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Dealing Merchant : </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['dealing_merchant'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Team Name : </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['team_name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Season : </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['season'] }}</td>
                                        </tr>

                                    </table>
                                </td>
                                <td style="width: 20%; border: none !important;"></td>
                                <td style="width: 40%; border: none !important;">
                                    <table class="reportTable">
                                        <tr>
                                            <td class="text-left">
                                                <strong>Shipment Date: </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['shipment_date'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Order Qty: </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['order_qty'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Total Number Of PO: </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['total_number_of_po']  }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Item: </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['item']  }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Fabric Booking No: </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['fabric_booking_no'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Fabrication: </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['fabrication'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Excess Cutting Percent: </strong>
                                            </td>
                                            <td class="text-left"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Remarks: </strong>
                                            </td>
                                            <td class="text-left">{{ $reportData['remark'] }}</td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @foreach($reportData['purchaseOrder'] as $purchaseOrder)
                    <div style="text-align: left; margin-top: 10px;">
                        <u style="font-size: 15px;"> <b>PO NO</b> -{{$purchaseOrder['po_no']}}, <b>PO QTY</b>
                            -{{$purchaseOrder['po_qty']}}, <b>PRINT-NO</b> -{{$purchaseOrder['print']}},
                            <b>EMBROIDERY</b> -{{$purchaseOrder['embroidery_status']}}, <b>UPDATE TIME</b>
                            -{{$purchaseOrder['update_time']}}, <b>Shipment Date</b> {{$purchaseOrder['shipment_date']}}
                        </u>
                    </div>

                    <div style="text-align: left; margin-top: 5px;">
                        <p style="font-size: 15px;"><b>PO REMARKS - {{$purchaseOrder['po_remarks']}}</b></p>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <td colspan="7"><b>COLOR SIZE BREAK DOWN with Actual Qty</b></td>
                                </tr>
                                <tr>
                                    <th class="text-left">Color/Size</th>
                                    @php
                                        $sizes = collect($purchaseOrder['actual_qty'])->collapse()->unique('size_id')->pluck('size');
                                    @endphp
                                    @foreach($sizes as $size)
                                        <th class="text-left">{{$size}}</th>
                                    @endforeach
                                    <th class="text-right">Total QTY</th>
                                </tr>
                                </thead>

                                <tbody>

                                @foreach($purchaseOrder['actual_qty'] as $actualQty)
                                    @php
                                        $totalActualQty = 0;
                                    @endphp
                                    <tr>
                                        <td class="text-left">{{ collect($actualQty)->first()['color'] }}</td>
                                        @foreach($sizes as $size)
                                            <td class="text-left">{{ collect($actualQty)->where('size',$size)->sum('value') }}</td>
                                            @php
                                                $totalActualQty += collect($actualQty)->where('size',$size)->sum('value');
                                            @endphp
                                        @endforeach
                                        <td class="text-right">{{$totalActualQty}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <table class="reportTable" style="margin-top: 5px;">
                                <thead>
                                @php
                                    $exCut = collect($purchaseOrder['ex_cut_percent'])->first()->first();
                                @endphp
                                <tr>
                                    <td colspan="7"><b>COLOR SIZE BREAK DOWN with Excess Cutting {{$exCut['value']}}%</b></td>
                                </tr>
                                <tr>
                                    <th class="text-left">Color/Size</th>
                                    @php
                                        $sizes = collect($purchaseOrder['ex_cut_percent'])->collapse()->unique('size_id')->pluck('size');
                                    @endphp
                                    @foreach($sizes as $size)
                                        <th class="text-left">{{$size}}</th>

                                    @endforeach
                                    <th class="text-right">Total QTY</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($purchaseOrder['ex_cut_percent'] as $colorKey => $exCutPercent)
                                    @php
                                        $totalExCutPercent = 0;
                                    @endphp
                                    <tr>
                                        <td class="text-left">{{ collect($exCutPercent)->first()['color'] }}</td>
                                        @foreach($sizes as $size)
                                            @php
                                                $actualQty = collect($purchaseOrder['actual_qty'][$colorKey])->where('size',$size)
                                                                ->sum('value');
                                                $cuttingPercentage = $actualQty * collect($exCutPercent)->where('size',$size)->sum('value') / 100;

                                                $cuttingPercentageSum = $cuttingPercentage + $actualQty;
                                            @endphp
                                            <td class="text-left">{{ round($cuttingPercentageSum) }}</td>
                                            @php
                                                $totalExCutPercent += $cuttingPercentageSum
                                            @endphp
                                        @endforeach
                                        <td class="text-right">{{ round($totalExCutPercent) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                        </div>
                    </div>

                @endforeach

