@extends('skeleton::layout')
@section('content')
    @push('style')
        <style>

        </style>
    @endpush
    <div class="padding">

        @if(Session::has('permission_of_date_wise_fabric_report_view') || Session::get('user_role') == 'super-admin')
            <div class="box knit-card">
                <div class="box-header">
                    <h2>Date Wise Fabric Status</h2>
                    <div class="clearfix"></div>
                    @if($report_data && $report_data->count() > 0)
                        <div class="box-tool">
                            <ul class="nav">
                                <li class="nav-item inline">
                                    <a href="{{url('date-wise-fabric-report-pdf-download?'.Request::getQueryString())}}" class="nav-link btn btn-xs btn-default">
                                        <i class="fa fa-file-pdf-o"> PDF Download</i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="box-body b-t">

                    {!! Form::open( ['url' =>   'date-wise-fabric-report-generate', 'method' =>  'GET']) !!}
                    <table class="table table-bordered">
                        <thead>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th></th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{!! Form::date('from_date',Request::get('from_date') ?? null,['class'=>'form-control form-control-sm']) !!}</td>
                            <td>{!! Form::date('to_date',Request::get('to_date') ?? null,['class'=>'form-control form-control-sm']) !!}</td>
                            <td>{!! Form::button('<i class="fa fa-search"></i> Search',['class'=>'btn btn-success','type'=>'submit','id'=>'id-button']) !!}</td>
                        </tr>
                        </tbody>
                    </table>
                    {!! Form::close() !!}

                    @if(isset($report_data) && $report_data->count() > 0)
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Buyer</th>
                                <th>Booking No</th>
                                <th>Fab. Composition</th>
                                <th>Fabric Type</th>
                                <th>Color</th>
                                <th>GSM</th>
                                <th>Total Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $grand_total_fabric = 0;
                                $buyer = '';
                                $booking_no = '';
                            @endphp
                            @foreach($report_data as $data)
                                <tr>
                                    @if($buyer != $data->buyer->name)
                                        <td rowspan="{{$report_data->where('buyer_id',$data->buyer_id )->count()}}">{{$data->buyer->name}}</td>
                                    @endif
                                    @if($booking_no != $data->order->booking_no)
                                        <td rowspan="{{$report_data->where('order_id',$data->order_id )->count()}}">{{$data->order->booking_no}}</td>
                                    @endif
                                    <td>{{$data->fabric_composition}}</td>
                                    <td>{{$data->fabric_type->fabric_type_name}}</td>
                                    <td>{{$data->color->name}}</td>
                                    <td>{{$data->fabric_gsm}}</td>
                                    <td>{{$data->total_fabric_qty}} Kg</td>
                                </tr>
                                @php
                                    $grand_total_fabric += $data->total_fabric_qty;
                                    $buyer = $data->buyer->name;
                                    $booking_no = $data->order->booking_no;
                                @endphp
                            @endforeach
                            <tr>
                                <td colspan="6"><b>Total</b></td>
                                <td><b>{{$grand_total_fabric}} Kg</b></td>
                            </tr>
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        @endif
    </div>
@endsection
@push('script-head')
    <script>

    </script>
@endpush
