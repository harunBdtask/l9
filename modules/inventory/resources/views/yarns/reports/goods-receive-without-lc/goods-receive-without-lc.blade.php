@extends('skeleton::layout')
@section('title','Goods Receive Without LC')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2> Goods Receive Without LC </h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="col-md-12 table-responsive" method="GET"
                          action="{{ url('/inventory/yarn-store/goods-receive-without-lc') }}">
                        <table class="reportTable">
                            <tr>
                                <th style="background-color: aliceblue !important; width: 15%">Company Name</th>
                                <th style="background-color: aliceblue !important; width: 15%">Store</th>
                                <th style="background-color: aliceblue !important; width: 15%">Count</th>
                                <th style="background-color: aliceblue !important; width: 15%">Composition</th>
                                <th style="background-color: aliceblue !important; width: 15%">Type</th>
                                <th style="background-color: aliceblue !important; width: 15%">Color</th>
                                <th style="background-color: aliceblue !important; width: 15%">Brand</th>
                            </tr>

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
                                    <select name="yarn_count_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($yarnCount as $value)
                                            <option @if(request()->get('yarn_count_id') == $value->id) selected @endif
                                            value="{{ $value->id }}"> {{ $value->yarn_count }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="yarn_composition_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($yarnComposition as $value)
                                            <option @if(request()->get('yarn_composition_id') == $value->id) selected @endif
                                            value="{{ $value->id }}"> {{ $value->yarn_composition }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="yarn_type_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($yarnType as $value)
                                            <option @if(request()->get('yarn_type_id') == $value->id) selected @endif
                                            value="{{ $value->id }}"> {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input name="yarn_color" type="text"
                                           value="{{ request()->get('yarn_color') }}"
                                           class="form-control form-control-sm search-field text-center"
                                    >
                                </td>
                                <td>
                                    <input name="yarn_brand" type="text"
                                           value="{{ request()->get('yarn_brand') }}"
                                           class="form-control form-control-sm search-field text-center"
                                    >
                                </td>
                            </tr>
                            <tr>
                                <th style="background-color: aliceblue !important; width: 15%">Certification</th>
                                <th style="background-color: aliceblue !important; width: 15%">Lot No</th>
                                <th style="background-color: aliceblue !important; width: 15%">Party</th>
                                <th style="background-color: aliceblue !important; width: 15%">PI No</th>
                                <th style="background-color: aliceblue !important; width: 15%">From Date</th>
                                <th style="background-color: aliceblue !important; width: 15%">To Date</th>
                                <th style="background-color: aliceblue !important;">
                                    <a style="line-height: 1"
                                       class="btn btn-sm btn-warning btn-block"
                                       href="{{url('/inventory/yarn-store/goods-receive-without-lc')}}">
                                        Clear
                                    </a>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <input name="certification" type="text"
                                           value="{{ request()->get('certification') }}"
                                           class="form-control form-control-sm search-field text-center"
                                    >
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
                                    <input name="from_date" type="date"
                                           value="{{ request()->get('from_date') }}"
                                           class="form-control form-control-sm search-field text-center"
                                    >
                                </td>
                                <td>
                                    <input name="to_date" type="date"
                                           value="{{ request()->get('to_date') }}"
                                           class="form-control form-control-sm search-field text-center"
                                    >
                                </td>
                                <td style="padding: 2px">
                                    <button type="submit" class="btn btn-sm btn-success btn-block">
                                        Show
                                    </button>
                                </td>
                            </tr>
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
                    <div class="col-lg-12 table-responsive">
                        @include('inventory::yarns.reports.goods-receive-without-lc.data-table', [
                            'title' => 'Goods Receive Without LC'
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
