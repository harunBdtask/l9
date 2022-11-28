@include('hr::report-style')
@include('hr::reports.include.header')

<div class="row">
    <table class="reportTable">
        <tr>
            <th>UserID</th>
            <th>Date</th>
            <th>Att In</th>
            <th>Att Out</th>
            <th>Status</th>
        </tr>
        @if(count($attendances))
            @foreach($attendances as $att)
                <tr>
                    <td>{{ $att->userid}}</td>
                    <td>{{ $att->date  }}</td>
                    <td>{{ $att->att_in}}</td>
                    <td>{{ $att->att_out }}</td>
                    <td>{{ $att->status}}</td>
                </tr>
            @endforeach
        @else
            <td colspan="5" class="text-center">No Data Available!</td>
        @endif
    </table>
</div>
