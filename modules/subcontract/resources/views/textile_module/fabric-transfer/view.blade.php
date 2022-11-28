@extends('subcontract::layout')
@section("title","Material Fabric Transfer")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Material Fabric Transfer</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="order_pdf" data-value="po_details" class="btn"
                                   href="{{ url('subcontract/material-fabric-transfer/pdf/'.$fabricTransfer->id) }}"><i
                                        class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span
                                            style="font-size: 12pt; font-weight: bold;">Material Fabric Transfer</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        @include('subcontract::textile_module.fabric-transfer.view-body')


                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
