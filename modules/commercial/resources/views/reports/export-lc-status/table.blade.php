<div class="table-responsive">
    <table >
    																																													
        <tr>
            <th> SL</th>
            <th> LC NUMBER</th>
            <th> LC QNT</th>
            <th> LC VALUE</th>
            <th> INVOICE NO</th>
            <th> INVOICE DATE</th>
            <th> INVOICE QNT</th>
            <th> INVOICE VALUE</th>
            <th> DOC. SUBMISSION NO</th>
            <th> DOC. SUBMISSION QNT</th>
            <th> DOC. SUBMISSION DATE</th>
            <th> DOC. SUBMISSSION VALUE</th>
            <th> REALIZATON DATE</th>
            <th> REALIZATON VALUE</th>
            <th> SHORT RELAZITION</th>
            <th> DUE VALUE</th>
            <th> PRIMARY CONTRACT NO</th>
            <th> PRIMARY CONTRACT VALUE</th>
            <th> SC NO</th>
            <th> SC VALUE</th>
            <th> BUYING AGENT</th>
            <th> BUYER</th>
        </tr>

        @forelse($data as $key=>$item)

        @php
            $inv_count = count($item->invoice)==0?1:count($item->invoice);
            
        @endphp
        @for($i=0;$i<=$inv_count-1;$i++)

        @php 
            $invoice = @$item->invoice[$i]; 
            $docs = collect($item->docSubmissionInfo)
            ->where('export_lc_id', $item->id)
            ->where('export_invoice_id', @$invoice->id)
            ->last();

            $proceed = collect(@$docs->docSubmission->proceed_realization)->last();

        @endphp

            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $item->lc_number??null }}</td>
                <td>{{ collect($item->details)->sum('attach_qty')??null }}</td>
                <td>{{ $item->lc_value??null }}</td>
                <td >{{ @$invoice->invoice_no??null  }}</td>
                <td >{{ @$invoice->invoice_date??null  }}</td>
                <td >{{ @$invoice->invoice_qty??null  }}</td>
                <td >{{ @$invoice->invoice_value??null  }}</td>

                <td>{{ $docs->docSubmission->bank_ref_bill??null }}</td>
                <td>{{ $invoice->invoice_qty??null  }}</td>
                <td>{{ $docs->docSubmission->submission_date??null }}</td>
                <td>{{ $docs->net_inv_value??null }}</td>


                <td>{{ $proceed->receive_date??null }}</td>
                <td>{{ $proceed->bill_invoice_amount??null }}</td>
                <td>{{ $proceed->negotiated_amount??null }}</td>
                <td>{{ (@$proceed->bill_invoice_amount - @$proceed->negotiated_amount) }}</td>

                <td>{{ $item->primary_contract->ex_contract_number??null }}</td>
                <td>{{ $item->primary_contract->contract_value??null }}</td>

                <td>{{ $item->primary_contract->salesContract[0]->contract_number??null }}</td>
                <td>{{ $item->primary_contract->salesContract[0]->contract_value??null }}</td>
             
                <td>{{ $item->buyingAgent->buying_agent_name??null }}</td>
                <td>{{ collect($item->buyer_names)->implode('name',',')??null }}</td>
            </tr>
        @endfor
        @empty
        <tr>
            <td colspan="22" style="height: 20px" align="center">No Data Found!</td>
        </tr>
        @endforelse
    </table>
</div>

