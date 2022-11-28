<div class="body-section" style="margin-top: 0px;">
    <table>
        <thead>
        <tr>
            <th><b>Lc/Sc. No</b>Date</th>
            <th><b>Order No</b></th>
            <th><b>Invoice No Date</b></th>
            <th><b>Value</b></th>
            <th><b>Bl No Date </b></th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>{{$invoice['lc_sc_no']}}
                    <p>{{$invoice['lc_sc_date']}}</p></td>
                <td>{{$invoice['order_numbers']}}</td>
                <td>{{$invoice['invoice_no']}} <p>{{$invoice['invoice_date']}}</p></td>
                <td>{{$invoice['value']}}</p></td>
                <td>{{$invoice['bl_no']}}<p>{{$invoice['bl_date']}}</p></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
