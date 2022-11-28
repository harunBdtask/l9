<div class="table-responsive">
    <table >
        <tr>
            <th > SL</th>
            <th >Job</th>
            <th >Buyer</th>
            <th >Style</th>
            <th >Order</th>
            <th >WO No</th>
            <th >WO Date</th>
            <th >Delivery Date</th>
            <th >Supplier</th>
            <th >Challan/ D/O No</th>
            <th >Yarn Rcvd Date</th>
            <th >Yarn Details</th>
            <th >Color</th>
            <th >Count</th>
            <th >Qty</th>
            <th >Rate</th>
            <th >Amount</th>
            <th >Remarks</th>
        </tr>

        @php $sl = 0; @endphp
        @if(count($data) > 0)
        <tr>
            <td>1</td>
            <td>23434</td>
            <td>Norma</td>
            <td>453434</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td></td>
        </tr>
        <tr>
            <td>2</td>
            <td>45435</td>
            <td>Kmart</td>
            <td>567856</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td></td>
        </tr>
        @else
            <tr>
                <td colspan="18" style="height: 20px" align="center">No Data Found!</td>
            </tr>
        @endif
    </table>
</div>
