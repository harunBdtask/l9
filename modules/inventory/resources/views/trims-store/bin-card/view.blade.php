@extends('subcontract::layout')
@section("title","Trims Store Bin Card")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Store Bin Card</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="header-section" style="padding-bottom: 0;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="order_pdf" data-value="po_details" class="btn"
                                   href="{{ url('inventory/trims-store/bin-card/pdf/'.$binCard->id) }}">
                                    <em class="fa fa-file-pdf-o"></em>
                                </a>
                                <a class="btn"
                                   href="{{ url('inventory/trims-store/bin-card/excel/'.$binCard->id) }}">
                                    <em class="fa fa-file-excel-o"></em>
                                </a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Trims Store Bin Card</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>

                        @include('inventory::trims-store.bin-card.view-body')

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
