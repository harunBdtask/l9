<div class="box-body b-t">
    <div class="row m-t">
        <div class="col-sm-12">
            <table class="reportTable">
                <thead>
                <tr>
                    <th>Sl</th>
                    <th>Loan Account Number</th>
                    <th>Company</th>
                    <th>Project</th>
                    <th>Unit</th>
                    <th>Bank</th>
                    <th>Control Account Name</th>
                    <th>Loan Creation Date</th>
                    <th>Expiary Date</th>
                    <th>Updated Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($loanAccounts as $key => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->loan_account_number ?? null }}</td>
                        <td>{{ $item->factory->factory_name ?? null }}</td>
                        <td>{{ $item->project->project ?? null }}</td>
                        <td>{{ $item->unit->unit ?? null }}</td>
                        <td>{{ $item->bank->short_name ?? null }}</td>
                        <td>{{ $item->controlAccount->name ?? null }}</td>
                        <td>{{ Carbon\carbon::parse($item->loan_creation_date)->format('M d, Y') ?? '' }}</td>
                        <td>{{ Carbon\carbon::parse($item->expiry_date)->format('M d, Y') ?? '' }}</td>
                        <td>{{ Carbon\carbon::parse($item->updated_at)->format('M d, Y') ?? '' }}</td>
                        <td style="padding: 0.2%;">
                            @permission('permission_of_loan_accounts_edit')
                            <a class="btn btn-xs btn-warning" title="Edit Loan Account"
                               href="{{ url('/basic-finance/loan/accounts/' . $item->id.'/edit' ) }}"><i
                                    class="fa fa-edit"></i></a>
                            @endpermission
{{--                            @permission('permission_of_loan_accounts_view')--}}
{{--                            <a class="btn btn-xs btn-info" title="View Loan Account"--}}
{{--                               target="__blank"--}}
{{--                               href="{{ url('/loan/accounts/report?factory_id=' . $item->factory_id ) . '&season_id=' . $item->season_id .'&supplier_as_mill_id=' . $item->supplier_as_mill_id . '&fabric_desc=' . $item->fabric_desc .'&from_date='.$item->created_at.'&to_date='.$item->created_at }}">--}}
{{--                                <i class="fa fa-eye"></i>--}}
{{--                            </a>--}}
{{--                            @endpermission--}}
                            @permission('permission_of_loan_accounts_delete')
                            <button style="margin-left: 2px;" type="button"
                                    class="btn btn-xs btn-danger show-modal"
                                    title="Delete Loan Account"
                                    data-toggle="modal"
                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                    ui-target="#animate"
                                    data-url="{{ url('/basic-finance/loan/accounts/'.$item->id) }}">
                                <i class="fa fa-times"></i>
                            </button>
                            @endpermission
{{--                            @permission('permission_of_loan_accounts_add')--}}
{{--                            <a type="button" class="btn btn-xs btn-info" title="copy"--}}
{{--                               href="{{ url('/loan/accounts/'.$item->id."/copy" )}}">--}}
{{--                                <i class="fa fa-copy"></i>--}}
{{--                            </a>--}}
{{--                            @endpermission--}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center p-a" colspan="11">No Data Found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="row m-t">
        <div class="col-sm-12">
            {{ $loanAccounts->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>

