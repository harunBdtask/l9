@extends('subcontract::layout')
@section("title","Batch Details")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Batch Card</h2>
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
                                <a id="order_pdf" data-value="po_details" class="btn" href="{{ url('subcontract/dyeing-process/batch-entry/pdf/'.$dyeingBatch->id) }}"><i class="fa fa-file-pdf-o"></i></a>
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
                                   <center>
                                    <p style="border-radius: 33px; border: 2px solid black;
                                              padding: 6px; width: 105px;margin-bottom: 3px;"><b>Dye House</b></p>
                                   </center>
                                   <p style="margin-bottom: 1%;" class="text-center">{{ $factoryAddress }}</p>
                                   {{-- <p class="text-center"><b>Phone:</b> 01876456534,01908764532  <b>Fax:</b> 097856</p> --}}
                                   </div>
                                

                                   <div class="col-md-1" style="margin-right: -6%;">
                                    <span>DT : </span>
                                   </div>
                                   <div class="col-md-2"> <p style="text-decoration-line: underline;
                                    text-decoration-style: dotted;
                                    text-decoration-color: black;">{{ $dyeingBatch->batch_date }}</p></div>
                            </div>
                            <div class="row" style="margin-top: 3%">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <center>
                                        <p style="border-radius: 33px;
                                        border: 2px solid black;
                                        padding: 6px;
                                        width: 151px;
                                        margin-bottom: 3px;"><b>BATCH CARD</b></p>
                                    </center>
                                </div>
                                <div class="col-md-1" style="margin-right: -35px;">
                                    <span>Ch No : </span>
                                </div>
                                <div class="col-md-2">
                                    <p style="border-bottom: 1px dashed;width: 107%;
                                    margin-top: 16px;"></p>
                                </div>
                            </div>
                        <div class="row p-x-1">
                            <div class="col-md-12">

                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-1" style="margin-right: -3%;">
                                        <p style="margin-top: 17%;">Buyer :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed; width: 26%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;" class="text-center">{{ $dyeingBatch->supplier->name }}</p>
                                    </div>

                                    <div class="col-md-1" style="margin-right: -3%;">
                                        <p style="margin-top: 17%;">Style :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed; width: 29%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;" class="text-center"></p>
                                    </div>

                                    <div class="col-md-1" style="margin-right: -2%;">
                                        <p style="margin-top: 17%;">Lot No :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed;width: 26%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-1" style="margin-right: -3%;">
                                        <p style="margin-top: 17%;">Color :</p>
                                    </div>
                                    <div class="col-md-7" style="border-bottom: 1px dashed;">
                                        <p style="margin-bottom: 3.7%; line-height: 1px;" class="text-center">{{ $dyeingBatch->color->name }}</p>
                                    </div>

                                    <div class="col-md-1" style="margin-right: -2%;">
                                        <p style="margin-top: 17%;">M/c No :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed;width: 28%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;" class="text-center">{{ $machines }}</p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-1" style="margin-right: -3%;">
                                        <p style="margin-top: 17%;">Fabric :</p>
                                    </div>
                                    <div class="col-md-5" style="border-bottom: 1px dashed; ">
                                        <p style="margin-bottom: 5.5%;; line-height: 1px;" class="text-center">{{ $dyeingBatch->fabricComposition->construction }}</p>
                                    </div>

                                    <div class="col-md-2">
                                        <p style="margin-top: 7%;">Finished/G.S.M :</p>
                                    </div>
                                    <div class="col-md-4" style="border-bottom: 1px dashed;margin-left: -5%; width: 39%;">
                                        <p style="margin-bottom: 5.5%;; line-height: 1px;" class="text-center">{{ $dyeingBatch->gsm }}</p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-2" style="margin-right: -3%;">
                                        <p style="margin-top: 7%;">Batch No :</p>
                                    </div>
                                    <div class="col-md-10" style="border-bottom: 1px dashed;margin-left: -6%;width: 90.2%;">
                                        <p style="margin-bottom: 2.3%; line-height: 1px;" class="text-center">{{ $dyeingBatch->batch_no }}</p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-1" style="margin-right: -3%;">
                                        <p style="margin-top: 17%;">L.T :</p>
                                    </div>
                                    <div class="col-md-5" style="border-bottom: 1px dashed; margin-left: -1.5%; width: 43%;">
                                        <p style="margin-bottom: 5.3%;; line-height: 1px;" class="text-center"></p>
                                    </div>

                                    <div class="col-md-1">
                                        <p style="margin-top: 17%;">UL.Time :</p>
                                    </div>
                                    <div class="col-md-5" style="border-bottom: 1px dashed;margin-left: -1.2%;width: 44%;">
                                        <p style="margin-bottom: 5.3%;; line-height: 1px;" class="text-center"></p>
                                    </div>
                                </div>

                                <table class="reportTable" style="width: 100%;margin-top: 3% ;">
                                    <thead>

                                    <tr>
                                        <th rowspan="2">SI</th>
                                        <th rowspan="2">Knitting M/C</th>
                                        <th rowspan="2">Grey Dia</th>
                                        <th rowspan="2">Roll Qty</th>
                                        <th rowspan="2">Grey Wt.(KG)</th>
                                        <th rowspan="2">Finish Wt.(KG)</th>
                                        <th colspan="2">Finished</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th>DIA</th>
                                        <th>GSM</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                           
                                            @foreach ($dyeingBatch->batchDetails as $details)
                                            @php
                                            $batchDetailsMachines = collect($details->subDyeingBatch->machineAllocations)
                                                                    ->pluck('machine.name')
                                                                    ->implode(',');
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $batchDetailsMachines }}</td>
                                                <td></td>
                                                <td>{{ $details->batch_roll }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ $details->gsm }}</td>
                                                <td>{{ $details->remarks }}</td>
                                            </tr>
                                            @endforeach
                                    
                                    </tbody>
                                </table>

                                <div class="row" style="margin-top: 3%;">
                                    <div class="col-md-3" style="margin-right: -3%;">
                                        <p style="margin-top: 5%;">(A)TOTAL GREY WT :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed; margin-left: -8%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                    <div class="col-md-3" style="margin-left: -1.2%;">
                                        <p style="margin-top: 5%;">(B)F/FAB :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed; margin-left: -18%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 1%;">
                                    <div class="col-md-2" style="margin-right: -3%;">
                                        <p style="margin-top: 7%;">(C)WASTAGE :</p>
                                    </div>
                                    <div class="col-md-4" style="border-bottom: 1px dashed; margin-left: -4%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                    <div class="col-md-3" style="margin-left: -1.2%;">
                                        <p style="margin-top: 5%;">(D)TOTAL(B+C) :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed; margin-left: -14%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                </div>


                                <div class="row" style="margin-top: 1%;">
                                    <div class="col-md-3" style="margin-right: -3%">
                                        <p style="margin-top: 5%;">(E)PROCESS LOSS:(A-D)</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed; margin-left: -6%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                    <div class="col-md-3" style="margin-left: -1.2%;">
                                        <p style="margin-top: 5%;">KG=</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed; margin-left: -21%; width: 40%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                </div>


                                <div class="row" style="margin-top: 5%">
                                    <div class="col-md-1" style="margin-left: -7%"></div>
                                   <div class="col-md-2" style="border-top: 1px dashed; width: 10%;">
                                    <p class="text-left">Sup.Batch</p>
                                   </div>
                                   <div class="col-md-1" style="margin-left: 8%;"></div>
                                   <div class="col-md-2" style="border-top: 1px dashed; width: 13%;">
                                    <p class="text-center">Operator(Dyeing)</p>
                                   </div>

                                   <div class="col-md-1" style="margin-left: 8%;"></div>
                                   <div class="col-md-2"  style="border-top: 1px dashed; width: 13%;">
                                    <p class="text-center">Sup.Finishing</p>
                                   </div>

                                   <div class="col-md-1" style="margin-left: 7%;"></div>
                                   <div class="col-md-2"  style="border-top: 1px dashed; width: 12.5%;">
                                    <p class="text-right">Q.C.(Incharge)</p>
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
