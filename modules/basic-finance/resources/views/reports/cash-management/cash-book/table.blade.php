<style>
    table thead {
        display: table-row-group;
    }

    table, tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
    }
</style>

@php
    function arrayGroupBy(array $array, $key)
       {
           if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
               trigger_error('arrayGroupBy(): The key should be a string, an integer, or a callback', E_USER_ERROR);
               return null;
           }
           $func = (!is_string($key) && is_callable($key) ? $key : null);
           $_key = $key;
           $grouped = [];
           foreach ($array as $value) {
               $key = null;
               if (is_callable($func)) {
                   $key = call_user_func($func, $value);
               } elseif (is_object($value) && property_exists($value, $_key)) {
                   $key = $value->{$_key};
               } elseif (isset($value[$_key])) {
                   $key = $value[$_key];
               }
               if ($key === null) {
                   continue;
               }
               $grouped[$key][] = $value;
           }
           if (func_num_args() > 2) {
               $args = func_get_args();
               foreach ($grouped as $key => $value) {
                   $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                   $grouped[$key] = call_user_func_array('arrayGroupBy', $params);
               }
           }
           return $grouped;
       }
@endphp
<table class="reportTable" style="width: 100%;">
    <thead>
    <tr>
        <th>CASH BOOK NAME</th>
        <th>COMPANY</th>
        <th>PROJECT</th>
        <th>UNIT</th>
        <th>Opening Balance</th>
        <th>Total Debit</th>
        <th>Total Credit</th>
        <th>Closing Balance</th>
        @if(empty(request('type')))
            <th>ACTION</th>@endif
    </tr>
    </thead>
    <tbody>

    @foreach ($journalUnitWiseData->groupBy('account_code') as $ac_code => $accWiseGroupedData)
        @php

            $accOpeningBalanceTotal = collect($accWiseGroupedData)->sum('unit_wise_total_opening_balance');
            $accDebitTotal = collect($accWiseGroupedData)->sum('unit_wise_total_debit_balance');
            $accCreditTotal = collect($accWiseGroupedData)->sum('unit_wise_total_credit_balance');
            $accClosingBalanceTotal = collect($accWiseGroupedData)->sum('unit_wise_total_closing_balance');

                $ac_rowspan = 0;
                collect($accWiseGroupedData)->groupBy('factory_id')->each(function($factoryGroup, $factory_id) use(&$ac_rowspan) {
                    collect($factoryGroup)->groupBy('project_id')->each(function($projectGroup, $project_id) use(&$ac_rowspan) {
                        collect($projectGroup)->groupBy('unit_id')->each(function($unitGroup, $unit_id) use(&$ac_rowspan) {
                            $ac_rowspan += collect($unitGroup)->count();
                        });
                        $ac_rowspan++;
                    });
                   $ac_rowspan++;
                 });
        $ac_rowspan++;
        @endphp
        <tr>
            <td rowspan="{{ $ac_rowspan }}"><b>{{ collect($accWiseGroupedData)->first()['account_name']  }} </b></td>
        @php $factoryOpeningBalanceTotal  = $factoryDebitTotal = $factoryCreditTotal = $factoryClosingBalanceTotal = 0; @endphp
        @foreach (arrayGroupBy($accWiseGroupedData->toArray(), 'factory_id') as $factoryWiseGroupedData)
            @php
                $factoryOpeningBalanceTotal = collect($factoryWiseGroupedData)->sum('unit_wise_total_opening_balance');
                $factoryDebitTotal = collect($factoryWiseGroupedData)->sum('unit_wise_total_debit_balance');
                $factoryCreditTotal = collect($factoryWiseGroupedData)->sum('unit_wise_total_credit_balance');
                $factoryClosingBalanceTotal = collect($factoryWiseGroupedData)->sum('unit_wise_total_closing_balance');

                $fac_rowspan = 0;
                    collect($factoryWiseGroupedData)->groupBy('project_id')->each(function($projectGroup, $project_id) use(&$fac_rowspan) {
                        collect($projectGroup)->groupBy('unit_id')->each(function($unitGroup, $unit_id) use(&$fac_rowspan) {
                            $fac_rowspan += collect($unitGroup)->count();
                        });
                        $fac_rowspan++;
                    });
                    $fac_rowspan++;
            @endphp
            @if(!$loop->first)
                <tr> @endif
                    <td rowspan="{{ $fac_rowspan }}">{{ collect($factoryWiseGroupedData)->first()['factory_name']  }}</td>
                @php $projectOpeningBalanceTotal = $projectClosingBalanceTotal = $projectDebitTotal = $projectCreditTotal = 0; @endphp
                @foreach (arrayGroupBy($factoryWiseGroupedData, 'project_id') as $projectWiseGroupedData)
                    @php
                        $proj_rowspan = 0;
                        $proj_rowspan = collect($projectWiseGroupedData)->count() + 1;

                    @endphp
                    @if(!$loop->first)
                        <tr> @endif
                            @php
                                $projectOpeningBalanceTotal = collect($projectWiseGroupedData)->sum('unit_wise_total_opening_balance');
                                $projectDebitTotal = collect($projectWiseGroupedData)->sum('unit_wise_total_debit_balance');
                                $projectCreditTotal = collect($projectWiseGroupedData)->sum('unit_wise_total_credit_balance');
                                $projectClosingBalanceTotal = collect($projectWiseGroupedData)->sum('unit_wise_total_closing_balance');
                            @endphp
                            <td rowspan="{{ $proj_rowspan}}">{{ collect($projectWiseGroupedData)->first()['project_name']  }}</td>
                        @php $unitOpeningBalanceTotal = $unitClosingBalanceTotal = $unitDebitTotal = $unitCreditTotal = 0; @endphp
                        @foreach (arrayGroupBy($projectWiseGroupedData, 'unit_id') as $unitWiseGroupedData)
                            @if(!$loop->first)
                                <tr> @endif
                                    @php
                                        $unitOpeningBalanceTotal = collect($unitWiseGroupedData)->sum('unit_wise_total_opening_balance');
                                        $unitDebitTotal = collect($unitWiseGroupedData)->sum('unit_wise_total_debit_balance');
                                        $unitCreditTotal = collect($unitWiseGroupedData)->sum('unit_wise_total_credit_balance');
                                        $unitClosingBalanceTotal = collect($unitWiseGroupedData)->sum('unit_wise_total_closing_balance');
                                    @endphp
                                    <td>{{ collect($unitWiseGroupedData)->first()['unit_name'] }}</td>
                                    <td style="text-align: right;">{{ number_format(collect($unitWiseGroupedData)->first()['unit_wise_total_opening_balance'], 2) }}</td>
                                    <td style="text-align: right;">{{ number_format(collect($unitWiseGroupedData)->first()['unit_wise_total_debit_balance'], 2) }}</td>
                                    <td style="text-align: right;">{{ number_format(collect($unitWiseGroupedData)->first()['unit_wise_total_credit_balance'], 2) }}</td>
                                    <td style="text-align: right;">{{ number_format(collect($unitWiseGroupedData)->first()['unit_wise_total_closing_balance'], 2) }}</td>
                                    @if(empty(request('type')))
                                        <td>

                                            @php 

                                                $urlData = http_build_query([
                                                    'start_date' => date('Y-m-d',strtotime($fromDate)),
                                                    'end_date' => date('Y-m-d',strtotime($toDate)),
                                                    'account_id' => collect($accWiseGroupedData)->first()['account_id'],
                                                    'factory_id' => $factoryId,
                                                    'project_id' => collect($unitWiseGroupedData)->first()['project_id'],
                                                    'unit_id' => collect($unitWiseGroupedData)->first()['unit_id']
                                                ]);
                                            @endphp
                                             <a class="btn btn-xs" target="_blank" href="{{ url('/basic-finance/ledger?'.$urlData) }}" title="View Ledger">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                                @if(!$loop->first)
                                    <tr> @endif
                                        <td><b>Unit Total</b></td>
                                        <td style="text-align: right;">
                                            <b>{{ number_format($unitOpeningBalanceTotal, 2) }}</b></td>
                                        <td style="text-align: right;"><b>{{ number_format($unitDebitTotal, 2) }}</b>
                                        </td>
                                        <td style="text-align: right;"><b>{{ number_format($unitCreditTotal, 2) }}</b>
                                        </td>
                                        <td style="text-align: right;">
                                            <b>{{ number_format($unitClosingBalanceTotal, 2) }}</b></td>
                                        @if(empty(request('type')))
                                            <td style="text-align: right;"><b>{{ '' }}</b></td> @endif
                                        @if(!$loop->first)
                                    </tr> @endif
                                @endforeach
                                @if(!$loop->first)
                                    <tr> @else
                                    <tr>
                                        @endif
                                        <td colspan="2"><b>Project Total</b></td>
                                        <td style="text-align: right;">
                                            <b>{{ number_format($projectOpeningBalanceTotal, 2) }}</b></td>
                                        <td style="text-align: right;"><b>{{ number_format($projectDebitTotal, 2) }}</b>
                                        </td>
                                        <td style="text-align: right;">
                                            <b>{{ number_format($projectCreditTotal, 2) }}</b></td>
                                        <td style="text-align: right;">
                                            <b>{{ number_format($projectClosingBalanceTotal, 2) }}</b></td>
                                        @if(empty(request('type')))
                                            <td style="text-align: right;"><b>{{ '' }}</b></td> @endif
                                    </tr>
                                    @endforeach
                                    @if(!$loop->first)
                                        <tr> @endif
                                            <td colspan="3"><b>Factory Total</b></td>
                                            <td style="text-align: right;">
                                                <b>{{ number_format($factoryOpeningBalanceTotal, 2) }}</b></td>
                                            <td style="text-align: right;">
                                                <b>{{ number_format($factoryDebitTotal, 2) }}</b></td>
                                            <td style="text-align: right;">
                                                <b>{{ number_format($factoryCreditTotal, 2) }}</b></td>
                                            <td style="text-align: right;">
                                                <b>{{ number_format($factoryClosingBalanceTotal, 2) }}</b></td>
                                            @if(empty(request('type')))
                                                <td style="text-align: right;"><b>{{ '' }}</b></td> @endif
                                        </tr>
                                        @if(!$loop->first)
                                            <tr> @endif
                                                <td colspan="4"><b>Account Total</b></td>
                                                <td style="text-align: right;">
                                                    <b>{{ number_format($accOpeningBalanceTotal, 2) }}</b></td>
                                                <td style="text-align: right;">
                                                    <b>{{ number_format($accDebitTotal, 2) }}</b></td>
                                                <td style="text-align: right;">
                                                    <b>{{ number_format($accCreditTotal, 2) }}</b></td>
                                                <td style="text-align: right;">
                                                    <b>{{ number_format($accClosingBalanceTotal, 2) }}</b></td>
                                                @if(empty(request('type')))
                                                    <td style="text-align: right;"><b>{{ '' }}</b></td> @endif
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4"><b>Grand Total</b></td>
                                                <td style="text-align: right;">
                                                    <b>{{ number_format(collect($journalUnitWiseData)->sum('unit_wise_total_opening_balance'), 2) }}</b>
                                                </td>
                                                <td style="text-align: right;">
                                                    <b>{{ number_format(collect($journalUnitWiseData)->sum('unit_wise_total_debit_balance'), 2) }}</b>
                                                </td>
                                                <td style="text-align: right;">
                                                    <b>{{ number_format(collect($journalUnitWiseData)->sum('unit_wise_total_credit_balance'), 2) }}</b>
                                                </td>
                                                <td style="text-align: right;">
                                                    <b>{{ number_format(collect($journalUnitWiseData)->sum('unit_wise_total_closing_balance'), 2) }}</b>
                                                </td>
                                                @if(empty(request('type')))
                                                    <td style="text-align: right;"><b>{{ '' }}</b></td> @endif
                                            </tr>
    </tbody>
</table>
<br>
