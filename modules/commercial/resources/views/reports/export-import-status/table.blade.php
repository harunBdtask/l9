<div class="table-responsive">
    <table >
        <tr>
            <th> SL</th>
            <th>Bank Name</th>
            <th>File No</th>
            <th>LC/SC Value</th>
            <th>TTL Shipped Value</th>
            <th>Due Shipment</th>
            <th>Realize Amount</th>
            <th>UN-Realize Amount</th>
            <th>TTL B TO LC</th>
            <th>BTB Open%</th>
            <th>Kept Fc Amount</th>
            <th>Sub Under Purchase</th>
            <th>Sub Under Collection</th>
            <th>Pending / Inhand</th>
            <th>BH</th>
            <th>Negotiate Value</th>
            <th>Remarks</th>
        </tr>

        @php $sl = 0; @endphp
        @if(count($data) > 0)

        <tr>
            <td>1</td>
            <td>National Credit and Commerce Bank</td>
            <td>123</td>
            <td>210,044.00</td>
            <td>5730.04	</td>
            <td>204313.96</td>
            <td>0</td>
            <td>3759</td>
            <td>1200</td>
            <td>0.57</td>
            <td>0</td>
            <td>0</td>
            <td>3759</td>
            <td>1971.04	</td>
            <td>1</td>
            <td>0</td>
            <td></td>
        </tr>
        @else
            <tr>
                <td colspan="17" style="height: 20px" align="center">No Data Found!</td>
            </tr>
        @endif
    </table>
</div>
