<div class="body-section" style="margin-top: 0px;">
    <div>
        <table border="1px solid">
            <thead >
            <tr ><td class="text-center" colspan="20" style="background-color: lightskyblue; height: 30px; font-size: 20px;">{{ factoryName() }}</td></tr>
            <tr ><td class="text-center" colspan="20" style="background-color: lightskyblue; height: 20px; font-size: 15px;"> {{ factoryAddress() }}</td></tr>
            </thead>
        </table>
    </div>
    <div class="row">
        <table >
            @php $colorWisePoCount = count($colorWisePo) + 7; @endphp
            {{--            {{ dd($colorWisePo) }}--}}
            @for( $index =0; $index < $maxLength  ; $index++)
                <tr>
                    @if($index <= 7)
                        <td colspan="2" style="border: 1px solid black;">{{ $factoryData[$index][0] }}</td>
                        <td style="border: 1px solid black">{{ $factoryData[$index][1] }}</td>
                    @elseif(($index > 7) && ($index < $colorWisePoCount) )
                        @php $colorIndex = 1; @endphp
                        @if($index == 8)
                            <td colspan="3" style="border: 1px solid black;">Color Break down as per PO</td>
                        @else
                            <td style="border: 1px solid black;">{{ $colorWisePo[$colorIndex]['color'] ?? ''}}</td>
                            <td style="border: 1px solid black;">{{ $colorWisePo[$colorIndex]['qty'] ?? ''}}</td>
                            <td style="border: 1px solid black;">{{ $colorWisePo[$colorIndex]['asi_master_lc_due_on'] ?? ''}}</td>
                            @php $colorIndex += 1; @endphp
                        @endif
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                    <td></td>
                    @if($index == 0)
                        <td style="border: 1px solid black;">{{ $customerPo[$index][0] }}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index][1] }}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index][2] }}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index][3] }}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index][4] }}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index][5] }}</td>
                    @elseif( $index !== 0 && count($customerPo) > $index)
                        <td style="border: 1px solid black;">{{ $customerPo[$index]['customer_name'] ?? ''}}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index]['po_no'] ?? ''}}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index]['group'] ?? '' }}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index]['brand'] ?? '' }}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index]['fty_del']  ?? ''}}</td>
                        <td style="border: 1px solid black;">{{ $customerPo[$index]['order_qty'] ?? '' }}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                    <td></td>
                    @if( count($wipData) > $index )

                        @if($index == 8)
                            <td></td>
                        @else
                            <td style="border: 1px solid black;">{{ $wipData[$index][0] }}</td>
                            <td style="border: 1px solid black;">{{ $wipData[$index][1] }}</td>
                        @endif

                    @else
                        <td></td>
                        <td></td>
                    @endif

                    {{--                    @if(count($wipData) > $index)--}}
                    {{--                        @if($index == 8 || $index == 9)--}}
                    {{--                            @if($index == 8)--}}
                    {{--                                <td colspan="2" rowspan="3" align="center">--}}
                    {{--                                    @if($wipReport['image'] && file_exists(('./storage/'. $wipReport['image'])))--}}
                    {{--                                        --}}{{--                                    storage_path() . '/app/public/'. $images[0]--}}
                    {{--                                        <img--}}
                    {{--                                            src="{{ storage_path() . '/app/public/'. $wipReport['image'] }}"--}}
                    {{--                                            width="50%"--}}
                    {{--                                            height="50px"--}}
                    {{--                                            class="img-fluid">--}}
                    {{--                                    @else--}}
                    {{--                                        <img src="{{ asset('/images/no_image.jpg') }}" alt="" width="80%">--}}

                    {{--                                    @endif--}}
                    {{--                                </td>--}}
                    {{--                            @else--}}
                    {{--                                <td colspan="2">{{ $wipData[$index][1] }}</td>--}}

                    {{--                            @endif--}}

                    {{--                        @else--}}
                    {{--                            <td>{{ $wipData[$index][0] }}</td>--}}
                    {{--                            <td>{{ $wipData[$index][1] }}</td>--}}
                    {{--                        @endif--}}

                    {{--                    @else--}}
                    {{--                        <td></td>--}}
                    {{--                        <td></td>--}}
                    {{--                    @endif--}}

                </tr>
            @endfor
        </table>

        {{--        <table>--}}
        {{--            <thead>--}}
        {{--            <tr>--}}
        {{--                <th colspan="3">Color Break down as per PO</th>--}}
        {{--            </tr>--}}
        {{--            <tr>--}}
        {{--                <td>Color</td>--}}
        {{--                <td>Qty</td>--}}
        {{--                <td>ASI- Master LC Due on</td>--}}
        {{--            </tr>--}}
        {{--            @forelse($wipReport['color_breakdown_as_per_po'] as $index => $item)--}}
        {{--                <tr>--}}
        {{--                    <td>{{ $item['color']  ?? ''}}</td>--}}
        {{--                    <td>{{ $item['qty']  ?? 0 }}</td>--}}
        {{--                    <td>{{ $item['asi_master_lc_due_on']  ?? ''}}</td>--}}
        {{--                </tr>--}}

        {{--            @empty--}}
        {{--                <tr colspan="3" align="center">No Data Found!</tr>--}}
        {{--            @endforelse--}}

        {{--            </thead>--}}
        {{--            <tbody>--}}
        {{--            </tbody>--}}
        {{--        </table>--}}
    </div>

    <div class="row" style="margin-top: 10px;">
        @if( isset($fabricDetails) && count($fabricDetails) > 0)

            @foreach($fabricDetails as $index => $fabricData )
                <div style=" padding: 1%;" class="table-responsive">
                    <table>
                        <thead>
                        <tr>
                            <td colspan="15" style="background-color: lightskyblue; border: 1px solid black;"> Fabric Details</td>
                        </tr>
                        <tr style="height: 40px;">
                            <td style="background-color: lightskyblue;border: 1px solid black; text-align: left" colspan="4"> Body Fabric - {{ $index +1 }} ;
                                Use in - {{ $fabricData['body_part_value'] ?? ' ' }}</td>
                            <td style="background-color: lightskyblue;border: 1px solid black; padding-left: 5px" colspan="2">Fabric supplier (Mill) ;</td>
                            <td style="background-color: lightskyblue;border: 1px solid black; padding-left: 5px"
                                colspan="2">{{ $fabricData['supplier_value'] ?? ' ' }}</td>
                            <td style="background-color: lightskyblue;border: 1px solid black; padding-left: 5px" colspan="7">
                                Fabric Quality : {{ $fabricData['fabric_description'] ?? ' ' }},
                                GSM : {{ $fabricData['gsm'] ?? ' ' }},
                                DIA : {{ $fabricData['body_part_value'] ?? ' ' }},
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2" style="background-color: lightblue; border: 1px solid black;">Buying Colors</td>
                            <td rowspan="2" style="background-color: lightblue; border: 1px solid black;">Fabric Quality Status</td>
                            <td colspan="3" style="background-color: lightblue; border: 1px solid black;">LD status</td>
                            <td rowspan="2" style="background-color: lightblue; border: 1px solid black;">Booked date by FTY</td>
                            <td rowspan="2" style="background-color: lightblue; border: 1px solid black;">PI Status/ LC due on</td>
                            <td colspan="3" style="background-color: lightblue; border: 1px solid black;">Fabric T{{ '&' }}A</td>
                            <td rowspan="2" style="background-color: lightblue; border: 1px solid black;">PP Yds in house date</td>
                            <td rowspan="2" style="background-color: lightblue; border: 1px solid black;">Bulk Shade Band rcv Dt</td>
                            <td rowspan="2" style="background-color: lightblue; border: 1px solid black;">Fabric Test status (FPT) {{'&'}} Recvd Date</td>
                            <td rowspan="2" style="background-color: lightblue; border: 1px solid black;">Ship Docs Recvd</td>
                            <td rowspan="2" style="background-color: lightblue; border: 1px solid black;">Remarks</td>
                        </tr>
                        <tr>
                            <td style="background-color: lightblue; border: 1px solid black;">1st Sent Dt/ Status</td>
                            <td style="background-color: lightblue; border: 1px solid black;">2nd Sent Dt/ Status</td>
                            <td style="background-color: lightblue; border: 1px solid black;">3rd Sent Dt/ Status</td>
                            <td style="background-color: lightblue; border: 1px solid black;">ETD</td>
                            <td style="background-color: lightblue; border: 1px solid black;">ETA</td>
                            <td style="background-color: lightblue; border: 1px solid black;">In house Date</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fabricData['details'] as $key => $item)
                            <tr>
                                <td  style="height: 20px; border: 1px solid black; text-align: left">{{ $item['buying_color'] ?? ' ' }}</td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ $item['fabric_status_quality_send_date'] ? 'Sent: ' . \Illuminate\Support\Carbon::parse($item['fabric_status_quality_send_date'])->format('M d').',' : ' ' }}
                                    {{ $item['fabric_status_quality_app_date'] ? 'App: ' . \Illuminate\Support\Carbon::parse($item['fabric_status_quality_app_date'])->format('M d').',' : ' ' }}
                                    {{ $item['fabric_status_quality_rej_date'] ? 'Rej: ' . \Illuminate\Support\Carbon::parse($item['fabric_status_quality_rej_date'])->format('M d').',' : ' ' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ $item['first_send_date'] ? 'Sent: ' . \Illuminate\Support\Carbon::parse($item['first_send_date'])->format('M d').',' : ' ' }}
                                    {{ $item['first_app_date'] ? 'App: ' . \Illuminate\Support\Carbon::parse($item['first_app_date'])->format('M d').',' : ' ' }}
                                    {{ $item['first_rej_date'] ? 'Rej: ' . \Illuminate\Support\Carbon::parse($item['first_rej_date'])->format('M d').',' : ' ' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ $item['second_send_date'] ? 'Sent: ' . \Illuminate\Support\Carbon::parse($item['second_send_date'])->format('M d').',' : ' ' }}
                                    {{ $item['second_app_date'] ? 'App: ' . \Illuminate\Support\Carbon::parse($item['second_app_date'])->format('M d').',' : ' ' }}
                                    {{ $item['second_rej_date'] ? 'Rej: ' . \Illuminate\Support\Carbon::parse($item['second_rej_date'])->format('M d').',' : ' ' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ $item['third_send_date'] ? 'Sent: ' . \Illuminate\Support\Carbon::parse($item['third_send_date'])->format('M d').',' : ' ' }}
                                    {{ $item['third_app_date'] ? 'App: ' .  \Illuminate\Support\Carbon::parse($item['third_app_date'])->format('M d').',' : ' ' }}
                                    {{ $item['third_rej_date'] ? 'Rej: ' . \Illuminate\Support\Carbon::parse($item['third_rej_date'])->format('M d').',' : ' ' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">{{ \Illuminate\Support\Carbon::parse($item['booked_date_by_fty'])->format('M d') ?? ' ' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ \Illuminate\Support\Carbon::parse($item['pi_status_lc_due_date'])->format('M d') ?? ' ' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['etd'] ?? ' ' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['eta'] ?? ' ' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ \Illuminate\Support\Carbon::parse($item['in_house_date'])->format('M d') ?? ' ' }}</td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ $item['pp_yds_in_house_send_date'] ? 'Sent: ' . \Illuminate\Support\Carbon::parse($item['pp_yds_in_house_send_date'])->format('M d').',' : ' ' }}
                                    {{ $item['pp_yds_in_house_recved_date'] ? 'received: ' . \Illuminate\Support\Carbon::parse($item['pp_yds_in_house_recved_date'])->format('M d').',' : ' ' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">{{ \Illuminate\Support\Carbon::parse($item['bulk_shade_band_receive_date'])->format('M d') ?? ' ' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ \Illuminate\Support\Carbon::parse($item['fabric_test_receive_date'])->format('M d') ?? ' ' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ \Illuminate\Support\Carbon::parse($item['ship_docs_receive_date'])->format('M d') ?? ' ' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['remarks'] ?? ' ' }}</td>
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
                        <tr>
                            <td colspan="20" style="background-color: lightskyblue; border: 1px solid black;">Trims Details</td>
                        </tr>
                        <tr style="height: 40px; background-color: #4fdfee">
                            <td align="left" style="background-color: lightskyblue; border: 1px solid black; padding-left: 5px" colspan="4">
                                {{ $trimsData['item_name'] ?? ' ' }}
                            </td>
                            <td align="left" style="background-color: lightskyblue; border: 1px solid black; padding-left: 5px" colspan="2">Supplier :</td>
                            <td align="left" style="background-color: lightskyblue; border: 1px solid black; padding-left: 5px" colspan="4">
                                {{ $trimsData['supplier_value'] ?? ' ' }}

                            </td>
                            <td align="left" style="background-color: lightskyblue; border: 1px solid black; padding-left: 5px" colspan="10">
                                Description: &nbsp; {{ $trimsData['description'] ?? ' ' }}
                                UOM:{{ $trimsData['uom_name'] ?? ' ' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: lightblue; border: 1px solid black;">{{ $trimsData['body_color'] ?? 'Body Color' }}</td>
{{--                            <td style="background-color: lightblue; border: 1px solid black;">Body Color</td>--}}
                            <td style="background-color: lightblue; border: 1px solid black; min-width: 75px">{{ $trimsData['thread_color'] ?? ' ' }}</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Lay Out Submission Date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Approve</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Quality Submission Date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Approval Date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">1 st Submit sent date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Approval Date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">2nd submit sent dt</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Approval Date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">3rd submit dt</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Approval Date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">4th submit dt</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Approval Date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Booking Date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Pi Issue Date</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Bulk in house status</td>
                            <td style="background-color: lightblue; border: 1px solid black;">BULK thread mock up submit dt</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Approval Status</td>
                            <td style="background-color: lightblue; border: 1px solid black;">Remarks</td>
                        </tr>
                        @foreach($trimsData['details'] as $key => $item)
                            <tr>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['body_color'] ?? '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['thread_color_name'] ?? '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ isset($item['lay_out_submission_sent_date']) ? 'Sent: '. \Illuminate\Support\Carbon::parse($item['lay_out_submission_sent_date'])->format('M d').',': '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ isset($item['lay_out_submission_app_date']) ? 'App: '. \Illuminate\Support\Carbon::parse($item['lay_out_submission_app_date'])->format('M d').',': '' }}
                                    {{ isset($item['lay_out_submission_rej_date']) ? 'Rej: '. \Illuminate\Support\Carbon::parse($item['lay_out_submission_rej_date'])->format('M d').',': '' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">{{ isset($item['quality_submission_sent_date']) ? 'Sent: '. \Illuminate\Support\Carbon::parse($item['quality_submission_sent_date'])->format('M d').',': '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ isset($item['quality_submission_app_date']) ? 'App: '. \Illuminate\Support\Carbon::parse($item['quality_submission_app_date'])->format('M d').',': '' }}
                                    {{ isset($item['quality_submission_rej_date']) ? 'Rej: '. \Illuminate\Support\Carbon::parse($item['quality_submission_rej_date'])->format('M d').',': '' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">{{ isset($item['first_submit_sent_date']) ? 'Sent: '. \Illuminate\Support\Carbon::parse($item['first_submit_sent_date'])->format('M d').',': '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ isset($item['first_submit_app_date']) ? 'App: '. \Illuminate\Support\Carbon::parse($item['first_submit_app_date'])->format('M d').',': '' }}
                                    {{ isset($item['first_submit_rej_date']) ? 'Rej: '. \Illuminate\Support\Carbon::parse($item['first_submit_rej_date'])->format('M d').',': '' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">{{ isset($item['second_submit_sent_date']) ? 'Sent: '. \Illuminate\Support\Carbon::parse($item['second_submit_sent_date'])->format('M d').',': '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ isset($item['second_submit_app_date']) ? 'App: '. \Illuminate\Support\Carbon::parse($item['second_submit_app_date'])->format('M d').',': '' }}
                                    {{ isset($item['second_submit_rej_date']) ? 'Rej: '. \Illuminate\Support\Carbon::parse($item['second_submit_rej_date'])->format('M d').',': '' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">{{ isset($item['third_submit_sent_date']) ? 'Sent: '. \Illuminate\Support\Carbon::parse($item['third_submit_sent_date'])->format('M d').',': '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ isset($item['third_submit_app_date']) ? 'App: '. \Illuminate\Support\Carbon::parse($item['third_submit_app_date'])->format('M d').',': '' }}
                                    {{ isset($item['third_submit_rej_date']) ? 'Rej: '. \Illuminate\Support\Carbon::parse($item['third_submit_rej_date'])->format('M d').',': '' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">{{ isset($item['fourth_submit_sent_date']) ? 'Sent: '. \Illuminate\Support\Carbon::parse($item['fourth_submit_sent_date'])->format('M d').',': '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">
                                    {{ isset($item['fourth_submit_app_date']) ? 'App: '. \Illuminate\Support\Carbon::parse($item['fourth_submit_app_date'])->format('M d').',': '' }}
                                    {{ isset($item['fourth_submit_rej_date']) ? 'Rej: '. \Illuminate\Support\Carbon::parse($item['fourth_submit_rej_date'])->format('M d').',': '' }}
                                </td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['booking_date'] ?? '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['pi_issue_date'] ?? '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['bulk_in_house_status'] ?? '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['bulk_thread_mock_up_submit_date'] ?? '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['approval_status'] ?? '' }}</td>
                                <td style="height: 20px; border: 1px solid black;">{{ $item['remarks'] ?? '' }}</td>
                            </tr>
                        @endforeach
                        </thead>
                    </table>
                </div>
            @endforeach
        @endif
    </div>

    @if($wipReport['sample_status']))
    <div class="row" style="margin-top: 10px">
        <div style=" padding: 1%;" class="table-responsive">
            <table>
                <thead>
                <tr>
                    <td style="background-color: lightblue; border: 1px solid black;">Tech Pack Issue date</td>
                    <td style="background-color: lightblue; border: 1px solid black;">Styling sample App. Date</td>
                    <td style="background-color: lightblue; border: 1px solid black;">1 Fit Send</td>
                    <td style="background-color: lightblue; border: 1px solid black;">App. Date</td>
                    <td style="background-color: lightblue; border: 1px solid black;">2nd Fit Send</td>
                    <td style="background-color: lightblue; border: 1px solid black;">App. Date</td>
                    <td style="background-color: lightblue; border: 1px solid black;">3rd Fit Send</td>
                    <td style="background-color: lightblue; border: 1px solid black;">App. Date</td>
                    <td style="background-color: lightblue; border: 1px solid black;">PPS send</td>
                    <td style="background-color: lightblue; border: 1px solid black;">Appv dt</td>
                    <td style="background-color: lightblue; border: 1px solid black;">AD Sample</td>
                    <td style="background-color: lightblue; border: 1px solid black;">Appv dt</td>
                    <td style="background-color: lightblue; border: 1px solid black;">TOP sent dt</td>
                    <td style="background-color: lightblue; border: 1px solid black;">Appv dt</td>
                    <td style="background-color: lightblue; border: 1px solid black;">Remarks</td>
                </tr>
                <tr>
                    <td>{{ isset( $wipReport['sample_status']['tech_pack_issue_date']) ?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['styling_sample_app_date']) ?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['first_fit_send_date']) ?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['first_app_date'] )?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['second_fit_send_date']) ?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['second_app_date'] )?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['third_fit_send_date']) ?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['third_app_date']) ?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['pps_send_date'] )?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['forth_app_date']) ?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['ad_sample'] )?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['fifth_app_date'] )?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['top_sent_date']) ?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['sixth_app_date']) ?? '' }}</td>
                    <td>{{ isset( $wipReport['sample_status']['remarks']) ?? '' }}</td>
                </tr>
                <tr>
                    <td colspan="3">Special Notes :</td>
                    <td colspan="3">{{ isset( $wipReport['sample_status']['special_notes']) ?? '' }}</td>
                    <td colspan="2">Next Meeting Due on :</td>
                    <td colspan="7">{{ isset( $wipReport['sample_status']['next_meeting_due_on']) ?? '' }}</td>
                </tr>

                </thead>
            </table>
        </div>
    </div>
    @endif

</div>
