@extends('subcontract::layout')
@section("title","Dyeing Recipes")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Recipes</h2>
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
                                <a id="order_pdf" data-value="po_details" class="btn" href="{{ url('dyeing/recipes/pdf/'.$recipe->id) }}"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;"> Recipes </span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        
                        <div class="row p-x-1">
                            <div class="col-md-12">
                                
                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <tr>
                                        <th class="text-left">Factory</th>
                                        <td>{{$recipe->factory->name}}</td>
                                        <th class="text-left">Buyer</th>
                                        <td>{{$recipe->buyer->name}}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-left">Batch No</th>
                                        <td>{{$recipe->dyeing_batch_no}}</td>
                                        <th class="text-left">M/C No</th>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <th class="text-left">Fabric Description</th>
                                        <td>{{$recipe->subDyeingBatch->fabric_composition_value ?? ''}}</td>
                                        <th class="text-left">Color(Batch)</th>
                                        <td>{{$recipe->subDyeingBatch->color->name ?? ''}}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-left">GSM</th>
                                        <td>{{$recipe->subDyeingBatch->gsm ?? ''}}</td>
                                        <th class="text-left">M/C Capacity</th>
                                        <td>{{$recipe->subDyeingBatch->total_machine_capacity ?? ''}}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-left">Order No</th>
                                        <td>{{ collect($recipe->subDyeingBatch->orders_no)->implode('orders_no',', ')}}</td>
                                        <th class="text-left">LD No</th>
                                        <td>{{$recipe->subDyeingBatch->ld_no}}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-left">Batch Fab Weight</th>
                                        <td>{{$recipe->subDyeingBatch->total_batch_weight}}</td>
                                        <th class="text-left">Recipe Date</th>
                                        <td>{{$recipe->recipe_date}}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-left">Liquor Ratio</th>
                                        <td>{{$recipe->liquor_ratio}}</td>
                                        <th class="text-left">Total Liq Level</th>
                                        <td>{{$recipe->total_liq_level}}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-left">Shift</th>
                                        <td>{{$recipe->Shift->shift_name}}</td>
                                        <th class="text-left">Remarks</th>
                                        <td>{{$recipe->remarks}}</td>
                                    </tr>

                                  
                                </table>

                                <table class="reportTable" style="width: 100%;margin-top: 30px;">
                                    <thead>

                                    <tr>
                                        <th rowspan="2">Function</th>
                                        <th rowspan="2">Chemical Name</th>
                                        <th colspan="2">Dosing</th>
                                        <th rowspan="2">Quantity</th>
                                        <th rowspan="2">Unit</th>
                                        <th rowspan="2">1st Additional Quantity</th>
                                        <th rowspan="2">2nd Additional Quantity</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>

                                    <tr>
                                        <th>Percentage</th>
                                        <th>G/Ltr</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                              
                                    @php
                                        $operationWiseRecipeDetails = $recipe->recipeDetails->groupBy('recipe_operation_id');
                                    @endphp
                                    @foreach ($operationWiseRecipeDetails as $details)
                                        <tr>
                                            <td style="background-color: lightgrey;" colspan="9">
                                                <b>{{ $details->first()->recipeOperation->name }}</b>
                                            </td>
                                        </tr>
                                        @foreach ($details as $item)
                                            <tr>
                                                <td>{{ $item->recipeFunction->function_name }}</td>
                                                <td>{{ $item->item->name }}</td>
                                                <td>{{ $item->percentage }}</td>
                                                <td>{{ $item->g_per_ltr }}</td>
                                                <td>{{ $item->total_qty }}</td>
                                                <td>{{ $item->unitOfMeasurement->name }}</td>
                                                <td>{{ $item->additional }}</td>
                                                <td></td>
                                                <td>{{ $item->remarks }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td class="text-left" colspan="9">Note :</td>
                                    </tr>
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

                                <br>
                                <br>
                                <br>

                                <div class="row">
                                    <div class="col-md-4">
                                        <table style="border-top-style: solid;width: 48%;">
                                            <thead>
                                            <tr>
                                                <td class="text-center">
                                                    <span style="font-size: 12pt; font-weight: bold;">Print Date & Time</span>
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
