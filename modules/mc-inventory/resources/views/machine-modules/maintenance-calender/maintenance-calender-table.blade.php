<table class="reportTable">
    <thead>
    <tr>
        <th><b>SL</b></th>
        <th><b>M/C Name</b></th>
        <th><b>M/C No</b></th>
        <th><b>M/C Model No</b></th>
        <th><b>Last Service Date</b></th>
        <th><b>Last Service Description</b></th>
        <th><b>Next Service Date</b></th>
        <th><b>Unit</b></th>
    </tr>
    </thead>
    <tbody>
        @foreach($maintenances as $maintenance)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td class="text-center">{{$maintenance->name}}</td>
                <td class="text-center">{{$maintenance->barcode}}</td>
                <td class="text-center">{{$maintenance->model_no}}</td>
                <td class="text-center">{{$maintenance->last_maintenance}}</td>
                <td class="text-center">{{$maintenance->description}}</td>
                <td class="text-center">{{$maintenance->next_maintenance}}</td>
                <td class="text-center">{{$maintenance->unit->name ?? ''}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
