<div class="body-section" style="margin-top: 0px;">
    <div class="row">
        <div class="col-md-3">
            <div style=" padding: 1%;" class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <td style="text-align: left"><b>Factory</b></td>
                        <td style="width: 140px">
                            {{ $wipReport['assign_factory'] ?? ''  }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><b>Style</b></td>
                        <td style="width: 140px">
                            {{ $wipReport['wip_style'] ??  $wipReport['style']}}

                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><b>Order Qty</b></td>
                        <td>
                            {{ $wipReport['order_qty'] ?? 0 }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><b>POs Received Date</b></td>
                        <td>
                            {{ $wipReport['po_received_date'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><b>POs Issued to FTY</b></td>
                        <td>
                            {{ $wipReport['po_issued_to_fty'] ?? '' }}

                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><b>Fabric PI rcvd date</b></td>
                        <td>
                            {{ $wipReport['fabric_pi_recieved_date'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><b>SC Issue date</b></td>
                        <td style="width: 140px">
                            {{ $wipReport['sc_issue_date'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left"><b>Revised SC Issue date</b></td>
                        <td>
                            {{ $wipReport['revised_sc_issue_date'] ?? '' }}
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div style=" padding: 1%;" class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th colspan="3"><b>Color Break down as per PO</b></th>
                    </tr>
                    <tr>
                        <td style="width: 30%"><b>Color</b></td>
                        <td style="width: 20%"><b>Qty</b></td>
                        <td style="width: 50%"><b>ASI- Master LC Due on</b></td>
                    </tr>
                    @forelse($wipReport['color_breakdown_as_per_po'] as $index => $item)
                        <tr>
                            <td>{{ $item['color']  ?? ''}}</td>
                            <td>{{ $item['qty']  ?? 0 }}</td>
                            <td>{{ $item['asi_master_lc_due_on']  ?? ''}}</td>
                        </tr>

                    @empty
                        <tr colspan="3" align="center"><b>No Data Found!</b></tr>
                    @endforelse

                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="col-md-6">
            <div style=" padding: 1%;" class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <td><b>Customer</b></td>
                        <td><b>PO</b></td>
                        <td><b>Group</b></td>
                        <td><b>Brand</b></td>
                        <td><b>FTY Del.</b></td>
                        <td><b>Order Qty</b></td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($wipReport['customer_wise_po'] as $index => $item)
                        <tr>
                            <td>{{ $item['customer_name']  ?? ''}}</td>
                            <td>{{ $item['po_no']  ?? '' }}</td>
                            <td>{{ $item['group']  ?? ''}}</td>
                            <td>{{ $item['brand']  ?? ''}}</td>
                            <td>{{ $item['fty_del']  ?? ''}}</td>
                            <td>{{ $item['order_qty']  ?? ''}}</td>
                        </tr>

                    @empty
                        <tr colspan="6" align="center">No Data Found!</tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-3">
            <div style="padding: 1%;" class="table-responsive">
                <table>
                    <tr>
                        <td>WIP Date :</td>
                        <td>
                            {{ $wipReport['wip_date'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>BULK TP RCVD DATE:</td>
                        <td style="width: 140px">
                            {{ $wipReport['bulk_tp_received_date'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>PCD:</td>
                        <td style="width: 140px">
                            {{ $wipReport['pcd'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>PO Delivery date</td>
                        <td style="width: 140px">
                            {{ $wipReport['po_delivery_date'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Final Costing Approved</td>
                        <td style="width: 140px">
                            {{ $wipReport['final_costing_approved'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Costing YY</td>
                        <td style="width: 140px">
                            {{ $wipReport['costing_yy'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Packing Info + UPC</td>
                        <td style="width: 140px">
                            {{ $wipReport['packing_info_upc'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>SIP Due date:</td>
                        <td style="width: 140px">
                            {{ $wipReport['ship_due_date'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            @if($wipReport['image'] && file_exists(('./storage/'. $wipReport['image'])))
                                <img
                                    src="{{ asset('./storage/'. $wipReport['image']) }}"
                                    width="90%"
                                    class="img-fluid">
                            @else
                                <img src="{{ asset('/images/no_image.jpg') }}" alt="" width="80%">

                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            {{ $wipReport['garments_item'] ?? '' }}

                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 10px;">
        @if( isset($fabricDetails) && count($fabricDetails) > 0)

            @foreach($fabricDetails as $index => $fabricData )
                <div style=" padding: 1%;" class="table-responsive">
                    <table>
                        <thead>
                        <tr style="height: 40px; background-color: #4fdfee">
                            <th align="left" style="padding-left: 5px" colspan="4"> Body Fabric - {{ $index +1 }} ;
                                Use in - {{ $fabricData['body_part_value'] ?? ' ' }}</th>
                            <th align="left" style="padding-left: 5px" colspan="2">Fabric supplier (Mill) ;</th>
                            <th align="left" style="padding-left: 5px"
                                colspan="2">{{ $fabricData['supplier_value'] ?? ' ' }}</th>
                            <th align="left" style="padding-left: 5px" colspan="7">
                                Fabric Quality : {{ $fabricData['fabric_description'] ?? ' ' }},
                                GSM : {{ $fabricData['gsm'] ?? ' ' }},
                                DIA : {{ $fabricData['body_part_value'] ?? ' ' }},
                            </th>
                        </tr>
                        <tr>
                            <td rowspan="2">Buying Colors</td>
                            <td rowspan="2">Fabric Quality Status</td>
                            <td colspan="3">LD status</td>
                            <td rowspan="2">Booked date by FTY</td>
                            <td rowspan="2">PI Status/ LC due on</td>
                            <td colspan="3">Fabric T&A</td>
                            <td rowspan="2">PP Yds in house date</td>
                            <td rowspan="2">Bulk Shade Band rcv Dt</td>
                            <td rowspan="2">Fabric Test status (FPT) & Recvd Date</td>
                            <td rowspan="2">Ship Docs Recvd</td>
                            <td rowspan="2">Remarks</td>
                        </tr>
                        <tr>
                            <td>1st Sent Dt/ Status</td>
                            <td>2nd Sent Dt/ Status</td>
                            <td>3rd Sent Dt/ Status</td>
                            <td>ETD</td>
                            <td>ETA</td>
                            <td>In house Date</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fabricData['details'] as $key => $item)
                            <tr>
                                <td>{{ $item['buying_color'] ?? ' ' }}</td>
                                <td>
                                    {{ $item['fabric_status_quality_send_date'] ? 'sent-' . $item['fabric_status_quality_send_date'] : ' ' }}
                                    {{ $item['fabric_status_quality_app_date'] ? 'app-' . $item['fabric_status_quality_app_date'] : ' ' }}
                                    {{ $item['fabric_status_quality_rej_date'] ? 'rej-' . $item['fabric_status_quality_rej_date'] : ' ' }}
                                </td>
                                <td>
                                    {{ $item['first_send_date'] ? 'sent-' . $item['first_send_date'] : ' ' }}
                                    {{ $item['first_app_date'] ? 'app-' . $item['first_app_date'] : ' ' }}
                                    {{ $item['first_rej_date'] ? 'rej-' . $item['first_rej_date'] : ' ' }}
                                </td>
                                <td>
                                    {{ $item['second_send_date'] ? 'sent-' . $item['second_send_date'] : ' ' }}
                                    {{ $item['second_app_date'] ? 'app-' . $item['second_app_date'] : ' ' }}
                                    {{ $item['second_rej_date'] ? 'rej-' . $item['second_rej_date'] : ' ' }}
                                </td>
                                <td>
                                    {{ $item['third_send_date'] ? 'sent-' . $item['third_send_date'] : ' ' }}
                                    {{ $item['third_app_date'] ? 'app-' . $item['third_app_date'] : ' ' }}
                                    {{ $item['third_rej_date'] ? 'rej-' . $item['third_rej_date'] : ' ' }}
                                </td>
                                <td>{{ $item['booked_date_by_fty'] ?? ' ' }}</td>
                                <td>{{ $item['pi_status_lc_due_date'] ?? ' ' }}</td>
                                <td>{{ $item['etd'] ?? ' ' }}</td>
                                <td>{{ $item['eta'] ?? ' ' }}</td>
                                <td>{{ $item['in_house_date'] ?? ' ' }}</td>
                                <td>
                                    {{ $item['pp_yds_in_house_send_date'] ? 'sent-' . $item['pp_yds_in_house_send_date'] : ' ' }}
                                    {{ $item['pp_yds_in_house_recved_date'] ? 'received-' . $item['pp_yds_in_house_recved_date'] : ' ' }}
                                </td>
                                <td>{{ $item['bulk_shade_band_receive_date'] ?? ' ' }}</td>
                                <td>{{ $item['fabric_test_receive_date'] ?? ' ' }}</td>
                                <td>{{ $item['ship_docs_receive_date'] ?? ' ' }}</td>
                                <td>{{ $item['remarks'] ?? ' ' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach

        @endif

    </div>

    <div class="row" style="margin-top: 10px;">
        @if( isset($trimsDetails) && count($trimsDetails) > 0 )
            @foreach($trimsDetails as $index => $trimsData)
                <div style=" padding: 1%;" class="table-responsive">
                    <table>
                        <thead>
                        <tr style="height: 40px; background-color: #4fdfee">
                            <td align="left" style="padding-left: 5px" colspan="4">
                                {{ $trimsData['item_name'] ?? ' ' }}
                            </td>
                            <td align="left" style="padding-left: 5px" colspan="2">Supplier :</td>
                            <td align="left" style="padding-left: 5px" colspan="4">
                                {{ $trimsData['supplier_value'] ?? ' ' }}

                            </td>
                            <td align="left" style="padding-left: 5px" colspan="10">
                                Description: &nbsp; {{ $trimsData['description'] ?? ' ' }}
                                UOM:{{ $trimsData['uom_name'] ?? ' ' }}
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $trimsData['body_color'] ?? 'Body Color' }}</td>
{{--                            <td>Body Color</td>--}}
                            <td style="min-width: 75px">{{ $trimsData['thread_color'] ?? ' ' }}</td>
                            <td>Lay Out Submission Date</td>
                            <td>Approve</td>
                            <td>Quality Submission Date</td>
                            <td>Approval Date</td>
                            <td>1 st Submit sent date</td>
                            <td>Approval Date</td>
                            <td>2nd submit sent dt</td>
                            <td>Approval Date</td>
                            <td>3rd submit dt</td>
                            <td>Approval Date</td>
                            <td>4th submit dt</td>
                            <td>Approval Date</td>
                            <td>Booking Date</td>
                            <td>Pi Issue Date</td>
                            <td>Bulk in house status</td>
                            <td>BULK thread mock up submit dt</td>
                            <td>Approval Status</td>
                            <td>Remarks</td>
                        </tr>
                        @if(count($trimsData['details']) > 0)
                            @foreach($trimsData['details'] as $key => $item)
                                <tr>
                                    <td style="height: 20px">{{ $item['body_color'] ?? '' }}</td>
                                    <td>{{ $item['thread_color_name'] ?? '' }}</td>
                                    <td>{{ isset($item['lay_out_submission_sent_date']) ? 'sent-'. $item['lay_out_submission_sent_date']: '' }}</td>
                                    <td>
                                        {{ isset($item['lay_out_submission_app_date']) ? 'app-'. $item['lay_out_submission_app_date']: '' }}
                                        {{ isset($item['lay_out_submission_rej_date']) ? 'rej-'. $item['lay_out_submission_rej_date']: '' }}
                                    </td>
                                    <td>{{ isset($item['quality_submission_sent_date']) ? 'sent-'. $item['quality_submission_sent_date']: '' }}</td>
                                    <td>
                                        {{ isset($item['quality_submission_app_date']) ? 'app-'. $item['quality_submission_app_date']: '' }}
                                        {{ isset($item['quality_submission_rej_date']) ? 'rej-'. $item['quality_submission_rej_date']: '' }}
                                    </td>
                                    <td>{{ isset($item['first_submit_sent_date']) ? 'sent-'. $item['first_submit_sent_date']: '' }}</td>
                                    <td>
                                        {{ isset($item['first_submit_app_date']) ? 'app-'. $item['first_submit_app_date']: '' }}
                                        {{ isset($item['first_submit_rej_date']) ? 'rej-'. $item['first_submit_rej_date']: '' }}
                                    </td>
                                    <td>{{ isset($item['second_submit_sent_date']) ? 'sent-'. $item['second_submit_sent_date']: '' }}</td>
                                    <td>
                                        {{ isset($item['second_submit_app_date']) ? 'app-'. $item['second_submit_app_date']: '' }}
                                        {{ isset($item['second_submit_rej_date']) ? 'rej-'. $item['second_submit_rej_date']: '' }}
                                    </td>
                                    <td>{{ isset($item['third_submit_sent_date']) ? 'sent-'. $item['third_submit_sent_date']: '' }}</td>
                                    <td>
                                        {{ isset($item['third_submit_app_date']) ? 'app-'. $item['third_submit_app_date']: '' }}
                                        {{ isset($item['third_submit_rej_date']) ? 'rej-'. $item['third_submit_rej_date']: '' }}
                                    </td>
                                    <td>{{ isset($item['fourth_submit_sent_date']) ? 'sent-'. $item['fourth_submit_sent_date']: '' }}</td>
                                    <td>
                                        {{ isset($item['fourth_submit_app_date']) ? 'app-'. $item['fourth_submit_app_date']: '' }}
                                        {{ isset($item['fourth_submit_rej_date']) ? 'rej-'. $item['fourth_submit_rej_date']: '' }}
                                    </td>
                                    <td>{{ $item['booking_date'] ?? '' }}</td>
                                    <td>{{ $item['pi_issue_date'] ?? '' }}</td>
                                    <td>{{ $item['bulk_in_house_status'] ?? '' }}</td>
                                    <td>{{ $item['bulk_thread_mock_up_submit_date'] ?? '' }}</td>
                                    <td>{{ $item['approval_status'] ?? '' }}</td>
                                    <td>{{ $item['remarks'] ?? '' }}</td>

                                </tr>
                            @endforeach
                        @endif
                        </thead>
                    </table>
                </div>
            @endforeach
        @endif
    </div>

    <div class="row" style="margin-top: 10px">
        <div style=" padding: 1%;" class="table-responsive">
            <table>
                <thead>
                <tr>
                    <td>Tech Pack Issue date</td>
                    <td>Styling sample App. Date</td>
                    <td>1 Fit Send</td>
                    <td>App. Date</td>
                    <td>2nd Fit Send</td>
                    <td>App. Date</td>
                    <td>3rd Fit Send</td>
                    <td>App. Date</td>
                    <td>PPS send</td>
                    <td>Appv dt</td>
                    <td>AD Sample</td>
                    <td>Appv dt</td>
                    <td>TOP sent dt</td>
                    <td>Appv dt</td>
                    <td>Remarks</td>
                </tr>
                @if($wipReport['sample_status'])
                    <tr>
                        <td>{{ isset( $wipReport['sample_status']['tech_pack_issue_date']) ? $wipReport['sample_status']['tech_pack_issue_date'] :'' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['styling_sample_app_date']) ? $wipReport['sample_status']['styling_sample_app_date'] :'' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['first_fit_send_date']) ? $wipReport['sample_status']['first_fit_send_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['first_app_date'] )? $wipReport['sample_status']['first_app_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['second_fit_send_date']) ? $wipReport['sample_status']['second_fit_send_date'] :'' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['second_app_date'] )? $wipReport['sample_status']['second_app_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['third_fit_send_date']) ? $wipReport['sample_status']['third_fit_send_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['third_app_date']) ? $wipReport['sample_status']['third_app_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['pps_send_date'] )? $wipReport['sample_status']['pps_send_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['forth_app_date']) ? $wipReport['sample_status']['forth_app_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['ad_sample'] )? $wipReport['sample_status']['ad_sample'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['fifth_app_date'] )? $wipReport['sample_status']['fifth_app_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['top_sent_date']) ? $wipReport['sample_status']['top_sent_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['sixth_app_date']) ? $wipReport['sample_status']['sixth_app_date'] : '' }}</td>
                        <td>{{ isset( $wipReport['sample_status']['remarks']) ? $wipReport['sample_status']['remarks'] : '' }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Special Notes :</td>
                        <td colspan="3">{{ isset( $wipReport['sample_status']['special_notes']) ? $wipReport['sample_status']['special_notes'] : '' }}</td>
                        <td colspan="2">Next Meeting Due on :</td>
                        <td colspan="7">{{ isset( $wipReport['sample_status']['next_meeting_due_on']) ? $wipReport['sample_status']['next_meeting_due_on'] : '' }}</td>
                    </tr>
                @endif

                </thead>
            </table>
        </div>
    </div>
</div>
