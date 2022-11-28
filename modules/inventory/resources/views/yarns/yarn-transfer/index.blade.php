@extends('skeleton::layout')
@section('title','Yarn Transfer')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Transfer List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/inventory/yarn-transfer/create') }}"
                            class="btn btn-sm white m-b">
                            <i class="fa fa-plus"></i> New Yarn Transfer
                        </a>
                    </div>
                </div>
                @include('inventory::partials.flash')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <form action="/inventory/yarn-transfer">
                            <table class="reportTable">
                                <thead>
                                <tr style="background: #0ab4e6;">
                                    <th>SL</th>
                                    <th>Transfer ID</th>
                                    <th>Year</th>
                                    <th>Challan No</th>
                                    <th>Company Name</th>
                                    <th>Transfer Date</th>
                                    <th>Transfer Criteria</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <input name="transfer_id"
                                               value="{{ request()->get('transfer_id') }}"
                                               class="form-control form-control-sm search-field text-center"
                                               placeholder="Search">
                                    </th>
                                    <th>
                                        <input name="year"
                                               type="year"
                                               value="{{ request()->get('year') }}"
                                               class="form-control form-control-sm search-field text-center"
                                               placeholder="Search">
                                    </th>
                                    <th>
                                        <input name="challan_no"
                                               value="{{ request()->get('challan_no') }}"
                                               class="form-control form-control-sm search-field text-center">
                                    </th>
                                    <th style="width: 14%">
                                        <select name="company_name"
                                                class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($supplier as $value)
                                                <option
                                                    @if(request()->get('company_name') == $value->id) selected @endif
                                                value="{{ $value->id }}">
                                                    {{ $value->factory_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <input name="transfer_date" type="date"
                                               value="{{ request()->get('transfer_date') }}"
                                               class="form-control form-control-sm search-field text-center"
                                               placeholder="Search">
                                    </th>
                                    <th style="width: 14%">
                                        <select name="transfer_criteria"
                                                class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            <option
                                                @if(request()->get('transfer_criteria') == 'store_to_store') selected
                                                @endif
                                                value="store_to_store">
                                                Store to Store
                                            </option>
                                            <option
                                                @if(request()->get('transfer_criteria') == 'company_to_company') selected
                                                @endif
                                                value="company_to_company">
                                                Company to Company
                                            </option>
                                        </select>
                                    </th>
                                    <th>
                                        <button class="btn btn-xs white">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $key => $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $value->transfer_no }}</td>
                                        <td>{{ date("Y",strtotime($value->transfer_date)) }}</td>
                                        <td>{{ $value->challan_no }}</td>
                                        <td>{{ optional($value->factory)->factory_name }}</td>
                                        <td>{{date("d-m-Y", strtotime($value->transfer_date))}}</td>
                                        <td>{{ \SkylarkSoft\GoRMG\Inventory\Models\YarnTransfer::TRANSFER_CRITERIA[$value->transfer_criteria] }}</td>
                                        <td style="width : 10%">
                                            <a href="/inventory/yarn-transfer/{{ $value->id }}/edit"
                                               class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="/inventory/yarn-transfer/{{ $value->id }}/view"
                                               class="btn btn-xs btn-success" type="button">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if (count($value->details) === 0)
                                                <a onclick="return confirm('Are you sure?')"
                                                   href="/inventory/yarn-transfer/{{ $value->id }}/delete"
                                                   class="btn btn-xs btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th class="text-center" colspan="14">No Data Found</th>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>

                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
