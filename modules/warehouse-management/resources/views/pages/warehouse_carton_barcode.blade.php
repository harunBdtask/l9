@extends('warehouse-management::layout')

@section('styles')
    <style>
        .barcode-container {
            padding-top: 6px;
            text-align: center;
            border-left: 1px solid #0a0c0d;
            border-top: 1px solid #0a0c0d;
            border-bottom: 1px solid #0a0c0d;
            border-right: 1px solid #0a0c0d;
            height: 60px;
        }
        .barcode-serial > p {
            text-align: center;
        }

        .company-info > p {
            text-align: center;
        }
        .main-info {
            border-left: 1px solid #0a0c0d;
            border-top: 1px solid #0a0c0d;
            border-right: 1px solid #0a0c0d;
        }

        .carton-info-left {
            text-align: left;
            float: left;
            margin-top: -12px;
            margin-left: 40px;
        }

        .carton-info-right {
            text-align: right;
            float: right;
            margin-top: -12px;
            margin-right: 40px;
        }

        @media print {
            .padding {
                padding: 0px;
            }

            .app-header ~ .app-body {
                padding: 0px!important;
            }

            .aside, nav, .navbar, .app-header, .noprint{
                display: none;
            }

            #printableArea {
                width: 457px;
                margin-left: 24%;
            }
        }
    </style>
@endsection
@section('title', 'Barcode')
@section('content')
    <div class="padding">
        <div class="col-md-6 col-md-offset-3">
            <div id="barcode-tag" class="box">
                <div class="box-body barcode-tag clearfix" id="printableArea">
                    <div class="main-info clearfix">
                        <div class="company-info">
                            <p>
                                <strong>{{ factoryName() }}</strong><br>
                                <strong>{{ factoryAddress() }}</strong>
                            </p>
                        </div>
                        <div class="carton-info-left">
                            <span><strong>Buyer :</strong> {{ $warehouse_carton->buyer->name }}</span>  <br>
                            <span><strong>Order :</strong> {{ $warehouse_carton->order->style_name }}</span>  <br>
                            <span><strong>PO :</strong> {{ $warehouse_carton->purchaseOrder->po_no }}</span>  <br>
                            <span><strong>Color :</strong> {{ $colors }}</span>  <br>
                            <span><strong>Size :</strong> {{ $sizes }}</span>
                        </div>
                        <div class="carton-info-right">
                            <span>{{ date('d/m/Y', strtotime($warehouse_carton->created_at)) }}<br>
                                <strong>By</strong> {{ $warehouse_carton->createdUser->screen_name }}</span><br>
                            <strong>Qty : {{ $warehouse_carton->garments_qty }}</strong>
                        </div>
                    </div>

                    <div class="barcode-container">
                        <?php echo DNS1D::getBarcodeSVG($warehouse_carton->barcode_no, "C128A", 1.2, 30, '', false);?>
                        <div class="barcode-serial clearfix" style="margin-top:-6px">
                            <p>
                                {{ $warehouse_carton->barcode_no }}
                            </p>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div><!-- /.col -->
    <div class="col-md-3 padding noprint">
        <button type="button" class="btn btn-primary" onclick="window.print();">
            <i class="fa fa-print"></i>
        </button>
    </div>
@endsection
