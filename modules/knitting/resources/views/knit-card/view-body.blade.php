<table class="reportTable">
    <tr>
        <td colspan="3" style="text-align: left;">
            <span><b>ORDER QTY: </b>{{ $data->assign_qty ?? '' }}</span>
        </td>
        <td colspan="3" style="text-align: right;">
            <span><b>DATE: </b>{{ date('d/m/Y', strtotime($data->knit_card_date)) }}</span>
        </td>
    </tr>

    <tr>
        <th>MACHINE NO:</th>
        <td><b>M/C NO: </b>{{ $data->machine->machine_no ?? '' }}</td>
        <td><b>RPM: </b>{{ $data->machine->machine_rpm ?? '' }}</td>
        <th colspan="3" style="width: 50%;">REMARKS</th>
    </tr>

    <tr>
        <th>PARTY NAME:</th>
        <td colspan="2">{{ $data->program->party_name ?? '' }}</td>
        <td rowspan="11" colspan="3">{{ $data->remarks ?? '' }}</td>
    </tr>

    <tr>
        <th>BUYER:</th>
        <td colspan="2">{{ $data->buyer->name ?? '' }}</td>
    </tr>

    <tr>
        <th>BOOKING NO:</th>
        <td colspan="2">{{ $data->planInfo->booking_no ?? '' }}</td>
    </tr>

    <tr>
        <th>ORDER NO:</th>
        <td colspan="2">{{ $data->planInfo->po_no ?? '' }}</td>
    </tr>

    <tr>
        <th>FAB DES:</th>
        <td colspan="2">{{ $data->planInfo->fabric_description ?? '' }}</td>
    </tr>


    <tr>
        <th>Stitch Length:</th>
        <td colspan="2">{{ $data->program->stitch_length ?? '' }}</td>
    </tr>

    <tr>
        <th>M/C DIA X GG:</th>
        <td colspan="2">{{ $data->machine->machine_dia }} X {{ $data->machine->machine_gg ?? '' }}</td>
    </tr>

    <tr>
        <th>F/DIA:</th>
        <td colspan="2">{{ $data->planInfo->fabric_dia ?? '' }}</td>
    </tr>

    <tr>
        <th>F/GSM:</th>
        <td>{{ $data->planInfo->fabric_gsm ?? '' }}</td>
        <th>NOTE: </th>
    </tr>

    <tr>
        <th>COLOR:</th>
        <td colspan="2">{{ $data->color ?? '' }}</td>
    </tr>

    <tr>
        <th>DELIVERY DATE:</th>
        <td colspan="2"></td>
    </tr>
</table>
<br>
<table class="reportTable">
    <tr>
        <th style="width: 18%">COUNT</th>
        <th style="width: 18%">TYPE</th>
        <th style="width: 18%">BRAND</th>
        <th style="width: 18%">LOT</th>
        <th style="width: 18%">Ref. No</th>
        <th>VDQ</th>
    </tr>
    @foreach($yarnDetails as $yarn)
        <tr>
            <td>{{ $yarn['yarn_count'] }}</td>
            <td>{{ $yarn['yarn_type'] }}</td>
            <td>{{ $yarn['yarn_brand'] }}</td>
            <td>{{ $yarn['yarn_lot'] }}</td>
            <td>{{ $yarn['reference_no'] }}</td>
            <td>{{ $yarn['vdq'] }}</td>
        </tr>
    @endforeach
</table>
