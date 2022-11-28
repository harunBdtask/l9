@extends('skeleton::layout')
@section('title','Daily Yarn Receive Statement')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2> Daily Yarn Receive Statement </h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="col-md-12" method="GET"
                          action="{{ url('/inventory/daily-yarn-receive-statement') }}">
                        <table class="reportTable" style="background-color: aliceblue">
                            <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Store</th>
                                <th>LOT No</th>
                                <th>Party Name</th>
                                <th>Receive Basis</th>
                                <th>Goods RCV By</th>
                                <th>LC No</th>
                                <th>PI No</th>
                                <th>RCV ID</th>
                                <th>Challan No</th>
                                <th>Date</th>
                                <th>
                                    <a style="line-height: 1"
                                       class="btn btn-sm btn-warning btn-block"
                                       href="{{url('inventory/daily-yarn-receive-statement')}}">
                                        Clear
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select name="factory_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        @foreach($factories as $value)
                                            <option @if(auth()->user()->factory_id == $value->id) selected @endif
                                            value="{{ $value->id }}"> {{ $value->factory_name }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="store_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        @foreach($stores as $value)
                                            <option @if(request()->get('store_id') == $value->id) selected @endif
                                            value="{{ $value->id }}"> {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="lot_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($lotNos as $value)
                                            <option @if($value == request()->get('lot_no')) selected @endif
                                            value="{{ $value }}"> {{ $value }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="party_type"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($loanParty as $value)
                                            <option @if($value->id == request()->get('party_type')) selected @endif
                                            value="{{ $value->id }}"> {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="receive_basis"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        <option {{ request()->get('receive_basis') == 'independent' ? 'selected' : '' }} value="independent">
                                            Indepedent
                                        </option>
                                        <option {{ request()->get('receive_basis') == 'pi' ? 'selected' : '' }} value="pi">
                                            PI Basis
                                        </option>
                                        <option {{ request()->get('receive_basis') == 'wo' ? 'selected' : '' }} value="wo">
                                            WO Basis
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <select name="receive_by"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">

                                    </select>
                                </td>
                                <td>
                                    <select name="lc_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($lcNos as $value)
                                            <option @if($value == request()->get('lc_no')) selected @endif
                                            value="{{ $value }}"> {{ $value }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="pi_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($piNos as $value)
                                            <option @if($value == request()->get('pi_no')) selected @endif
                                            value="{{ $value }}"> {{ $value }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="receive_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($receiveNos as $value)
                                            <option @if($value == request()->get('receive_id')) selected @endif
                                            value="{{ $value }}"> {{ $value }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="challan_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($challanNos as $value)
                                            <option @if($value == request()->get('challan_no')) selected @endif
                                            value="{{ $value }}"> {{ $value }} </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input name="date" type="date"
                                           value="{{ request()->get('date') ?? date('Y-m-d') }}"
                                           class="form-control form-control-sm search-field text-center"
                                    >
                                </td>
                                <td style="padding: 2px">
                                    <button type="submit" class="btn btn-sm btn-success btn-block">
                                        Show
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="row">
                    @if(count($reportData) > 0)
                        <div class="col-md-12">
                            <div class="pull-right m-b-1">
                                <a class="btn btn-sm"
                                   title="PDF"
                                   href="{{ request()->fullUrlWithQuery(['type' => 'pdf']) }}">
                                    <em class="fa text-danger fa-file-pdf-o"></em>
                                </a>
                                <a class="btn btn-sm"
                                   title="Excel"
                                   href="{{ request()->fullUrlWithQuery(['type' => 'excel']) }}">
                                    <em class="fa text-success fa-file-excel-o"></em>
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-12">
                        @include('inventory::yarns.reports.daily-yarn-receive-statement.data-table', [
                            'reportData' => collect($reportData)->whereNotNull('lc_no')->groupBy('loan_party_id')
                        ])
                    </div>
                    <div class="col-lg-12">
                        @include('inventory::yarns.reports.daily-yarn-receive-statement.data-table', [
                            'reportData' => collect($reportData)->whereNull('lc_no')->groupBy('loan_party_id')
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
