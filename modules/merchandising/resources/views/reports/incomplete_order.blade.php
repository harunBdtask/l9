@extends('skeleton::layout')
@push('style')

@endpush
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_recap_report_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="box knit-card">
                <div class="box-header print-delete">
                    <h2>Incomplete Order List</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t print-delete">
                    <div class="row print-delete">
                        <div class="col-md-8">
                            <form class="form-inline print-delete" method="GET" action="{{ url('incomplete-booking-search') }}" role="search">
                                <div class="form-group">
                                    {!! Form::select('dealing_merchant',$dealing_merchant,request()->dealing_merchant ??null,['class'=>'form-control form-control-sm','placeholder'=>'Select Dealing Merchant']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::selectMonth('month',request()->month ?? date('n'),['class'=>'form-control form-control-sm']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::selectRange('year', date('Y',strtotime('5 years ago')), date('Y',strtotime('+5 years')),request()->year ?? date('Y'),['class'=>'form-control form-control-sm']) !!}
                                </div>
                                <button type="submit" class="btn btn-sm white m-b">Search</button>
                            </form>
                        </div>
                        <div class="col-md-4 print-delete" align="right">
                            <ul>
                                {{--<li style="list-style: none;display: inline-block"><a  id="print"><i class=" fa fa-print" ></i>&nbsp;Print</a></li>--}}
                                <li style="list-style: none;display: inline-block"><a href="{{ url('incomplete-booking-search-pdf?dealing_merchant='.request()->dealing_merchant.'&month='.request()->month.'&year='.request()->year)}}" class="hidden-print btn btn-xs" title="Download this pdf"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a></li>
                                {{--<li style="list-style: none;display: inline-block"><a href="{{ url('recap-report-excel-download?buyer_id='.request()->buyer_id.'&month='.request()->month.'&year='.request()->year)}}" class="hidden-print btn btn-xs" title="Download this excel file"><i class="fa fa-file-excel-o"></i>&nbsp;Excel</a></li>--}}
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix print-delete"></div>
                    <div>
                        @if(isset($orders_list) && $orders_list->count() > 0)
                            <table class="reportTable reportTableCustom">
                                <thead>
                                <tr>
                                    <th>Order / Style No</th>
                                    <th>Booking No</th>
                                    <th>Buyer</th>
                                    <th>Dealing Merchant</th>
                                    <th>Team</th>
                                    <th>Order Qty</th>
                                    <th>Shipment Date</th>
                                    <th>Company Name</th>
                                    <th>Created Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders_list as $orders_data)
                                    <tr>
                                        <td style="background: red;color: white;font-weight: bold">{{ $orders_data->getOriginal('order_style_no')}}</td>
                                        <td style="background: red;color: white;font-weight: bold;padding: 3px">{{ $orders_data->booking_no ?? 'N/A'}}</td>
                                        <td>{{isset($orders_data->buyer->name) ? $orders_data->buyer->name : 'N/A'}}</td>
                                        <td>{{isset($orders_data->dealing_merchants->first_name) || isset($orders_data->dealing_merchants->last_name) ? $orders_data->dealing_merchants->first_name .' '.$orders_data->dealing_merchants->last_name : 'N/A'}}</td>
                                        <td>{{ $orders_data->team->team_name ?? 'N/A'}}</td>
                                        <td>{{$orders_data->total_quantity}}</td>
                                        <td style="background: red;color: white;font-weight: bold">{{date('d M Y',strtotime($orders_data->order_shipment_date))}}</td>
                                        <td style="background: #0F733B;color: #fff;font-weight: bold;letter-spacing: 1px">{{$orders_data->factory->factory_short_name}}</td>
                                        <td>{{date('d M Y',strtotime($orders_data->created_at))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="text-center print-delete">{{$orders_list->appends($_GET)->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@push('script-head')

@endpush
