@foreach($accounts as $account)
    @php
        $info = optional($account)->accountInfo;
        $hasLedger = 0;

        if ($account->account_type == '3') {
            $control_account = $account->name;
            $hasLedger = collect($account->hasLedger())->where('accountInfo.control_account_id', $account->id)->count();
            $ledger =  '';
        } else {
            $control_account =  $info->controlAccount->name;
            $ledger = $info->ledgerAccount->name !== "N\A" ? $info->ledgerAccount->name : $account->name;
        }
    @endphp
    @if(!$hasLedger)
        <tr class="tr-height" style="{{ $account->status != 1 ? 'color: gainsboro' : ''}}">
            <td class="text-center">{{ $account->code }}</td>
            <td class="text-left">{{ $account->type }}</td>
            <td class="text-left">{{ $info->parentAccount->name }}</td>
            <td class="text-left">{{ $info->groupAccount->name }}</td>
            <td class="text-left">{{ $control_account }}</td>
            <td class="text-left">{{ $ledger }}</td>
            @if($account->status == 1)
                <td class="text-left" style="color: green">Active</td>
            @else
                <td class="text-left" style="color: red">In-Active</td>
            @endif
            <td>
                @permission('permission_of_chart_of_accounts_edit')
                <a type="button" class="btn btn-xs btn-info"
                        href="/finance/accounts/{{$account->id}}/edit">
                        <i class="fa fa-edit"></i>
            </a>
                @endpermission

                @permission('permission_of_chart_of_accounts_delete')
                <button type="button" class="btn btn-xs btn-danger" id="delete_account"
                        data-url="/finance/api/v1/delete-account/{{$account->id}}">
                    <i class="fa fa-times"></i>
                </button>
                @endpermission

            </td>
        </tr>
    @endif
@endforeach
