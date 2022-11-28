@extends('basic-finance::layout')
@section("title","Procurement Requisition")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Procurement Quotation</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Quotation Details</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <div class="row p-x-1">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-5"
                                         style="float: left; position:relative; margin-top:30px">
                                        <table class="borderless">
                                            <tbody>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Item Name :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $quotation->item->item_group }}  </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Item Description :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $quotation->item_description }}  </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Supplier Name :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $quotation->supplier->name }}  </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Unit : </b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $quotation->uom->unit_of_measurement }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Unit Price :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $quotation->unit_price }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Last Modification Date :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ date('d M Y', strtotime($quotation->last_modified_at)) }}  </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Created by:</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $quotation->createdBy->screen_name }} </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>



                            </div>
                        </div>


                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection
