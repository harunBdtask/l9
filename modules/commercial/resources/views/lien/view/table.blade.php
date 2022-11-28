<table>
    <tbody>
        <tr>
            <th>Company Name</th>
            <td>{{$factory['factory_name']}}</td>
        </tr>
        <tr>
            <th>Lien No</th>
            <td>{{$lien_no}}</td>
        </tr>
        <tr>
            <th>Advising Bank</th>
            <td>{{$bank['name']}}</td>
        </tr>
        <tr>
            <th>Application Date</th>
            <td>{{$lien_date}}</td>
        </tr>
    </tbody>
</table>
<table style="margin: 40px 0">
    <thead style="background: aliceblue">
    <tr>
        <th>Buyer Name</th>
        <th>Contract NO</th>
        <th>Contract Value</th>
        <th>Internal File No</th>
        <th>Contract Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($details as $item)
        <tr>
            <td>{{$item['buyer_name']}}</td>
            <td>{{$item['sales_contract_no']}}</td>
            <td style="text-align: right">
                {{$item['sales_contract_value']}}
            </td>
            <td>{{$item['internal_file_no']}}</td>
            <td>{{$item['sales_contract_date']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
