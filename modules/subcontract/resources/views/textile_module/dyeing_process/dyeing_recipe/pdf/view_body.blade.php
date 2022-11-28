<div>
    <div class="padding">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row p-x-1">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-3" style="">
                                        <table class="borderless">
                                            <tbody>
                                                <tr>
                                                    <td style="padding-left: 0;" class="text-left">
                                                        <strong style="font-size: 16px;">Recipe Date :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ \Carbon\Carbon::make($dyeingRecipe->recipe_date)->format('d-M-Y') }} </td>
                                                    <td class="text-left">
                                                        <strong style="font-size: 16px;">LD No :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $dyeingRecipe->ld_no }}</td>
                                                    {{-- <td rowspan="7" style="vertical-align: top">
                                                        <table class="reportTable" style="width:80%;float: right;">
                                                            <thead>
                                                                <tr style="border: 1px solid black;">
                                                                    <th style="border: 1px solid black;">Fabric Composition</th>
                                                                    <th style="border: 1px solid black;">Fabric Type</th>
                                                                    <th style="border: 1px solid black;">GSM</th>
                                                                    <th style="border: 1px solid black;">Color</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($dyeingRecipe->subDyeingBatch->batchDetails as $details)
                                                                    <tr style="border: 1px solid black;">
                                                                        <td style="border: 1px solid black;">{{ $details->fabricComposition->construction }}
                                                                        </td>
                                                                        <td style="border: 1px solid black;">{{ $details->fabricType->construction_name }}
                                                                        </td>
                                                                        <td style="border: 1px solid black;">{{ $details->gsm }}</td>
                                                                        <td style="border: 1px solid black;">{{ $details->color->name }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td> --}}
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;" class="text-left">
                                                        <strong style="font-size: 16px;">Recipe Id :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $dyeingRecipe->recipe_uid }} </td>
                                                    <td class="text-left">
                                                        <strong style="font-size: 16px;">Fabric Weight :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $dyeingRecipe->subDyeingBatch->total_batch_weight }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;font-size: 16px;" class="text-left">
                                                        <strong style="font-size: 16px;">Batch No :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $dyeingRecipe->subDyeingBatch->batch_no }} </td>
                                                    <td class="text-left">
                                                        <strong style="font-size: 16px;">Liquor Ratio :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $dyeingRecipe->liquor_ratio }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;" class="text-left">
                                                        <strong style="font-size: 16px;">Requisition Id :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ collect($dyeingRecipe->recipeRequisitions)->first()->requisition_uid ?? '' }}
                                                    </td>
                                                    <td class="text-left">
                                                        <strong style="font-size: 16px;">Total Liq :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $dyeingRecipe->total_liq_level }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;" class="text-left">
                                                        <strong style="font-size: 16px;">Buyers Name :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $dyeingRecipe->supplier->name }} </td>
                                                    <td class="text-left">
                                                        <strong style="font-size: 16px;">Y.Lot :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $dyeingRecipe->yarn_lot }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0;" class="text-left">
                                                        <strong style="font-size: 16px;">Buyers Order No :</strong>
                                                    </td>
                                                    @php
                                                        $orderNos = collect($dyeingRecipe->subDyeingBatch->order_nos)->join(', ');
                                                    @endphp
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $orderNos }} </td>
                                                    <td class="text-left">
                                                        <strong style="font-size: 16px;">Machine No :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ collect($machines)->implode(', ') }} </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left">
                                                        <strong style="font-size: 16px;">Remarks :</strong>
                                                    </td>
                                                    <td style="padding-left: 30px;font-size: 16px;" class="text-left">
                                                        {{ $dyeingRecipe->remarks }} </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>

                                </div>

                                    <table class="reportTable" style="width:80%;margin-left:10%;margin-top:2%">
                                        <thead>
                                            <tr style="border: 1px solid black;">
                                                <th style="border: 1px solid black;font-size: 16px;width:60%">Fabric Composition</th>
                                                <th style="border: 1px solid black;font-size: 16px;">Fabric Type</th>
                                                <th style="border: 1px solid black;font-size: 16px;">GSM</th>
                                                <th style="border: 1px solid black;font-size: 16px;width:20%">Color</th>
                                                <th style="border: 1px solid black;font-size: 16px;width:20%">Fabric Weight</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dyeingRecipe->subDyeingBatch->batchDetails as $details)
                                                <tr style="border: 1px solid black;">
                                                    <td style="border: 1px solid black;font-size: 16px;">{{ $details->fabric_composition_value }}
                                                    </td>
                                                    <td style="border: 1px solid black;font-size: 16px;">{{ $details->fabricType->construction_name }}
                                                    </td>
                                                    <td style="border: 1px solid black;font-size: 16px;">{{ $details->gsm }}</td>
                                                    <td style="border: 1px solid black;font-size: 16px;">{{ $details->subDyeingBatch->fabricColor->name }}</td>
                                                    <td style="border: 1px solid black;font-size: 16px;">{{ $details->batch_weight }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <thead>

                                        <tr>
                                            <th style="font-size: 14px; width:40%">Item Name</th>
                                            <th style="font-size: 14px;">Dosing Percent</th>
                                            <th style="font-size: 14px;">Dosing Quantity</th>
                                            <th style="font-size: 14px;">G/Ltr</th>
                                            <th style="font-size: 14px;">GPL Quantity</th>
                                            <th style="font-size: 14px;">Unit</th>
                                            <th style="font-size: 14px;">Additional Quantity(KG)</th>
                                            <th style="font-size: 14px;">Remarks</th>
                                        </tr>

                                    </thead>
                                    <tbody>

                                    @php
                                        $operationWiseRecipeDetails = $dyeingRecipe->recipeDetails->groupBy('recipe_operation_id');
                                    @endphp

                                    @foreach ($operationWiseRecipeDetails as $details)
                                        <tr>
                                            <td class="text-center" style="background-color: lightgrey;" colspan="9">
                                                <strong style="font-size: 16px;">{{ $details->first()->recipeOperation->name }}</strong>
                                            </td>
                                        </tr>
                                        @foreach ($details as $item)
                                            <tr>
                                                <td style="font-size: 16px;">{{ $item->dsItem->name }}</td>
                                                <td style="font-size: 16px;">{{ $item->percentage }}</td>
                                                <td style="font-size: 16px;">
                                                    @if ($item->percentage)
                                                        {{ number_format($item->total_qty, 3) }}
                                                    @endif
                                                </td>
                                                <td style="font-size: 16px;">{{$item->g_per_ltr}}</td>
                                                <td style="font-size: 16px;">
                                                    @if ($item->g_per_ltr)
                                                        {{ number_format($item->total_qty, 3) }}
                                                    @endif
                                                </td>
                                                <td style="font-size: 16px;">{{$item->unitOfMeasurement->name}}</td>
                                                <td style="font-size: 16px;"></td>
                                                <td style="font-size: 16px;">{{$item->remarks}}</td>
                                            </tr>
                                        @endforeach
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
