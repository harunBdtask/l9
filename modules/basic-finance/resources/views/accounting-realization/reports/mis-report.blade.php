@extends('basic-finance::layout')
@section('title', 'Payment Realization List')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Payment Realization List</h2>
            </div>
            <div class="box-body">
                @include('partials.response-message')
                <div class="row">
                    <div class="col-md-10">
                        {!! Form::open(['url' => '/basic-finance/accounting-realization/mis-report', 'method' => 'GET']) !!}
                        <div class="form-group row">
                            <label class="control-label col-md-1 text-right">Date</label>
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <input type="date" class="form-control form-control-sm" name="start_date" value="{{ request()->get('start_date')??$start_date }}" placeholder="Start Date">
                                    <span class="input-group-addon">To</span>
                                    <input type="date" class="form-control form-control-sm" name="end_date" value="{{ request()->get('end_date')??$end_date }}" placeholder="End Date">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success btn-sm">Search</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-2 text-right">
                        <a id="batch_excel" data-value="" class="btn" href="{{ url('basic-finance/accounting-realization/mis-report-excel?start_date='.$start_date.'&end_date='.$end_date) }}">
                            <i class="fa fa-file-excel-o"></i>
                        </a>
                    </div>
                </div>

                @php 
                    $distribution_tiles = $lists['dynamic_titles']['distribution']??[];
                    $deduction_tiles = $lists['dynamic_titles']['deduction']??[];
                    $bank_charge_tiles = $lists['dynamic_titles']['bank_charge']??[];
                @endphp
                <div class="table-responsive">
                <table class="reportTable">
                    <thead>
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
                        <tr class="tr-height">
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
                      <tr class="tr-height">
                        <td colspan="13" class="text-center text-danger">No Data Found</td>
                      </tr>
                    @endif
                    </tbody>

                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
