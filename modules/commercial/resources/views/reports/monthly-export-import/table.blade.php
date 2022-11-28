<div class="table-responsive">
    <table >
        <tr>
            <th class="text-center" rowspan="2">Month</th>
            <th class="text-center"  rowspan="2">Contract Value</th>
            <th class="text-center" rowspan="2">Export Lc</th>
            <th class="text-center" colspan="3">Lien</th>
            <th class="text-center" colspan="5">Export</th>
            <th class="text-center" colspan="5">Import</th>
        </tr>
        <tr>
            <th>SC</th>
            <th>LC</th>
            <th>Total</th>
            <th>Ex-Factory Qnty (Invoice)</th>
            <th>Ex-Factory Value (Invoice)</th>
            <th>Bank Submit (Collection)</th>
            <th>Bank Submit (Purchess)</th>
            <th>Realization</th>
            <th>BTB Value</th>
            <th>Company Acceptance</th>
            <th>Bank Acceptance</th>
            <th>Maturity Value</th>
            <th>Payment Value</th>
        </tr>

        @php $sl = 0; @endphp
        @if(count($data) > 0)
        <tr>
            <td>1</td>
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @else
            <tr>
                <td colspan="16" style="height: 20px" align="center">No Data Found!</td>
            </tr>
        @endif
    </table>
</div>
