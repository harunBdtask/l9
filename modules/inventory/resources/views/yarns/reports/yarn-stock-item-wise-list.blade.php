@extends('skeleton::layout')
@section('title','Yarn Stock Item Wise List')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Stock Item Wise List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">

                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-6">
                                <form action="/inventory/yarn-stock-item-wise-list" method="GET">
                                    <div class="input-group" style="width: 250px;">
                                        <input type="text" class="form-control form-control-sm" id="search"
                                               value="{{ request('search') }}"
                                               name="search" placeholder="Search with Yarn Lot">
                                        <span class="input-group-btn">
                                                <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <table class="reportTable">
                        <thead>
                        <tr style="background: #0ab4e6;">
                            <th>SL</th>
                            <th>Item</th>
                            <th>Receive Qty</th>
                            <th>Receive Return Qty</th>
                            <th>Issue Qty</th>
                            <th>Issue Return Qty
                            <th>Rate</th>
                            <th>Current Stock Qty</th>
                            <th>Current Stock Value</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($stocks as $key => $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{
                                        "{$stock->meta['yarn_count']},
                                        {$stock->meta['yarn_composition']},
                                        {$stock->meta['yarn_composition']},
                                        {$stock->meta['yarn_lot']},
                                        {$stock->meta['yarn_color']},
                                        {$stock->meta['yarn_brand']},
                                        {$stock->meta['yarn_type']}"
                                    }}
                                </td>
                                <td>{{ $stock->receive_qty }}</td>
                                <td>{{ $stock->receive_return_qty }}</td>
                                <td>{{ $stock->issue_qty }}</td>
                                <td>{{ $stock->issue_return_qty }}</td>
                                <td>
                                    {{ $stock->balance != 0 ? round($stock->balance_amount / $stock->balance, 4) : 0 }}
                                </td>
                                <td>{{ $stock->balance }}</td>
                                <td>{{ $stock->balance_amount }}</td>
                            </tr>
                        @empty
                            <tr>
                                <th class="text-center" colspan="14">No Data Found</th>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $stocks->links() }}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
