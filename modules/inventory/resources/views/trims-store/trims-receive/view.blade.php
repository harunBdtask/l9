@extends('subcontract::layout')
@section("title","Trims Receive")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Receive</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="header-section" style="padding-bottom: 0;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="order_pdf" data-value="po_details" class="btn"
                                   href="{{ url('inventory/trims-store/receive/pdf/'.$receive->id) }}">
                                    <em class="fa fa-file-pdf-o"></em>
                                </a>
                                <a class="btn"
                                   href="{{url('inventory/trims-store/receive/excel/'. $receive->id)}}"><i
                                        class="fa fa-file-excel-o"></i>
                                </a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Trims Receive</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>

                        @include('inventory::trims-store.trims-receive.view-body')

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
