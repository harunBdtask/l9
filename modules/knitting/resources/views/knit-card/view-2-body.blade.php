<table class="reportTable">
    <tbody>
        <tr>
            <td style="width: 10%" class="text-left">Machine No</td>
            <td style="width: 30%" class="text-left">{{ $data->machine->machine_no ?? '' }}</td>
            <td style="width: 10%" class="text-left">Buyer Name</td>
            <td style="width: 30%" class="text-left"> {{ $data->buyer->name ?? '' }}</td>
            <td style="width: 5%" class="text-left">Date</td>
            <td>  {{  $date }}</td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left">Party Name</td>
            <td style="width: 30%" class="text-left">{{ $data->program->party_name }}</td>
            <td style="width: 10%" class="text-left">FSO No</td>
            <td style="width: 30%" class="text-left">{{ $data->sales_order_no }}</td>
            <td style="width: 5%" class="text-left">Shift</td>
            <td>{{ $shift }}</td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left">Machine Dia</td>
            <td style="width: 30%" class="text-left">{{ $data->program->machine_dia }}</td>
            <td style="width: 10%" class="text-left">Program No</td>
            <td style="width: 30%" class="text-left">{{ $data->program->program_no }}</td>
            <td style="width: 5%" class="text-left"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left">Machine Gauge</td>
            <td style="width: 30%" class="text-left">{{ $data->program->machine_gg }}</td>
            <td style="width: 10%" class="text-left">Program Quantity</td>
            <td style="width: 30%" class="text-left">{{ $data->program->program_qty }}</td>
            <td style="width: 5%" class="text-left"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left">Finish Dia</td>
            <td style="width: 30%" class="text-left">{{ $data->program->finish_fabric_dia }}</td>
            <td style="width: 10%" class="text-left">Production Target</td>
            <td style="width: 30%" class="text-left">{{ $data->production_target_qty }}</td>
            <td style="width: 5%" class="text-left"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left">Fabric Description</td>
            <td style="width: 30%" class="text-left">{{ $data->program->fabric_description }}</td>
            <td style="width: 10%" class="text-left">Production Location</td>
            <td style="width: 30%" class="text-left">{{ $fabricSalesOrder->location }}</td>
            <td style="width: 5%" class="text-left"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left">RPM</td>
            <td style="width: 30%" class="text-left">{{ $data->machine->machine_rpm }}</td>
            <td style="width: 10%" class="text-left">Balance</td>
            <td style="width: 30%" class="text-left"> {{ (double)($data->program->program_qty ?? 0) - (double)$data->assign_qty }}</td>
            <td style="width: 5%" class="text-left"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left">Stitch Length</td>
            <td style="width: 30%" class="text-left">{{ $data->program->stitch_length }}</td>
            <td style="width: 10%" class="text-left">Knit Card Date</td>
            <td style="width: 30%" class="text-left">{{  $data->knit_card_date }}</td>
            <td style="width: 5%" class="text-left"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left">Fabric GSM</td>
            <td style="width: 30%" class="text-left"></td>
            <td style="width: 10%" class="text-left">Delivery Date</td>
            <td style="width: 30%" class="text-left">{{ $fabricSalesOrder->delivery_date }}</td>
            <td style="width: 5%" class="text-left"></td>
            <td></td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left">Grey GSM</td>
            <td style="width: 30%" class="text-left"></td>
            <td style="width: 10%" class="text-left"  rowspan="3">Remarks</td>
            <td style="width: 30%" class="text-left"  rowspan="3">{{ $data->remarks }}</td>
            <td style="width: 5%"  class="text-left" rowspan="3"></td>
            <td rowspan="3" class="text-left"></td>
        </tr>
        <tr>
            <td style="width: 10%" class="text-left" >Finished GSM</td>
            <td style="width: 30%" class="text-left" >{{ $data->gsm }}</td>

        </tr>
        <tr>
            <td style="width: 10%" class="text-left">Color</td>
            <td style="width: 30%" class="text-left">{{ $data->color }}</td>
        </tr>
    </tbody>

</table>

<table class="reportTable" style="margin-top: 5%">
    <thead>
    <tr>
        <th colspan="7">YARN</th>
    </tr>
    <tr>
        <th>COUNT</th>
        <th>Type</th>
        <th>COMPOSITION</th>
        <th>BRAND</th>
        <th>LOT</th>
        <th>REF NO </th>
        <th>VDQ</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data->yarnDetails as $detail)
        <tr>
            <td>{{ $detail->yarn_count->yarn_count }}</td>
            <td>{{ $detail->yarn_type->name }}</td>
            <td>{{ $detail->yarn_composition->yarn_composition }}</td>
            <td>{{ $detail->yarn_brand }}</td>
            <td>{{ $detail->yarn_lot }}</td>
            <td>{{ $detail->ref_no }}</td>
            <td>{{ $detail->vdq }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

