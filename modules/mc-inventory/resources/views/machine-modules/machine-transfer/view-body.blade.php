<table class="reportTable" style="margin-top: 40px; margin-bottom: 0">
    <tbody>
    <tr>
        <th style="background-color: rgb(242 242 242) !important;height: 30px;" colspan="4">{{ factoryName() }}</th>
    </tr>
    <tr>
        <th colspan="4" style="height: 30px;">{{ factoryAddress() }}</th>
    </tr>
    <tr>
        <th colspan="4" style="height: 30px;">Machine Transfer Challan</th>
    </tr>
    <tr>
        <th style="width: 100px">From</th>
        <th style="width: 400px">{{ factoryName() }}</th>
        <th style="width: 100px">To</th>
        <th style="width: 400px">{{ $transfer->machineTransferTo->location_name }}</th>
    </tr>
    <tr>
        <th rowspan="2" style="height: 180px;">Address</th>
        <th rowspan="2">{{ factoryAddress() }}</th>
        <th>Date</th>
        <th>{{ \Carbon\Carbon::make($transfer->created_at)->format('d M Y') }}</th>
    </tr>
    <tr>
        <th>Attention & Contact Number</th>
        <th>{{ $transfer->attention }}</th>
    </tr>
    </tbody>
</table>
<table class="reportTable">
    <thead>
    <tr>
        <th style="background-color: rgb(242 242 242) !important;width: 100px">SI</th>
        <th style="background-color: rgb(242 242 242) !important;width: 400px" colspan="2">Machine Particulars</th>
        <th style="background-color: rgb(242 242 242) !important;width: 100px">QTY</th>
        <th style="background-color: rgb(242 242 242) !important;width: 400px">Reason</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td rowspan="6" class="text-center">{{ collect($transfer->id)->count() }}</td>
        <td>Machine Type</td>
        <td>{{ $transfer->machine->type->machine_type ?? '' }}</td>
        <td rowspan="6" class="text-center">{{ collect($transfer->machine->barcode)->count() }}</td>
        <td rowspan="6" class="text-center">{{ $transfer->reason }}</td>
    </tr>
    <tr>
        <td>Machine Sub Type</td>
        <td>{{ $transfer->machine->subtype->machine_sub_type ?? '' }}</td>
    </tr>
    <tr>
        <td>Machine Make</td>
        <td>{{ $transfer->machine->brand->name ?? '' }}</td>
    </tr>
    <tr>
        <td>Machine Model</td>
        <td>{{ $transfer->machine->model_no ?? '' }}</td>
    </tr>
    <tr>
        <td>Machine Serial No</td>
        <td>{{ $transfer->machine->serial_no ?? '' }}</td>
    </tr>
    <tr>
        <td>Transfer From</td>
        <td></td>
    </tr>
    </tbody>
</table>
