@extends('subcontract::layout')
@section("title","Bill")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Bill</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="header-section" style="padding-bottom: 0;">
                            <div class="pull-right" style="">
                                <a id="order_pdf" data-value="po_details" class="btn"
                                   href="{{ url('subcontract/sub-dyeing-goods-delivery/bill-view-pdf/'.$dyeingGoodsDelivery->id) }}">
                                    <em class="fa fa-file-pdf-o"></em>
                                </a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Bill</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>

                        @include('subcontract::textile_module.sub-dyeing-goods-delivery.bill.view-body')

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
