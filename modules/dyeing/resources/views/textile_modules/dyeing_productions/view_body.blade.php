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
                                        <span style="font-size: 12pt; font-weight: bold;">Dyeing Production</span>
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
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->factory->factory_name}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Buyer :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->buyer->name}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Order No :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->dyeing_order_no}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Batch No :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->dyeing_batch_no}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Loading Time :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->loading_date}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Shift :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->shift->shift_name}} </td>
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
                                                    <b>Dyeing Unit :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->dyeingUnit->name}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Production Date :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->production_date}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>M/C Name :</b>
                                                </td>
                                                @php
                                                    $machines = collect($dyeingProduction->dyeingBatch->machineAllocations)->pluck('machine.name')->implode(', ');
                                                @endphp
                                                <td style="padding-left: 30px;"> {{ $machines }} </td>
                                            </tr>

                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Unloading Time :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->unloading_date}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Remarks :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{$dyeingProduction->remarks}} </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <thead>
                                    <tr>
                                        <th>Batch No</th>
                                        <th>Order No</th>
                                        <th>Fab Description</th>
                                        <th>Dia & Dia Type</th>
                                        <th>GSM</th>
                                        <th>Fabric Color</th>
                                        <th>Batch Qty</th>
                                        <th>No Of Roll</th>
                                        <th>Dyeing Production Qty</th>
                                        <th>Reject Roll</th>
                                        <th>Reject Qty</th>
                                        <th>Unit Cost</th>
                                        <th>Total Cost</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dyeingProduction->dyeingProductionDetails as $production)
                                        <tr>
                                            <td>{{$production->dyeing_batch_no}}</td>
                                            <td>{{$production->dyeing_order_no}}</td>
                                            <td>{{$production->fabric_composition_value}}</td>
                                            <td>{{ collect($production->dia_type_value)->implode('name',', ') }}</td>
                                            <td>{{$production->gsm}}</td>
                                            <td>{{$production->color->name}}</td>
                                            <td>{{$production->batch_qty}}</td>
                                            <td>{{$production->no_of_roll}}</td>
                                            <td>{{$production->dyeing_production_qty}}</td>
                                            <td>{{$production->reject_roll_qty}}</td>
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