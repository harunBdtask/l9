@extends('skeleton::layout')
@section('title','Yarn Transfer')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h4>
                    Daily Yarn Stock
                </h4>
            </div>
            <div class="box-body">
                <div class="row m-t-3">
                    <table class="reportTable">
                        <thead>
                        <tr>
                            <th rowspan="2">SL</th>
                            <th rowspan="2">Company Name</th>
                            <th colspan="7">Description</th>
                            <th rowspan="2">Stock In Hand</th>
                            <th rowspan="2">Avg. Rate (USD)</th>
                            <th rowspan="2">Stock Value (USD)</th>
                            <th rowspan="2">MRR No.</th>
                            <th rowspan="2">Receive Date</th>
                            <th rowspan="2">Age (Days)</th>
                        </tr>
                        <tr>
                            <th>Product Id</th>
                            <th>Count</th>
                            <th>Composition</th>
                            <th>Yarn Type</th>
                            <th>Color</th>
                            <th>Lot</th>
                            <th>Supplier</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <select name="factory_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <input name="product_id" value="{{ request()->get('product_id') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="count" value="{{ request()->get('count') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="yarn_composition" value="{{ request()->get('yarn_composition') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="yarn_type" value="{{ request()->get('yarn_type') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="yarn_color" value="{{ request()->get('yarn_color') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="yarn_lot" value="{{ request()->get('yarn_lot') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <select name="supplier_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <input name="stock_in_hand" value="{{ request()->get('stock_in_hand') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="avg_rate" value="{{ request()->get('avg_rate') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="stock_value" value="{{ request()->get('stock_value') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="mrp_no" value="{{ request()->get('mrp_no') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="receive_date" value="{{ request()->get('receive_date') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="age_days" value="{{ request()->get('age_days') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                            </tr>
                        <tr>
                            <td colspan="15">No data available</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
