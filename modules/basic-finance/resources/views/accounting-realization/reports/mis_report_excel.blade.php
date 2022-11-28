<div class="padding">
    <div class="box">
        <div class="box-body table-responsive b-t">
            <div class="row">
                <form action="">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="reportTable" style="margin-top: 2%;">
                                    @php 
                                        $distribution_tiles = $lists['dynamic_titles']['distribution']??[];
                                        $deduction_tiles = $lists['dynamic_titles']['deduction']??[];
                                        $bank_charge_tiles = $lists['dynamic_titles']['bank_charge']??[];
                                    
                                        $total_cols = 13+count(@$distribution_tiles)+count(@$deduction_tiles)+count(@$bank_charge_tiles);
                                    @endphp
                                    <thead>
                                    <tr>
                                        <th style="text-align: center" colspan="{{ $total_cols}}"><h2 class="text-center"> <b>Realization Report</b> </h2></th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center" colspan="{{ $total_cols}}"> <h4> <b>Start Date: {{ @$start_date }}</b> &nbsp; <b>End Date: {{ @$end_date }}</b> </h4> </th>
                                    </tr>
                                    <tr>
                                        <th>Foreign EXP CONT. NO./ Local Sales Contract</th>
                                        <th>L/C No.</th>
                                        <th>Buyer/Party Name</th>
                                        <th>Style No.</th>
                                        <th>PO No.</th>
                                        <th>Invoice No.</th>
                                        <th>Bill ref. No (FDBC/ TT-Foreign/ LDBC/ TT-Local)</th>
                                        <th>Realize Date</th>
                                        <th>Currency</th>
                                        <th>Con. Rate</th>
                                        <th>Invoice Value</th>
                                        <th>Realized Value</th>
                                        <th>Short Realization</th>

                                        @if(!empty($distribution_tiles))
                                            @foreach($distribution_tiles as $key=>$val)
                                                <th>{{ $val }}</th>
                                            @endforeach
                                        @endif

                                        @if(!empty($deduction_tiles))
                                            @foreach($deduction_tiles as $key=>$val)
                                                <th>{{ $val }}</th>
                                            @endforeach
                                        @endif
                                        
                                        @if(!empty($bank_charge_tiles))
                                            @foreach($bank_charge_tiles as $key=>$val)
                                                <th>{{ $val }}</th>
                                            @endforeach
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($lists) && $lists['list']->count())
                                    @foreach($lists['list'] as $item)
                                    <tr>
                                        <td>{{ $item['sales_contract'] }}</td>
                                        <td>{{ $item['lc_no'] }}</td>
                                        <td>{{ $item['buyer'] }}</td>
                                        <td>{{ $item['style_no'] }}</td>
                                        <td>{{ $item['po_no'] }}</td>
                                        <td>{{ $item['invoice_no'] }}</td>
                                        <td>{{ $item['bill_ref_no'] }}</td>
                                        <td>{{ $item['realize_date'] }}</td>
                                        <td>{{ $item['currency_name'] }}</td>
                                        <td>{{ $item['con_rate'] }}</td>
                                        <td>{{ $item['invoice_value'] }}</td>
                                        <td>{{ $item['realized_value'] }}</td>
                                        <td>{{ $item['short_realization'] }}</td>

                                        @if(!empty($distribution_tiles))
                                            @foreach($distribution_tiles as $code=>$title)
                                                <td>
                                                    @php $distribution = collect($item['distribution'])->where('code', $code)->flatten(); @endphp    
                                                    {{ $distribution[0]??0 }}
                                                </td>
                                            @endforeach
                                        @endif

                                        @if(!empty($deduction_tiles))
                                            @foreach($deduction_tiles as $code=>$title)
                                                <td>
                                                    @php $deduction = collect($item['deduction'])->where('code', $code)->flatten(); @endphp    
                                                    {{ $deduction[0]??0 }}
                                                </td>
                                            @endforeach
                                        @endif

                                        @if(!empty($bank_charge_tiles))
                                            @foreach($bank_charge_tiles as $code=>$title)
                                                <td>
                                                    @php $deduction = collect($item['foreign_bank_charge'])->where('code', $code)->flatten(); @endphp    
                                                    {{ $deduction[0]??0 }}
                                                </td>
                                            @endforeach
                                        @endif
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="13" class="text-center text-danger">No Data Found</td>
                                    </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <br>
    </div>
</div>