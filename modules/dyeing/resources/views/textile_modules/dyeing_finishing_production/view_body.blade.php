<div>
    <div class="padding">
        <div class="box">


            <div class="box-body">

                <div class="row">
                    <div class="col-md-12">



                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Brush</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <div class="row p-x-1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5" style="float: left; position:relative; margin-top:30px">
                                        <table class="borderless">

                                            <tbody>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Factory :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->factory->factory_name}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Buyer :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->buyer->name}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Entry Basis :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->entry_basis_value}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Batch No :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->dyeing_batch_no}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Order No :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->textile_order_no}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Dyeing Unit :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->subDyeingUnit->name}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Production Date :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->production_date}} </td>
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
                                                    <b>M/C Name :</b>
                                                </td>
                                                @php
                                                    $machines = collect($finishingProduction->dyeingBatch->machineAllocations)
                                                                        ->pluck('machine.name')->implode(', ');
                                                @endphp
                                                <td style="padding-left: 30px;"> {{ $machines }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Loading Time :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->loading_date}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Unloading Time :</b>
                                                </td>

                                                <td style="padding-left: 30px;"> {{$finishingProduction->unloading_date}} </td>
                                            </tr>

                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Shift :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->shift->shift_name}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Length Shrinkage :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->length_shrinkage}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Width Shrinkage :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->width_shrinkage}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Remarks :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$finishingProduction->remarks}} </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <thead>
                                    <tr>
                                        <th>Batch No</th>
                                        <th>Order Name</th>
                                        <th>Fab Description</th>
                                        <th>Dia & Dia Type</th>
                                        <th>GSM</th>
                                        <th>Fabric Color</th>
                                        <th>Batch Qty/Order Qty</th>
                                        <th>No Of Roll</th>
                                        <th>Finish Qty</th>
                                        <th>Reject Roll</th>
                                        <th>Reject Qty</th>
                                        <th>Unit Cost</th>
                                        <th>Total Cost</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @forelse ($finishingProduction->finishingProductionDetails as $production)
                                      <tr>
                                        <td>{{$production->dyeing_batch_no}}</td>
                                        <td>{{$production->textile_order_no}}</td>
                                        <td>{{$production->fabric_composition_value}}</td>
                                        <td>{{ collect($production->dia_type_value)->implode('name',', ') }}</td>
                                        <td>{{$production->gsm}}</td>
                                        <td>{{$production->color->name}}</td>
                                        <td>{{$production->batch_qty ?? 'N/A'}}/{{$production->order_qty}}</td>
                                        <td>{{$production->no_of_roll}}</td>
                                        <td>{{$production->finish_qty}}</td>
                                        <td>{{$production->reject_roll}}</td>
                                        <td>{{$production->reject_qty}}</td>
                                        <td>{{$production->unit_cost}}</td>
                                        <td>{{$production->total_cost}}</td>
                                     </tr>
                                      @empty
                                      <tr>
                                        <td colspan="13">No Data</td>
                                    </tr>
                                      @endforelse
                                    </tbody>
                                </table>

                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
</div>
