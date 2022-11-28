<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sample List</title>
    @include('merchandising::download.include.report-style')
</head>

<body>
@include('merchandising::download.include.report-header')
@include('merchandising::download.include.report-footer')
    <main>
        <h4 align="center" style="margin-top: 5px">Sample List</h4>
        <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;margin-top: 10px">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Buyer</th>
                    <th>Buying Agent</th>
                    <th>Season</th>
                    <th>Ref. No</th>
                    <th>Dealing Merchant</th>
                    <th>Team Lead</th>
                    <th>Receive Date</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody style="font-size: 10px">
                @php
                  $i=1;
                @endphp
            @foreach($sample_lists->chunk(100) as $samplelists)
                @foreach($samplelists as $sample_list)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{$sample_list->buyer ? $sample_list->buyer->name : 'N/A' }}</td>
                    <td>{{$sample_list->agent ? $sample_list->agent->buying_agent_name : 'N/A'}}</td>
                    <td>{{$sample_list->season}}</td>
                    <td>{{$sample_list->sample_ref_no ? $sample_list->sample_ref_no : 'N/A' }}</td>
                    <td>{{$sample_list->dealingMerchant->first_name ?? 'N/A'}}</td>
                    <td>{{$sample_list->teamLead->team_name ?? 'N/A'}}</td>
                    <td>{{date('d M Y',strtotime($sample_list->receive_date))}}</td>
                    <td>{{$sample_list->remarks ?? '--'}}</td>
                </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </main>

</body>

</html>
