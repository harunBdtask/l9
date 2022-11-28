@extends('subcontract::layout')
@section("title","Machine Transfer Challan")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Machine Transfer Challan</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="header-section" style="padding-bottom: 0;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="order_pdf" data-value="po_details" class="btn"
                                   href="{{ url('mc-inventory/machine-transfer/pdf/'.$transfer->id) }}">
                                    <em class="fa fa-file-pdf-o"></em>
                                </a>
                            </div>
                        </div>

                        @include('McInventory::machine-modules.machine-transfer.view-body')

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
