
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
                                        <span style="font-size: 12pt; font-weight: bold;">Fabric Receive</span>
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
                                            <td style="padding-left: 30px;"> {{ $fabricReceive->factory->group_name }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Party :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $fabricReceive->supplier->name }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Challan No :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $fabricReceive->challan_no }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Receive Basis :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $fabricReceive->receive_basis_value }} </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 0;">
                                                <b>Today Date :</b>
                                            </td>
                                            <td style="padding-left: 30px;"> {{ $currentDate }} </td>
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
                                                        <b>Sub Grey Store :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $fabricReceive->greyStore->name }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Order No :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;">{{ (collect($fabricReceive->challanOrders)->pluck('textileOrder.order_no')->implode(', '))??null }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Challan Date :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $fabricReceive->challan_date }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;">
                                                        <b>Required Operation :</b>
                                                    </td>
                                                    <td style="padding-left: 30px;"> {{ $fabricReceive->required_operations }}  </td>
                                                </tr>
                                              </tbody>
                                       </table>
                                    </div>
                                </div>

                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <thead>
                                    <tr>
                                        <th>Operation</th>
                                        <th>Order No</th>
                                        <th>Fab Composition</th>
                                        <th>Fab Type</th>
                                        <th>Color</th>
                                        <th>Color Type</th>
                                        {{-- <th>L/D No</th> --}}
                                        <th>Fin.Dia</th>
                                        <th>Dia Type</th>
                                        <th>GSM</th>
                                        <th>Grey Req Qty</th>
                                        <th>UOM</th>
                                        <th>Total Roll</th>
                                        <th>Receive Qty</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($fabricReceive->receiveDetailsByChallanNo as $receive)
                                        @if($receive->receive_qty)
                                        <tr>
                                            <td>{{ $receive->operation->name }}</td>
                                            <td>{{ $receive->textileOrder->order_no }}</td>
                                            <td>{{ $receive->fabric_description }}</td>
                                            <td>{{ $receive->fabricType->construction_name }}</td>
                                            <td>{{ $receive->color->name }}</td>
                                            <td>{{ $receive->colorType->color_types }}</td>
                                            {{-- <td></td> --}}
                                            <td>{{ $receive->finish_dia }}</td>
                                            <td>{{ $receive->dia_type_value['name']??null }}</td>
                                            <td>{{ $receive->gsm }}</td>
                                            <td>{{ $receive->grey_required_qty }}</td>
                                            <td>{{ $receive->unitOfMeasurement->unit_of_measurement }}</td>
                                            <td>{{ $receive->total_roll }}</td>
                                            <td>{{ $receive->receive_qty }}</td>
                                            <td>{{ $receive->remarks }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        
                                    
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
  
