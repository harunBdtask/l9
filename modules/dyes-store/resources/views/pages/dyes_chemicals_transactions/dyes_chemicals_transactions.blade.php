@extends('dyes-store::layout')
@section('title','Dyes Chemical Transfer')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>DYES CHEMICALS TRANSFER LIST</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="box-body">
                    <a class="btn btn-sm btn-info" href="{{ url('/dyes-store/dyes-chemical-transfer/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Transfer
                    </a>
                </div>

                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if (Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr style="background: ghostwhite;">
                                <th style="text-align:left; padding-left: 1em;">Sl</th>
                                <th style="text-align:left; padding-left: 1em;">Transfer Date</th>
                                <th style="text-align:left; padding-left: 1em;">Item</th>
                                <th style="text-align:left; padding-left: 1em;">Uom</th>
                                <th style="text-align:left; padding-left: 1em;">Qty</th>
                                <th style="text-align:left; padding-left: 1em;">rate</th>
                                <th style="text-align:left; padding-left: 1em;">From Store</th>
                                <th style="text-align:left; padding-left: 1em;">To Store</th>
                                <th style="width: 170px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transfer_list as $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="text-align:left; padding-left: 1em;">
                                        {{ \Carbon\Carbon::parse($value->trn_date)->toFormattedDateString() }}
                                    </td>
                                    <td style="text-align:left; padding-left: 1em;">
                                        {{ $value->item->name ?? '' }}
                                    </td>
                                    <td style="text-align:left; padding-left: 1em;">
                                        {{ $value->uom->name ?? '' }}
                                    </td>
                                    <td style="text-align:left; padding-left: 1em;">
                                        {{ $value->qty ?? '' }}
                                    </td>
                                    <td style="text-align:left; padding-left: 1em;">
                                        {{ $value->rate ?? '' }}
                                    </td>
                                    <td style="text-align:left; padding-left: 1em;">
                                        {{ $value->sub_store_id == null ? 'Main Store' : $value->fromStore->name }}
                                    </td>
                                    <td style="text-align:left; padding-left: 1em;">
                                        {{ $value->trn_store == null ? 'Main Store' : $value->toStore->name }}
                                    </td>
                                    <td>
                                        <a class="btn btn-xs text-info" data-toggle="tooltip" data-placement="top"
                                           href="{{ url('/dyes-store/dyes-chemical-transfer/create?transfer_id=' . $value->id) }}"
                                           title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-xs text-danger" data-toggle="tooltip" data-placement="top"
                                           href="{{ url('/dyes-store/dyes-chemical-transfer/' . $value->id . '/destroy') }}"
                                           onclick="return confirm('Are You Sure?');" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="9">Not Found!</td>
                            @endforelse
                            </tbody>
                        </table>
                        @if ($transfer_list->total() > 15)
                            <div
                                class="text-center print-delete">{{ $transfer_list->appends(request()->except('page'))->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script-head')
    <script>
        function select2() {
            $('select').select2({
                tags: false,
                tokenSeparators: [',', ' ']
            });
        }

        $(document).ready(function () {
            select2();

            const value = (selector) => $(selector).val();

            $('#create-button').click(() => gotoURL());

            $('#filter-button').click(() => filterVouchers());

            $('#clear-button').click(() => window.location = '/dyes-chemical?type=in');

            function gotoURL() {
                window.location = '/dyes-chemical/create';
            }

            function filterVouchers() {
                const type = value('#type');
                const startDate = value('input[name=start_date]');
                const endDate = value('input[name=end_date]');
                const search = value('input[name=search]');

                if (type === 'in') {
                    let URL = `/dyes-chemical?type=in`;
                    URL += `&start_date=${startDate}&end_date=${endDate}&search=${search}`;
                    window.location = URL;
                }
                if (type === 'out') {
                    let URL = `/dyes-chemical-issue?type=out`;
                    URL += `&start_date=${startDate}&end_date=${endDate}&search=${search}`;
                    window.location = URL;
                }
            }
        });
    </script>
@endpush
