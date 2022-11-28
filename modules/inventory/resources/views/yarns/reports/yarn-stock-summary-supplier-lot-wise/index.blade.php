@php
 $title = 'Yarn Stock Summary Supplier-Lot Wise';
@endphp
@extends('skeleton::layout')
@section('title', $title)
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2> {{ $title }} </h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="col-md-12" method="GET"
                          action="{{ url('/inventory/yarn-stock-summary-supplier-lot-wise-report') }}">
                        <table class="reportTable" style="background-color: aliceblue">
                            <thead>
                            <tr>
                                <th>Store</th>
                                <th>Count</th>
                                <th>Yarn Lot</th>
                                <th>Yarn Brand</th>
                                <th>Yarn Type</th>
                                <th>Certification</th>
                                <th>Origin</th>
                                <th colspan="2">Date Range</th>
                                <th>
                                    <a style="line-height: 1"
                                       class="btn btn-sm btn-warning btn-block"
                                       href="{{url('inventory/yarn-stock-summary-supplier-lot-wise-report')}}">
                                        Clear
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select name="store_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        @foreach($stores as $value)
                                            <option @if(request()->get('store_id') == $value->id) selected @endif
                                            value="{{ $value->id }}"> {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width : 12%">
                                    <select name="count_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($counts as $value)
                                            <option @if(request()->get('count_id') == $value->id) selected @endif
                                            value="{{ $value->id }}"> {{ $value->yarn_count }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width : 12%">
                                    <input name="yarn_lot" type="text"
                                           value="{{ request()->get('yarn_lot') }}"
                                           placeholder="Yarn Lot"
                                           class="form-control form-control-sm search-field text-center"
                                    >
                                </td>
                                <td style="width : 12%">
                                    <input name="yarn_brand" type="text"
                                           value="{{ request()->get('yarn_brand') }}"
                                           placeholder="Yarn Brand"
                                           class="form-control form-control-sm search-field text-center"
                                    >
                                </td>
                                <td>
                                    <select name="yarn_type"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($yarnTypes as $key => $type)
                                            <option @if(request()->get('yarn_type') == $type->id) selected @endif
                                            value="{{ $type->id }}"> {{ $type->name }} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input name="certification" type="text"
                                           value="{{ request()->get('certification') }}"
                                           placeholder="Certification"
                                           class="form-control form-control-sm search-field text-center"
                                    >
                                </td>
                                <td>
                                    <input name="origin" type="text"
                                           value="{{ request()->get('origin') }}"
                                           placeholder="Origin"
                                           class="form-control form-control-sm search-field text-center"
                                    >
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
                    <div class="col-lg-12 table-responsive">
                        @include('inventory::yarns.reports.yarn-stock-summary-supplier-lot-wise.data-table', [
                            'reportData' => $reportData
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
