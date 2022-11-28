@extends('skeleton::layout')
@section("title","Capacity VS Marketing Realization Report")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Capacity VS Marketing Realization Report</h2>
            </div>

            <div class="box-body">
                <div class="col-md-6">
                    <form action="{{ url('/planning/reports/capacity-marketing-comparisons') }}" method="GET">
                        <table class="reportTable">
                            <tr>
                                <th>Start</th>
                                <th>End</th>
                                <th>Search</th>
                            </tr>
                            <tr>
                                <td style="padding: 3px;">
                                    <input style="line-height: 1.5rem;" type="month" class="form-control" name="start"
                                           value="{{ request('start') }}"/>
                                </td>
                                <td style="padding: 3px;">
                                    <input style="line-height: 1.5rem;" type="month" class="form-control" name="end"
                                           value="{{ request('end') }}"/>
                                </td>
                                <td style="padding: 3px;">
                                    <button class="btn btn-sm btn-info" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="row">
                    <div class="header-section" style="padding-bottom: 0;">
                        <div class="pull-right" style="margin-bottom: -5%;">

                            <a id="order_volume_pdf" data-value="" class="btn"
                               href="{{ route("planning.reports.capacity-marketing-comparisons.pdf",[
                            'start' => request('start'),
                            'end' => request('end'),
                            ]) }}">
                                <em class="fa fa-file-pdf-o"></em></a>

                            <a id="order_volume_excel" data-value="" class="btn"
                               href="{{ route('planning.reports.capacity-marketing-comparisons.excel',[
                            'start' => request('start'),
                            'end' => request('end'),
                            ]) }}">
                                <em class="fa fa-file-excel-o"></em>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        @include('planing::reports.capacity-marketing-comparison-table')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
