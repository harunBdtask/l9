<form action="/date-wise-finishing-target" method="post">
    @csrf
    <input type="hidden" value="{{$productionDate}}" name="production_date">

    @if(isset($floorId))
        <input type="hidden" value="{{$floorId}}" name="finishing_floor_id">
        <table class="reportTable">
            <thead>
            <tr>
                <th>Finishing Floor</th>
                <th>Finishing Tables</th>
                <th>Iron Target/Hr</th>
                <th>QC Pass Target/Hr</th>
                <th>Poly Target/Hr</th>
                <th>Packing Target/Hr</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $key => $row)

                <tr>
                    <td>
                        <input type="text" disabled="disabled" class="form-control form-control-sm"
                               value="{{$row->floor->name}}">
                    </td>
                    <td>
                        <input type="text" disabled="disabled" class="form-control form-control-sm"
                               value="{{$row->name}}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               name="iron_target[{{$row->id}}]"
                               value="{{ collect($targets)->where('finishing_floor_id', $row->floor_id)
                                            ->where('finishing_table_id', $row->id)
                                            ->first()['iron_target'] ?? 0 }}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               name="qc_pass_target[{{$row->id}}]"
                               value="{{ collect($targets)->where('finishing_floor_id', $row->floor_id)
                                            ->where('finishing_table_id', $row->id)
                                            ->first()['qc_pass_target'] ?? 0 }}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               name="poly_target[{{$row->id}}]"
                               value="{{ collect($targets)->where('finishing_floor_id', $row->floor_id)
                                            ->where('finishing_table_id', $row->id)
                                            ->first()['poly_target'] ?? 0 }}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               name="ctn_target[{{$row->id}}]"
                               value="{{ collect($targets)->where('finishing_floor_id', $row->floor_id)
                                            ->where('finishing_table_id', $row->id)
                                            ->first()['ctn_target'] ?? 0 }}">
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    @else
        <table class="reportTable">
            <thead>
            <tr>
                <th>Finishing Floor</th>
                <th>Iron Target/Hr</th>
                <th>QC Pass Target/Hr</th>
                <th>Poly Target/Hr</th>
                <th>Packing Target/Hr</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $key => $row)

                <tr>
                    <td>
                        <input type="text" disabled="disabled" class="form-control form-control-sm"
                               value="{{$row->name}}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               name="iron_target[{{$row->id}}]"
                               value="{{ collect($targets)->where('finishing_floor_id', $row->id)->first()['iron_target'] ?? 0 }}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               name="qc_pass_target[{{$row->id}}]"
                               value="{{ collect($targets)->where('finishing_floor_id', $row->id)->first()['qc_pass_target'] ?? 0 }}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               name="poly_target[{{$row->id}}]"
                               value="{{ collect($targets)->where('finishing_floor_id', $row->id)->first()['poly_target'] ?? 0 }}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm"
                               name="ctn_target[{{$row->id}}]"
                               value="{{ collect($targets)->where('finishing_floor_id', $row->id)->first()['ctn_target'] ?? 0 }}">
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    @endif


    <div class="text-center">
        <button class="btn btn-success btn-sm">Submit</button>
    </div>
</form>
