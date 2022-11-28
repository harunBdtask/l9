@extends('skeleton::layout')
@section('title','BTB Margin Lc')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>
                    BTB Margin Lc List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/commercial/btb-margin-lc/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New BTB Margin Lc</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/commercial/btb-margin-lc/') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Unique ID</th>
                                <th>Application Date</th>
                                <th>Lien Bank</th>
                                <th>Item Category</th>
                                <th>PI Value</th>
                                <th>LC Value</th>
                                <th>Supplier</th>
                                <th>LC Type</th>
                                <th>BTB LC Number</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($b2bData as $data)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{ $data->uniq_id }}</td>
                                    <td>{{ $data->application_date }}</td>
                                    <td>{{ $data->lienBank->name }}</td>
                                    <td>{{ $data->item->item_name }}</td>
                                    <td>{{ $data->pi_value }}</td>
                                    <td>{{ $data->lc_sc_value }}</td>
                                    <td>{{ $data->supplier->name }}</td>
                                    <td>{{ $data->lc_type == 1 ? 'BTB' : 'Margin LC' }}</td>
                                    <td>{{ $data->lc_number }}</td>
                                    <td style="padding: 2px">
                                        <a target="_blank" class="btn btn-xs white"
                                           href="{{ url('/commercial/btb-margin-lc/'. $data->id . '/pad-preview') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a href="{{ url('/commercial/btb-margin-lc/create?margin_lc_id=') . $data->id }}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a target="_blank" class="btn btn-xs btn-info"
                                           href="{{ url('/commercial/btb-margin-lc/'. $data->id . '/view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Budget"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/commercial/btb-margin-lc/'.$data->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="10">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $b2bData->appends(request()->query())->links()  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
