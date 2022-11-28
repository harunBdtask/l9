@extends('skeleton::layout')
@section('title','Daily Yarn Receive Statement')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h4>Daily Yarn Issue Statement</h4>
            </div>
            <div class="box-body">
                <form id="reportFilterForm" class="row">
                    <div class="col-sm-12 table-responsive">
                        <table class="reportTable">
                            <tr style="background-color: aliceblue">
                                <th>Company</th>
                                <th>Party</th>
                                <th>Issue Basis</th>
                                <th>LOT No</th>
                                <th>Issue ID</th>
                                <th>Issue Challan No</th>
                                <th colspan="2">Issue Date Range</th>
                                <th>
                                    <a style="line-height: 1"
                                       class="btn btn-sm btn-warning btn-block"
                                       href="{{url('inventory/daily-yarn-issue-report')}}">
                                        Clear
                                    </a>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <select
                                        name="factory_id"
                                        class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value=''>Select</option>
                                        @foreach($data['factories'] as $value)
                                            <option value="{{ $value->id }}"
                                                {{ request('factory_id',factoryId()) == $value->id?'selected':''}}>
                                                {{ $value->text }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div style="width: 105px;">
                                        <select
                                            name="loan_party_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value=''>Select</option>
                                            @foreach($data['loanPartyList'] as $item)
                                                <option value="{{ $item->id }}"
                                                    {{request('loan_party_id')==$item->id?'selected':''}}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div style="width: 105px;">
                                        <select
                                            name="issue_basis"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value=''>Select</option>
                                            <option value="1" {{request('issue_basis')==1?'selected':''}}>Independent
                                            </option>
                                            <option value="2" {{request('issue_basis')==2?'selected':''}}>Requisition
                                            </option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div style="width: 105px;">
                                        <select
                                            name="yarn_lot"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value=''>Select</option>
                                            @foreach($data['lotList'] as $item)
                                                <option value="{{ $item }}"
                                                    {{request('yarn_lot')==$item?'selected':''}}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div style="width: 105px;">
                                        <select
                                            name="issue_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value=''>Select</option>
                                            @foreach($data['issueList'] as $item)
                                                <option value="{{ $item }}"
                                                    {{request('issue_no')==$item?'selected':''}}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div style="width: 105px;">
                                        <select
                                            name="challan_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value=''>Select</option>
                                            @foreach($data['challanList'] as $item)
                                                <option value="{{ $item }}"
                                                    {{request('challan_no')==$item?'selected':''}}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td><input
                                        type="date"
                                        name="from_date"
                                        value="{{request('from_date')}}"
                                        class="form-control form-control-sm search-field text-center">
                                </td>
                                <td><input
                                        type="date"
                                        name="to_date"
                                        value="{{request('to_date')}}"
                                        class="form-control form-control-sm search-field text-center">
                                </td>
                                <td style="padding: 2px">
                                    <button type="submit" class="btn btn-sm btn-success btn-block">
                                        Show
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
                @if(count($reportData) > 0)
                    <section class="m-b-1">
                        <div class="row">
                            <div class="col-sm-4 col-sm-offset-8 text-right">
                                <a id="exportPDF"
                                   class="btn btn-sm"
                                   title="PDF Export"
                                   href="{{url('inventory/daily-yarn-issue-report/pdf')}}">
                                    <em class="fa text-danger fa-file-pdf-o"></em>
                                </a>
                                <a
                                    id="exportExcel"
                                    class="btn btn-sm"
                                    title="Excel Export"
                                    href="{{url('inventory/daily-yarn-issue-report/excel')}}">
                                    <em class="fa text-success fa-file-excel-o"></em>
                                </a>
                            </div>
                        </div>
                    </section>

                    <main>
                        @include('inventory::yarns.reports.yarn-issue.yarn-issue-daily-report-table')
                    </main>

                    <div class="row m-t-3">
                        <div class="col-sm-12">
                            <table class="table table-transparent">
                                <tr>
                                    <th></th>
                                    <th>Prepared By</th>
                                    <th></th>
                                    <th>Confirmed</th>
                                    <th></th>
                                    <th>Authorised Signature</th>
                                    <th></th>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        $(function () {
            $exportPDF = $('#exportPDF');
            $exportExcel = $('#exportExcel');
            $reportFilterForm = $('#reportFilterForm');
            $exportPDF.click(function (e) {
                e.preventDefault();
                $reportFilterForm.attr('action', '/inventory/daily-yarn-issue-report/pdf');
                $reportFilterForm.submit();
            });
            $exportExcel.click(function (e) {
                e.preventDefault();
                $reportFilterForm.attr('action', '/inventory/daily-yarn-issue-report/excel');
                $reportFilterForm.submit();
            });

        })
    </script>
@endpush
