@extends('subcontract::layout')
@section("title","Recipe Details")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Recipe Details</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="header-section" style="padding-bottom: 0;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="order_pdf" data-value="po_details" class="btn"
                                   href="{{ url('subcontract/dyeing-process/recipe-entry/pdf/'.$dyeingRecipe->id) }}">
                                    <em class="fa fa-file-pdf-o"></em>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-2">
                                {{-- <img src="https://commondatastorage.googleapis.com/codeskulptor-assets/lathrop/asteroid_blue.png" alt=""> --}}
                            </div>

                            <div class="col-md-6">
                                @php
                                    $factoryName = factoryName();
                                    $factoryAddress = factoryAddress();
                                @endphp
                                <h2 style="margin-bottom: 1%;" class="text-center">{{ $factoryName }}</h2>
                                <p style="margin-bottom: 1%;" class="text-center">{{ $factoryAddress }}</p>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Recipe Details</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <div class="row p-x-1">
                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-4" style="float: left; position:relative; margin-top:30px">
                                        <table class="borderless">
                                            <tbody>
                                            <tr>
                                                <td style="padding-left: 0;" class="text-right">
                                                    <strong>Recipe Date :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{ \Carbon\Carbon::make($dyeingRecipe->recipe_date)->format('d-M-Y') }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;" class="text-right">
                                                    <strong>Recipe Id :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{ $dyeingRecipe->recipe_uid }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;" class="text-right">
                                                    <strong>Batch No :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{$dyeingRecipe->subDyeingBatch->batch_no}} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;" class="text-right">
                                                    <strong>Requisition Id :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{ collect($dyeingRecipe->recipeRequisitions)->first()->requisition_uid ?? '' }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;" class="text-right">
                                                    <strong>Buyers Name :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{ $dyeingRecipe->supplier->name }} </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;" class="text-right">
                                                    <strong>Buyers Order No :</strong>
                                                </td>
                                                @php
                                                    $orderNos = collect($dyeingRecipe->subDyeingBatch->order_nos)->join(', ');
                                                @endphp
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{ $orderNos }} </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                    <div class="col-md-4" style="float: left; position:relative;margin-top:30px">
                                        <table class="borderless">
                                            <tbody>
                                            <tr>
                                                <td class="text-right">
                                                    <strong>LD No :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{$dyeingRecipe->ld_no}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <strong>Fabric Weight :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{ $dyeingRecipe->subDyeingBatch->total_batch_weight }}  </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <strong>Liquor Ratio :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{ $dyeingRecipe->liquor_ratio }} </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <strong>Total Liq :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{ $dyeingRecipe->total_liq_level }} </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <strong>Y.Lot :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{$dyeingRecipe->yarn_lot}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <strong>Machine No :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right">  {{ collect($machines)->implode(', ') }} </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <strong>Remarks :</strong>
                                                </td>
                                                <td style="padding-left: 30px;"
                                                    class="text-right"> {{ $dyeingRecipe->remarks }} </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-4" style="float: right; position:relative;margin-top:30px">
                                        <table class="reportTable">
                                            <thead>
                                                <tr>
                                                    <th>Fabric Composition</th>
                                                    <th>Fabric Type</th>
                                                    <th>GSM</th>
                                                    <th>Color</th>
                                                    <th>Fabric Weight</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dyeingRecipe->subDyeingBatch->batchDetails as $details)
                                                <tr>
                                                    <td>{{$details->fabric_composition_value}}</td>
                                                    <td>{{$details->fabricType->construction_name}}</td>
                                                    <td>{{$details->gsm}}</td>
                                                    <td>{{$details->subDyeingBatch->fabricColor->name}}</td>
                                                    <td>{{$details->batch_weight}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                </div>

                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <thead>

                                    <tr>
                                        <th>Item Name</th>
                                        <th>Dosing Percent</th>
                                        <th>Dosing Quantity</th>
                                        <th>G/Ltr</th>
                                        <th>GPL Quantity</th>
                                        <th>Unit</th>
                                        <th>Additional Quantity(KG)</th>
                                        <th>Remarks</th>
                                    </tr>

                                    </thead>
                                    <tbody>

                                    @php
                                        $operationWiseRecipeDetails = $dyeingRecipe->recipeDetails->groupBy('recipe_operation_id');
                                    @endphp

                                    @foreach ($operationWiseRecipeDetails as $details)
                                        <tr>
                                            <td class="text-center" style="background-color: lightgrey;" colspan="9">
                                                <strong>{{ $details->first()->recipeOperation->name }}</strong>
                                            </td>
                                        </tr>
                                        @foreach ($details as $item)
                                            <tr>
                                                <td>{{ $item->dsItem->name }}</td>
                                                <td>{{ $item->percentage }}</td>
                                                <td>
                                                    @if ($item->percentage)
                                                        {{ number_format($item->total_qty, 3) }}
                                                    @endif
                                                </td>
                                                <td>{{$item->g_per_ltr}}</td>
                                                <td>
                                                    @if ($item->g_per_ltr)
                                                        {{ number_format($item->total_qty, 3) }}
                                                    @endif
                                                </td>
                                                <td>{{$item->unitOfMeasurement->name}}</td>
                                                <td></td>
                                                <td>{{$item->remarks}}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach

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
                                                            style="font-size: 12pt; font-weight: bold;">Shift In-Charge</span>
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

                                <br>
                                <br>

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
@endsection
@section('scripts')
<script>

</script>
@endsection

