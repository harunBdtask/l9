
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th><b>SL</b></th>
                                    <th><b>Party</b></th>
                                    <th><b>Fabric Composition</b></th>
                                    <th><b>Fab Type</b></th>
                                    <th><b>Color</b></th>
                                    <th><b>L/D No</b></th>
                                    <th><b>Color Type</b></th>
                                    <th><b>Fin Dia</b></th>
                                    <th><b>Dia Type</b></th>
                                    <th><b>GSM</b></th>
                                    <th><b>Receive Qty</b></th>
                                    <th><b>UOM</b></th>
                                    <th><b>Issue Qty</b></th>
                                    <th><b>Balance</b></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockSummery as $summery)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $summery->supplier->name }}</td>
                                        <td class="text-center">{{ $summery->fabricComposition->construction }}</td>
                                        <td class="text-center">{{ $summery->fabricType->construction_name }}</td>
                                        <td class="text-center">{{ $summery->color->name }}</td>
                                        <td class="text-center">{{ $summery->ld_no }}</td>
                                        <td class="text-center">{{ $summery->colorType->color_types }}</td>
                                        <td class="text-center">{{ $summery->finish_dia }}</td>
                                        <td class="text-center">{{ $summery->dia_type_value }}</td>
                                        <td class="text-center">{{ $summery->gsm }}</td>
                                        <td class="text-center">{{ $summery->receive_qty }}</td>
                                        <td class="text-center">{{ $summery->unitOfMeasurement->unit_of_measurement }}</td>
                                        <td class="text-center">{{ $summery->issue_qty }}</td>
                                        <td class="text-center">{{ ($summery->receive_qty - $summery->receive_return_qty) - ($summery->issue_qty - $summery->issue_return_qty) }}</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            
                      

