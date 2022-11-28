@extends('warehouse-management::layout')
@section('head-script')
    <script type="text/javascript">
        window.history.forward();
        function noBack(){ window.history.forward(); }
    </script>
@endsection
@section('title', 'Shipment Scan')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Shipment Scan</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message" style="margin-bottom: 20px;">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::open(['url' => '/warehouse-shipment-scan', 'method' => 'POST', 'id' => 'warehouse-shipment-form']) !!}
                        {!! Form::hidden('challan_no', $challan_no, ['id' => 'challan_no']) !!}
                        <div class="form-group row">
                            <div class="col-sm-8 col-sm-offset-2">
                                {!! Form::text('barcode_no', null, ['class' => 'form-control', 'id' => 'barcode_no', 'placeholder' => 'Scan barcode here', 'required' => true]) !!}
                                <span class="text-danger barcode_no"></span>
                                <span class="text-left">Challan No {{ $challan_no }}</span>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @if($challan_data && $challan_data->count())
                            <div class="form-group row">
                                <div class="col-sm-2 col-sm-offset-4">
                                    <button type="button" class="btn btn-success" id="create-challan">Create Challan
                                    </button>
                                </div>
                                <div class="col-sm-2">
                                    <a href="{{ url('/') }}" class="btn btn-danger">Close Challan</a>
                                </div>
                            </div>
                            <div class="table-responsive shipment-scan-table" style="margin-top: 20px;">
                                <table class="reportTable">
                                    <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Buyer</th>
                                        <th>Order/Style</th>
                                        <th>Purchase Order</th>
                                        <th>Garments Qty</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total_qty = 0;
                                    @endphp
                                    @foreach($challan_data as $challan)
                                        @php
                                            $total_qty += $challan->warehouseCarton->garments_qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $challan->warehouseCarton->buyer->name }}</td>
                                            <td>{{ $challan->warehouseCarton->order->style_name }}</td>
                                            <td>{{ $challan->warehouseCarton->purchaseOrder->po_no }}</td>
                                            <td>{{ $challan->warehouseCarton->garments_qty }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="4">Total</th>
                                        <th>{{ $total_qty }}</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).on('click', '#create-challan', function (e) {
            e.preventDefault();
            var challanNo = $('#challan_no').val();
            var messageElement = $('.flash-message');
            var confirmChallanCreate = confirm('Are you sure you want to create this challan?');
            if (challanNo && confirmChallanCreate) {
                showLoader();
                var url = window.location.protocol + "//" + window.location.host + '/warehouse-shipment-challan-create';
                var data = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    challan_no: challanNo
                };
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data
                }).done(function (response) {
                    hideLoader();

                    if (response.status == 'success') {
                        messageElement.append(response.message);
                        setTimeout(redirectToList(challanNo), 2000);
                    }

                    if (response.status == 'danger') {
                        messageElement.append(response.message);
                    }
                });
            }
        });

        function redirectToList(challanNo) {
            let url = window.location.protocol + "//" + window.location.host + "/warehouse-shipment-challans/" + challanNo;
            window.location.href = url;
        }
    </script>
@endsection