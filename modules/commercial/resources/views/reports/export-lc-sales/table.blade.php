<div class="table-responsive">
    <table >
        <tr>
            <th> SL</th>
            <th>Internal File No.</th>
            <th>Bank File No.</th>
            <th>Year</th>
            <th>Beneficiary</th>
            <th>Buyer</th>
            <th>Applicant</th>
            <th>SC/LC</th>
            <th>SC/LC No.</th>
            <th>Convertible Type</th>
            <th>SC/LC Date</th>
            <th>Last Ship Date</th>
            <th>SC Value(LC/SC)</th>
            <th>Rep. LC/SC</th>
            <th>Balance</th>
            <th>SC Value(Direct)</th>
            <th>LC Value(Direct)</th>
            <th>File Value</th>
            <th>Expiry Date</th>
            <th>Lien Bank</th>
            <th>Issuing Bank</th>
            <th>Pay Term</th>
            <th>Tenor</th>
            <th>Inco Term</th>
            <th>Transfering Bank</th>
            <th>Negotiating Bank</th>
            <th>Nominated Ship. Line</th>
            <th>Re- Imbursing Bank</th>
        </tr>

        @php $sl = 0; @endphp
        @if(count($data) > 0)

        <tr>
            <td>1</td>
            <td>32423</td>
            <td>454</td>
            <td>2020</td>
            <td>Skylarksoft</td>
            <td>Norma</td>
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>2</td>
            <td>45645</td>
            <td>546</td>
            <td>2021</td>
            <td>Skylarksoft</td>
            <td>Kmart</td>
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
                <td colspan="28" style="height: 20px" align="center">No Data Found!</td>
            </tr>
        @endif
    </table>
</div>
