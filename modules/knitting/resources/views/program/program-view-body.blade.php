
    <div class="padding">
        <div class="box">
            <div class="box-body">
                <div class="row text-center">
                    <a class="btn pull-right" href="{{ url('knitting/program/'.$data->id.'/program-view-pdf') }}" title="PDF"><i class="fa fa-file-pdf-o"></i></a>
                    <div class="col-md-6 col-md-offset-3" style="display: flex; flex-direction: column;">
                        <span style="font-size: 16px; font-weight: bold">{{ factoryName() }}</span>
                    </br>
                        <span>{{ factoryAddress() }}</span>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <u style="font-size: 15px; font-weight: bold;">Knitting Program Report</u>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="reportTable" style="border: none !important;">
                            <tr>
                                <td style="width: 40%; border: none !important;">
                                    <table class="reportTable">
                                        <tr>
                                            <td class="text-left">
                                                <strong>Buyer Name: </strong>
                                            </td>
                                            <td class="text-left">{{ $program->buyer->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Style/Job No: </strong>
                                            </td>
                                            <td class="text-left">{{ $program->planInfo->style_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>PO: </strong>
                                            </td>
                                            <td class="text-left">{{ $program->planInfo->po_no }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Team Leader: </strong>
                                            </td>
                                            <td class="text-left"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Deling Merchant: </strong>
                                            </td>
                                            <td class="text-left"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Knitting Source: </strong>
                                            </td>
                                            <td class="text-left">{{ $knittingSources[$program->knitting_source_id] ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Booking Type: </strong>
                                            </td>
                                            <td class="text-left" style="text-transform: capitalize">{{ $program->planInfo->booking_type ?? '' }}</td>
                                        </tr>

                                    </table>
                                </td>
                                <td style="width: 20%; border: none !important;"></td>
                                <td style="width: 40%; border: none !important;">
                                    <table class="reportTable">
                                        <tr>
                                            <td class="text-left">
                                                <strong>Knitting Party: </strong>
                                            </td>
                                            <td class="text-left">{{ $program->party_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Address: </strong>
                                            </td>
                                            <td class="text-left"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Create Date&Time: </strong>
                                            </td>
                                            <td class="text-left">{{ $program->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Machine GG: </strong>
                                            </td>
                                            <td class="text-left">{{ $program->machine_gg }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Last Update Date&Time: </strong>
                                            </td>
                                            <td class="text-left">{{ $program->updated_at }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Remarks: </strong>
                                            </td>
                                            <td class="text-left"></td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="reportTable" style="border: none !important;">
                            <tr>
                                <td style="width: 40%; border: none !important;">
                                    <table class="reportTable">
                                        <thead>
                                            <tr>
                                                <th colspan="4">Fleece Info</th>
                                            </tr>
                                            <tr>
                                                <th>Fab.Info</th>
                                                <th>Count</th>
                                                <th>In Percent</th>
                                                <th>Percent Wise Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($program->fleece_info['details'] as $fleece)
                                            @php
                                               // dd($fleece);
                                            @endphp
                                            <tr>
                                                <td>{{ $fleece['type'] }}</td>
                                                <td>{{ $fleece['yarn_count'] }}</td>
                                                <td>{{ $fleece['percentage'] }}</td>
                                                <td>{{ $fleece['qty'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </td>
                                <td style="width: 10%; border: none !important;"></td>
                                <td style="width: 50%; border: none !important;">
                                    <table class="reportTable">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Stipe Details(Yarn Dyeing)</th>
                                            </tr>
                                            <tr>
                                                <th>Stipe(Y/D) Color</th>
                                                <th>Measurement</th>
                                                <th>UOM</th>
                                                <th>Total Feeder</th>
                                                <th>Fab Req(KGS)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($program->knittingProgramStripeDetails as $stripe)
                                            @foreach ($stripe->stripe_details['details'] as $details)
                                            <tr>
                                                <td>{{ $details['stripe_color'] }}</td>
                                                <td>{{ $details['measurement'] }}</td>
                                                <td>{{ $details['uom_value'] }}</td>
                                                <td>{{ $details['total_feeder'] }}</td>
                                                <td>{{ $details['fabric_req_kg'] }}</td>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                                <tr>
                                    <th>Program No</th>
                                    <th>Program Qty</th>
                                    <th>PI Number</th>
                                    <th>Program Start Date</th>
                                    <th>Program End Date</th>
                                    <th>Fabric Des</th>
                                    <th>Stitch Length</th>
                                    <th>Finish GSM</th>
                                    <th>Machine Dia</th>
                                    <th>Machine Gauge</th>
                                    <th>Machine Feeder</th>
                                    <th>Finish Dia/Type</th>
                                    <th>Colour</th>
                                    <th>Program Colour QTY</th>
                                    <th>Yarn Description</th>
                                    <th>Yarn Lot</th>
                                    <th>Yarn Allocated QTY</th>
                                    <th>REQ QTY</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($data->knitting_program_colors_qtys as $colorQtyIndex => $color_qty_value)
                                @if($color_qty_value['allocated_status'] == true)
                                    @foreach($color_qty_value['knitting_yarns'] as $yarnIndex => $yarn)
                                        @foreach($yarn as $index => $value)
                                            @if($index == 0)
                                                <tr style="text-align: center;">
                                                    @if($colorQtyIndex == $data->allocation_iteration_first_index)
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->program_no }}</td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->program_qty }}</td>
                                                    <td rowspan="{{ $data->total_row }}"></td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->start_date }}</td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->end_date }}</td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->planInfo->fabric_description ?? null }}</td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->stitch_length }}</td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->planInfo->fabric_gsm }}</td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->machine_dia }}</td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->machine_gg }}</td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->feeder_text }}</td>
                                                    <td rowspan="{{ $data->total_row }}">{{ $program->finish_fabric_dia }}</td>
                                                    @endif
                                                    <td rowspan="{{ count($yarn) }}">{{ $color_qty_value['item_color'] }}</td>
                                                    <td rowspan="{{ count($yarn) }}">{{ $color_qty_value['program_qty'] }}</td>
                                                    <td>{{ $value['yarn_description'] }}</td>
                                                    <td>{{ $value['yarn_lot'] }}</td>
                                                    <td>{{ $value['allocated_qty'] }}</td>
                                                    <td>{{ $value['previous_total_yarn_requisition_qty'] }}</td>
                                                    <td></td>
                                                </tr>
                                            @else
                                                <tr style="text-align: center;">
                                                    <td>{{ $value['yarn_description'] }}</td>
                                                    <td>{{ $value['yarn_lot'] }}</td>
                                                    <td>{{ $value['allocated_qty'] }}</td>
                                                    <td>{{ $value['previous_total_yarn_requisition_qty'] }}</td>
                                                    <td></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            @endforeach
                                {{-- <tr>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->program_no }}</td>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->program_qty }}</td>
                                    <td rowspan="{{ $data->total_row }}"></td>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->start_date }}</td>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->end_date }}</td>
                                    <td rowspan="{{ $data->total_row }}"></td>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->stitch_length }}</td>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->planInfo->fabric_gsm }}</td>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->machine_dia }}</td>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->machine_gg }}</td>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->feeder_text }}</td>
                                    <td rowspan="{{ $data->total_row }}">{{ $program->finish_fabric_dia }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr> --}}
                            </tbody>

                        </table>
                    </div>
                </div>




            </div>
        </div>
    </div>
