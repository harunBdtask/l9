<div class="table-responsive">
    <table >
        <tr>
            <th> SL</th>
            <th>Company Name</th>
            <th>Location</th>
            <th>Country Name</th>
            <th>Invoice No.</th>
            <th>Ship Mode</th>
            <th>Invoice Date</th>
            <th>Insert Date</th>
            <th>SC/LC</th>
            <th>SC/LC No.</th>
            <th>Buyer Name</th>
            <th>Forwarder Name</th>
            <th>Lien Bank</th>
            <th>EXP Form No</th>
            <th>EXP Form Date</th>
            <th>Grs. Invoice Amount</th>
            <th>Discount</th>
            <th>Bonous</th>
            <th>Claim</th>
            <th>Commission</th>
            <th>Net Invoice Amount</th>
            <th>Currency</th>
            <th>PO NO</th>
            <th>Style Ref.</th>
            <th>Invoice Qnty.</th>
            <th>Ctn Qnty.</th>
            <th>Actual Ship Date</th>
            <th>B/L No</th>
            <th>B/L Date</th>
            <th>B/L Days</th>
            <th>Ship Bl No</th>
            <th>Ship Bl Date</th>
            <th>ETD</th>
            <th>Feeder Vessle</th>
            <th>Mother Vessle</th>
            <th>ETA Dest.</th>
            <th>Courier No(NN Docs)</th>
            <th>GSP/CO No.</th>
            <th>GSP/CO Date</th>
            <th>GSP Cour. Date</th>
            <th>Org B/L Rcv</th>
            <th>I/C Rcv Date</th>
            <th>Ex-Factory Date</th>
            <th>Document In Hand</th>
            <th>Doc Sub Date</th>
            <th>Sub. Days</th>
            <th>B TO B Courier No</th>
            <th>Bank Bill No.</th>
            <th>Bank Bill Date</th>
            <th>Pay Term</th>
            <th>Possible Rlz. Date</th>
            <th>Realized Date</th>
            <th>Remarks</th>
        </tr>

        @php $sl = 0; @endphp
        @if(count($data) > 0)

        <tr>
            <td>1</td>
            <td>Powertex Fashions Limited</td>
            <td>27-Gazipura</td>
            <td>Anguilla</td>
            <td>QWQW</td>
            <td>Sea</td>
            <td>30-12-2020</td>
            <td>26-12-2020</td>
            <td>LC</td>
            <td>1221212</td>
            <td>Norma</td>
            <td>KIABI LIGHT KNIT</td>
            <td>National Credit and Commerce Bank</td>
            <td>Q</td>
            <td>30-12-2020</td>
            <td>600</td>
            <td>6</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>594</td>
            <td>USD</td>
            <td>View</td>
            <td>Gents short sleeve polo,ladies Polo SS 016-18</td>
            <td>300</td>
            <td>130</td>
            <td>17-06-2021</td>
            <td>21</td>
            <td>17-06-2021</td>
            <td>738356days</td>
            <td>2019</td>
            <td>17-06-2021</td>
            <td>17-06-2021</td>
            <td>Maruf</td>
            <td>Saqib</td>
            <td>22-06-2021</td>
            <td></td>
            <td>112345</td>
            <td>17-06-2021</td>
            <td></td>
            <td>07-06-2021</td>
            <td>17-06-2021</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td> At Sight</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Powertex Fashions Limited</td>
            <td>27-Gazipura</td>
            <td>Anguilla</td>
            <td>24234</td>
            <td>Sea</td>
            <td>11-12-2020</td>
            <td>11-12-2020</td>
            <td>LC</td>
            <td>1221212</td>
            <td>Norma</td>
            <td>KIABI LIGHT KNIT</td>
            <td>National Credit and Commerce Bank</td>
            <td>Q</td>
            <td>22-12-2020</td>
            <td>44</td>
            <td>6</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>777</td>
            <td>USD</td>
            <td>View</td>
            <td>Gents short sleeve polo,ladies Polo SS 016-18</td>
            <td>300</td>
            <td>130</td>
            <td>17-06-2021</td>
            <td>21</td>
            <td>17-06-2021</td>
            <td>738356days</td>
            <td>2019</td>
            <td>17-06-2021</td>
            <td>17-06-2021</td>
            <td>Maruf</td>
            <td>Saqib</td>
            <td>22-06-2021</td>
            <td></td>
            <td>556756</td>
            <td>17-06-2021</td>
            <td></td>
            <td>07-06-2021</td>
            <td>17-06-2021</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td> At Sight</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @else
            <tr>
                <td colspan="53" style="height: 20px" align="center">No Data Found!</td>
            </tr>
        @endif
    </table>
</div>
