@extends('skeleton::layout')
@push('style')
    <style>
        .bg-yes {
            background: #3496130d;
            color: #00a65a;
            display: block;
            font-weight: bold;
        }

        .bg-no {
            background: #f9f2f4;
            color: #a94442;
            display: block;
            font-weight: bold;
        }

        .buyer_id + .select2-container .select2-selection--single {
            width: 250px;
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_recap_report_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="box knit-card">
                <div class="box-header print-delete btn-info">
                    <h2>Incomplete Order List</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t print-delete">
                    <div class="row print-delete">
                        <div class="col-md-10">
                            <form class="form-inline print-delete" method="GET" action="{{ url('order-current-status-search') }}" role="search">
                                <div class="form-group">
                                    {!! Form::select('buyer_id',$buyers,request()->buyer_id ??null,['class'=>'form-control form-control-sm buyer_id select2-input','placeholder'=>'Select Buyer']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::selectMonth('month',request()->month ?? date('n'),['class'=>'form-control form-control-sm select2-input']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::selectRange('year', date('Y',strtotime('5 years ago')), date('Y',strtotime('+5 years')),request()->year ?? date('Y'),['class'=>'form-control form-control-sm select2-input']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::select('factory_id', $factory,request()->factory_id ??null,['class'=>'form-control form-control-sm select2-input','placeholder'=>'Select Factory']) !!}
                                </div>
                                <button type="submit" class="btn btn-sm white m-b">Search</button>
                            </form>
                        </div>
                        <div class="col-md-2 print-delete" align="right">
                            <ul>
                                {{--<li style="list-style: none;display: inline-block"><a  id="print"><i class=" fa fa-print" ></i>&nbsp;Print</a></li>--}}
                                <li style="list-style: none;display: inline-block"><a href="{{ url('order-current-status-pdf?buyer_id='.request()->buyer_id.'&month='.request()->month.'&year='.request()->year.'&factory_id'.request()->factory_id)}}" class="hidden-print btn btn-xs" title="Download this pdf"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a></li>
                                {{--<li style="list-style: none;display: inline-block"><a href="{{ url('recap-report-excel-download?buyer_id='.request()->buyer_id.'&month='.request()->month.'&year='.request()->year)}}" class="hidden-print btn btn-xs" title="Download this excel file"><i class="fa fa-file-excel-o"></i>&nbsp;Excel</a></li>--}}
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix print-delete"></div>
                    <div id="parentTableFixed" class="table-responsive">
                        @if(isset($orders_list) && $orders_list->count() > 0)
                            <table class="reportTable reportTableCustom" id="fixTable">
                                <thead>
                                <tr>
                                    <th>Buyer</th>
                                    <th>Booking No</th>
                                    <th>Style No</th>
                                    <th>Dealing Merchant</th>
                                    <th>No Of PO Created</th>
                                    <th>Is Budget Created</th>
                                    <th>Is Fabric Booked</th>
                                    <th>Knitting details Given</th>
                                    <th>Shipment Date</th>
                                    <th>Company</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders_list as $orders_data)
                                    <tr>
                                        <td>{{$orders_data['buyer']}}</td>
                                        <td>{{$orders_data['booking_no']}}</td>
                                        <td>{{$orders_data['style_no']}}</td>
                                        <td>{{$orders_data['dealing_merchant']}}</td>
                                        <td>{{$orders_data['is_po_created']}}</td>
                                        <td>{!! $orders_data['is_budget_created'] !!}</td>
                                        <td>{!! $orders_data['is_budget_fabric_booked'] !!}</td>
                                        <td>{!! $orders_data['is_budget_knitting_info'] !!}</td>
                                        <td>{{date('d M Y',strtotime($orders_data['order_shipment_date']))}}</td>
                                        <td>{!! $orders_data['factory'] !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{--<div class="text-center print-delete">{{$orders_list->appends($_GET)->links() }}</div>--}}
                            {{--                            <div class="text-center print-delete">{{$orders_list->links() }}</div>--}}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@push('script-head')

@endpush
@section('scripts')
    <script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#fixTable").tableHeadFixer();
        });
    </script>
@endsection
