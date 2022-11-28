@extends('basic-finance::layout')
@section("title","Procurement Requisition")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Procurement Requisitions</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        {{--                        <div class="header-section" style="padding-bottom: 0px;">--}}
                        {{--                            <div class="pull-right" style="margin-bottom: -5%;">--}}
                        {{--                                <a id="order_pdf" data-value="po_details" class="btn"--}}
                        {{--                                   href="{{ url('subcontract/dyeing-process/recipe-entry/pdf/'.$dyeingRecipe->id) }}"><i--}}
                        {{--                                        class="fa fa-file-pdf-o"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Requisition Details</span>
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
                                                    <b>Requisition ID :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $procurementRequisition->requisition_uid }}  </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Factory :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $procurementRequisition->factory->factory_name }}  </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Date : </b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ \Carbon\Carbon::create($procurementRequisition->date)->toFormattedDateString() }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Project :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $procurementRequisition->project->project }} </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-5" style="float: right; position:relative;margin-top:30px">
                                        <table class="borderless">
                                            <tbody>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Department:</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $procurementRequisition->department->department }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Unit :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $procurementRequisition->unit->unit }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Requisition By:</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $procurementRequisition->createdBy->screen_name }}  </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <thead>
                                    <tr>
                                        <th>Item Type</th>
                                        <th>Item Category</th>
                                        <th>Item</th>
                                        <th>Item Description</th>
                                        <th>Brand</th>
                                        <th>Origin</th>
                                        <th>Uom</th>
                                        <th>Qty</th>
                                        <th>Expected Delivery Date</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $requisitionDetails = $procurementRequisition->procurementRequisitionDetails;
                                    @endphp

                                    @forelse($requisitionDetails as $detail)
                                        @php
                                            $itemType = ucwords(str_replace('_', ' ', $detail->item_type));
                                        @endphp

                                        <tr>
                                            <td>{{ $itemType }}</td>
                                            <td>{{ $detail->itemCategory->name }}</td>
                                            <td>{{ $detail->item->name }}</td>
                                            <td>{{ $detail->item_description }}</td>
                                            <td>{{ $detail->brand->name }}</td>
                                            <td>{{ $detail->origin }}</td>
                                            <td>{{ $detail->uom->unit_of_measurement ?? $detail->uom->name }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>{{ \Carbon\Carbon::create($detail->expected_delivery_date)->toFormattedDateString() }}</td>
                                            <td>{{ $detail->remarks }}</td>
                                        </tr>
                                    @empty
                                        <tr class="tr-height">
                                            <td colspan="10" class="text-center text-danger">No Account Found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>

                                <div class="row">
                                    <div class="col-md-4">
                                        <table style="border: 1px solid black;width: 48%;">
                                            <thead>
                                            <tr>
                                                <td class="text-center">
                                                    <span style="font-size: 12pt; font-weight: bold;">Prepared By</span>
                                                    <br>
                                                </td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <center>
                                        <div class="col-md-4">
                                            <table style="border: 1px solid black;width: 48%;">
                                                <thead>
                                                <tr>
                                                    <td class="text-center">
                                                        <span
                                                            style="font-size: 12pt; font-weight: bold;">Checked By</span>
                                                        <br>
                                                    </td>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </center>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-2">
                                        <table style="border: 1px solid black;width: 99%;">
                                            <thead>
                                            <tr>
                                                <td class="text-center">
                                                    <span style="font-size: 12pt; font-weight: bold;">Approved By</span>
                                                    <br>
                                                </td>
                                            </tr>
                                            </thead>
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
