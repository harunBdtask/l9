<table class="reportTable">
    <thead>
    <tr>
        <td style="background-color: aliceblue" rowspan="2"><b>MONTH</b></td>
        <td style="background-color: #cfe8fd" colspan="{{ collect($cuttingFloors)->count()+1 }}"><b>CUTTING SECTION</b>
        </td>
        <td style="background-color: aliceblue" colspan="{{ (collect($printEmbrFloors)->count()*2)+2 }}">
            <b>PRINT SECTION</b>
        </td>
        <td style="background-color: #cfe8fd" colspan="{{ (collect($printEmbrFloors)->count()*2)+2 }}">
            <b>EMBR SECTION</b>
        </td>
        <td style="background-color: aliceblue" colspan="{{ collect($inputFloors)->count()+1 }}"><b>INPUT SECTION</b>
        </td>
        <td rowspan="2" style="background-color: aliceblue"><b>REMARKS</b></td>
    </tr>
    <tr>
        @foreach($cuttingFloors as $floor)
            <td style="background-color: #cfe8fd"><b>{{ strtoupper($floor->floor_no) }}</b></td>
        @endforeach
        <td style="background-color: #cfe8fd"><b>TOTAL CUT.</b></td>
        @foreach($printEmbrFloors as $floor)
            <td style="background-color: aliceblue"><b>{{ strtoupper($floor->floor_no) }}</b></td>
        @endforeach
        <td style="background-color: aliceblue"><b>TOTAL PRINT SENT</b></td>
        @foreach($printEmbrFloors as $floor)
            <td style="background-color: aliceblue"><b>{{ strtoupper($floor->floor_no) }}</b></td>
        @endforeach
        <td style="background-color: aliceblue"><b>TOTAL PRINT REC.</b></td>
        @foreach($printEmbrFloors as $floor)
            <td style="background-color: #cfe8fd"><b>{{ strtoupper($floor->floor_no) }}</b></td>
        @endforeach
        <td style="background-color: #cfe8fd"><b>TOTAL EMBR. SENT</b></td>
        @foreach($printEmbrFloors as $floor)
            <td style="background-color: #cfe8fd"><b>{{ strtoupper($floor->floor_no) }}</b></td>
        @endforeach
        <td style="background-color: #cfe8fd"><b>TOTAL EMBR. REC.</b></td>
        @foreach($inputFloors as $floor)
            <td style="background-color: aliceblue"><b>{{ strtoupper($floor->floor_no) }}</b></td>
        @endforeach
        <td style="background-color: aliceblue"><b>TOTAL INPUT</b></td>
    </tr>
    </thead>
    <tbody>
    @php
        $totalPrintSent = [];
        $totalPrintRec = [];
        $totalEmbrSent = [];
        $totalEmbrRec = [];
    @endphp
    @foreach($report as $key => $data)
        <tr>
            <td>{{ strtoupper($key) }}</td>
            @foreach($cuttingFloors as $floor)
                <td style="background-color: #cfe8fd">{{ $data[$floor->floor_no] }}  </td>
            @endforeach
            <td style="background-color: #cfe8fd">{{ $data['total_cutting'] }}  </td>
            @foreach($printEmbrFloors as $floor)
                @php
                    $totalPrintSent[$floor->floor_no] = ($totalPrintSent[$floor->floor_no] ?? 0) +
                        $data['print_sent'][$floor->floor_no];
                @endphp
                <td style="background-color: aliceblue">{{ $data['print_sent'][$floor->floor_no] }}  </td>
            @endforeach
            <td style="background-color: aliceblue">{{ $data['total_print_sent'] }}  </td>
            @foreach($printEmbrFloors as $floor)
                @php
                    $totalPrintRec[$floor->floor_no] = ($totalPrintRec[$floor->floor_no] ?? 0) +
                        $data['print_received'][$floor->floor_no];
                @endphp
                <td style="background-color: aliceblue">{{ $data['print_received'][$floor->floor_no] }}  </td>
            @endforeach
            <td style="background-color: aliceblue">{{ $data['total_print_rec'] }}  </td>
            @foreach($printEmbrFloors as $floor)
                @php
                    $totalEmbrSent[$floor->floor_no] = ($totalEmbrSent[$floor->floor_no] ?? 0) +
                        $data['embr_sent'][$floor->floor_no];
                @endphp
                <td style="background-color: #cfe8fd">{{ $data['embr_sent'][$floor->floor_no] }}  </td>
            @endforeach
            <td style="background-color: #cfe8fd">{{ $data['total_embr_sent'] }}  </td>
            @foreach($printEmbrFloors as $floor)
                @php
                    $totalEmbrRec[$floor->floor_no] = ($totalEmbrRec[$floor->floor_no] ?? 0) +
                        $data['embr_received'][$floor->floor_no];
                @endphp
                <td style="background-color: #cfe8fd">{{ $data['embr_received'][$floor->floor_no] }}  </td>
            @endforeach
            <td style="background-color: #cfe8fd">{{ $data['total_embr_rec'] }}  </td>
            @foreach($inputFloors as $floor)
                <td style="background-color: aliceblue">{{ $data[$floor->floor_no] }}  </td>
            @endforeach
            <td style="background-color: aliceblue">{{ $data['total_input'] }}  </td>
            <td></td>
        </tr>
    @endforeach
    <tr style="background-color: #cbcaca">
        <td><b>G. Total</b></td>
        @foreach($cuttingFloors as $floor)
            <td>
                <b>{{ collect($report)->pluck($floor->floor_no)->sum() }}  </b>
            </td>
        @endforeach
        <td><b>{{ collect($report)->sum('total_cutting') }}  </b></td>
        @foreach($printEmbrFloors as $floor)
            <td>
                <b>{{ $totalPrintSent[$floor->floor_no] }}  </b>
            </td>
        @endforeach
        <td>
            <b>{{ collect($report)->sum('total_print_sent') }}  </b>
        </td>
        @foreach($printEmbrFloors as $floor)
            <td>
                <b>{{ $totalPrintRec[$floor->floor_no] }}  </b>
            </td>
        @endforeach
        <td>
            <b>{{ collect($report)->sum('total_print_rec') }}  </b>
        </td>
        @foreach($printEmbrFloors as $floor)
            <td>
                <b>{{ $totalEmbrSent[$floor->floor_no] }}  </b>
            </td>
        @endforeach
        <td>
            <b>{{ collect($report)->sum('total_embr_sent') }}  </b>
        </td>
        @foreach($printEmbrFloors as $floor)
            <td>
                <b>{{ $totalEmbrRec[$floor->floor_no] }}  </b>
            </td>
        @endforeach
        <td>
            <b>{{ collect($report)->sum('total_embr_rec') }}  </b>
        </td>
        @foreach($inputFloors as $floor)
            <td>
                <b>{{ collect($report)->pluck($floor->floor_no)->sum() }}  </b>
            </td>
        @endforeach
        <td><b>{{ collect($report)->sum('total_input') }}  </b></td>
        <td></td>
    </tr>
    </tbody>
</table>
