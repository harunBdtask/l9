@if(count($data->fleece_info['details']) > 0)
<div class="row">
    <div class="col-md-12">
    <h6 style="font-size: 14px; margin-top: 20px; margin-bottom: 5px;">Fleece Info</h6>
        <table class="reportTable">
            <thead>
            <tr style="background-color: #eee">
                <th>Fab. Info.</th>
                <th>Count</th>
                <th>In Percent</th>
                <th>Percent wise Qty.</th>
            </tr>
            </thead>
            <tbody>
                @foreach($data->fleece_info['details'] as $fleece)
                    <tr style="text-align: center;">
                        <td>{{ $fleece['type'] }}</td>
                        <td>{{ $fleece['yarn_count'] }}</td>
                        <td>{{ $fleece['percentage'] }}</td>
                        <td>{{ $fleece['qty'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if(count($data->collarCuffs) > 0)
<div class="row">
    <div class="col-md-12">
    <h6 style="font-size: 14px; margin-top: 20px; margin-bottom: 5px;">Collar Cuff Info</h6>
        <table class="reportTable">
            <thead>
            <tr style="background-color: #eee">
                <th>Gmts. Color</th>
                <th>Gmts. Size</th>
                <th>Book. Item Size</th>
                <th>Prog. Item Size</th>
                <th>Booking Qty. PCS</th>
                <th>Excess Percentage</th>
                <th>Prog. Qty. PCS</th>
            </tr>
            </thead>
            <tbody>
                @foreach($data->collarCuffs as $collarCuff)
                    <tr style="text-align: center;">
                        <td>{{ $collarCuff['gmt_color'] }}</td>
                        <td>{{ $collarCuff['size'] }}</td>
                        <td>{{ $collarCuff['booking_item_size'] }}</td>
                        <td>{{ $collarCuff['program_item_size'] }}</td>
                        <td>{{ $collarCuff['booking_qty'] }}</td>
                        <td>{{ $collarCuff['excess_percentage'] }}</td>
                        <td>{{ $collarCuff['program_qty'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <table class="reportTable">
            <thead>
            <tr style="background-color: #eee; text-align: center;">
                <th>Program No</th>
                <th>Program Qty</th>
                <th>Stitch Length</th>
                <th>Program Date</th>
                <th>Color</th>
                <th>Color Qty</th>
                <th>Yarn Description</th>
                <th>Yarn Lot</th>
                <th>Ref. No</th>
                <th>Yarn Allocated Qty</th>
                <th>Req. Qty</th>
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
                                        <td rowspan="{{ $data->total_row }}">{{ $data->program_no }}</td>
                                        <td rowspan="{{ $data->total_row }}">{{ $data->program_qty }}</td>
                                        <td rowspan="{{ $data->total_row }}">{{ $data->stitch_length }}</td>
                                        <td rowspan="{{ $data->total_row }}">{{ $data->program_date }}</td>
                                        @endif
                                        <td rowspan="{{ count($yarn) }}">{{ $color_qty_value['item_color'] }}</td>
                                        <td rowspan="{{ count($yarn) }}">{{ $color_qty_value['program_qty'] }}</td>
                                        <td>{{ $value['yarn_description'] }}</td>
                                        <td>{{ $value['yarn_lot'] }}</td>
                                        <td>{{ $value['product_code'] }}</td>
                                        <td>{{ $value['allocated_qty'] }}</td>
                                        <td>{{ $value['previous_total_yarn_requisition_qty'] }}</td>
                                        <td></td>
                                    </tr>
                                @else
                                    <tr style="text-align: center;">
                                        <td>{{ $value['yarn_description'] }}</td>
                                        <td>{{ $value['yarn_lot'] }}</td>
                                        <td>{{ $value['product_code'] }}</td>
                                        <td>{{ $value['allocated_qty'] }}</td>
                                        <td>{{ $value['previous_total_yarn_requisition_qty'] }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        @php
            $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        @endphp
        <strong>Program Qty In word: </strong>{{ ucwords($digit->format($data->program_qty)) }}
    </div>
</div>
