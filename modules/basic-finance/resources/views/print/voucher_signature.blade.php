<table class="borderless">
    <tbody>
    <tr>
            <th class="text-center">&nbsp; </th>
            <th class="text-center">
                @if(!empty($bf_variable->voucher_preview_signature) && !empty($voucher->createdBy))
                    @if(!empty($voucher->createdBy->signature))
                        <img src="{{ asset('storage/'.$voucher->createdBy->signature)}}"
                        style="max-height: 80px;max-width: 100px;" />
                    @endif

                    <p class="signature">{{ $voucher->createdBy->first_name.' '.$voucher->createdBy->last_name }}</p>
                    <p class="signature">{{ $voucher->createdBy->designation }}</p>
                    <p class="signature">{{ $voucher->createdBy->AccDepartment->department??'' }}</p>
                    <p class="signature">{{ date('d-M-Y h:i:sa', strtotime($voucher->created_at)) }}</p>
                @endif
            </th>
            <th class='text-center'>
                @if(!empty($bf_variable->voucher_preview_signature) && !empty($checkedBy->commenter))
                    @if(!empty($checkedBy->commenter->signature))
                        <img src="{{ asset('storage/'.$checkedBy->commenter->signature)}}"
                        style="max-height: 80px;max-width: 100px;"
                        />
                    @endif
                    <p class="signature">{{ $checkedBy->commenter->first_name.' '.$checkedBy->commenter->last_name }}</p>
                    <p class="signature">{{ $checkedBy->commenter->designation }}</p>
                    <p class="signature">{{ $checkedBy->commenter->AccDepartment->department??'' }}</p>
                    <p class="signature">{{ date('d-M-Y h:i:sa', strtotime($checkedBy->created_at)) }}</p>
                @endif
            </th>
            <th class='text-center'>
                @if(!empty($bf_variable->voucher_preview_signature) && !empty($approvedBy->commenter))
                    @if(!empty($approvedBy->commenter->signature))
                        <img src="{{ asset('storage/'.$approvedBy->commenter->signature)}}"
                        style="max-height: 80px;max-width: 100px;"
                        />
                    @endif
                    <p class="signature">{{ $approvedBy->commenter->first_name.' '.$approvedBy->commenter->last_name }}</p>
                    <p class="signature">{{ $approvedBy->commenter->designation }}</p>
                    <p class="signature">{{ $approvedBy->commenter->AccDepartment->department??'' }}</p>
                    <p class="signature">{{ date('d-M-Y h:i:sa', strtotime($approvedBy->created_at)) }}</p>
                @endif
            </th>
            <th class="text-center">&nbsp;</th>
        </tr>
        <tr>
            <th class="text-center"><u>Received By</u></th>
            <th class="text-center"><u>Prepared By</u></th>
            <th class='text-center'><u>Checked By</u></th>
            <th class='text-center'><u>Approved By</u></th>
            <th class="text-center"><u>Authorized By</u></th>
        </tr>
    </tbody>
</table>
