<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <table class="reportTable" style="border: none !important;">
            <tr>
                <td style="width: 40%; border: none !important;">
                    <table class="reportTable">
                        <tr>
                            <td>
                                <strong>Program No: </strong>
                            </td>
                            <td>{{ $data->program_no }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Program Date: </strong>
                            </td>
                            <td>{{ $data->program_date }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Knitting Source: </strong>
                            </td>
                            <td>{{ $knittingSources[$data->knitting_source_id] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Knitting Party: </strong>
                            </td>
                            <td>{{ $data->party_name }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Stitch Length: </strong>
                            </td>
                            <td>{{ $data->stitch_length }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Start Date: </strong>
                            </td>
                            <td>{{ $data->start_date }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>End Date: </strong>
                            </td>
                            <td>{{ $data->end_date }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 20%; border: none !important;"></td>
                <td style="width: 40%; border: none !important;">
                    <table class="reportTable">
                        <tr>
                            <td>
                                <strong>Color Range: </strong>
                            </td>
                            <td>{{ $data->colorRange->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Finish Fabric Dia: </strong>
                            </td>
                            <td>{{ $data->finish_fabric_dia }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Machine Dia: </strong>
                            </td>
                            <td>{{ $data->machine_dia }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Machine GG: </strong>
                            </td>
                            <td>{{ $data->machine_gg }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Machine Type: </strong>
                            </td>
                            <td>{{ $data->machine_type_info }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Feeder: </strong>
                            </td>
                            <td>{{ $data['feeder_text'] }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Remarks: </strong>
                            </td>
                            <td>{{ $data->remarks }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

@includeIf('knitting::program.table')
