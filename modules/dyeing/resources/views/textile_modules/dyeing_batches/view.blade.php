@extends('dyeing::layout')
@section("title","Batch Details")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Dyeing Batch Card</h2>
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
                                {{--                                <a id="order_pdf" data-value="po_details" class="btn"--}}
                                {{--                                   href="{{ url('subcontract/dyeing-process/batch-entry/pdf/'.$dyeingBatch->id) }}"><i--}}
                                {{--                                        class="fa fa-file-pdf-o"></i></a>--}}
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
                            <div class="col-md-2"><p style="text-decoration-line: underline;
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
                                        <p style="margin-bottom: 8%; line-height: 1px;"
                                           class="text-center">{{ $dyeingBatch->buyer->name }}</p>
                                    </div>

                                    <div class="col-md-1" style="margin-right: -3%;">
                                        <p style="margin-top: 17%;">Order No :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed; width: 29%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;"
                                           class="text-center">{{ collect($dyeingBatch->orders_no)->implode(', ') }}</p>
                                    </div>

                                    <div class="col-md-1" style="margin-right: -2%;">
                                        <p style="margin-top: 17%;">Batch No :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed;width: 26%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;"
                                           class="text-center">{{ $dyeingBatch->batch_no }}</p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-1" style="margin-right: -3%;">
                                        <p style="margin-top: 17%;">Color :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed;">
                                        <p style="margin-bottom: 8%; line-height: 1px;"
                                           class="text-center">{{ $dyeingBatch->color->name }}</p>
                                    </div>

                                    <div class="col-md-1" style="margin-right: -2%;">
                                        <p style="margin-top: 17%;">Fabric Des. :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed;width: 28%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;"
                                           class="text-center">{{ $dyeingBatch->fabric_composition_value }}</p>
                                    </div>

                                    <div class="col-md-1" style="margin-right: -2%;">
                                        <p style="margin-top: 17%;">Fabric Type :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed;width: 28%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;"
                                           class="text-center">{{ $dyeingBatch->fabricType->name }}</p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-1" style="margin-right: -3%;">
                                        <p style="margin-top: 17%;">Machines :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed;width: 28%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;"
                                           class="text-center">{{ $machines }}</p>
                                    </div>
                                    <div class="col-md-1" style="margin-right: -3%;">
                                        <p style="margin-top: 17%;">GSM :</p>
                                    </div>
                                    <div class="col-md-3" style="border-bottom: 1px dashed;">
                                        <p style="margin-bottom: 8%; line-height: 1px;"
                                           class="text-center">{{ $dyeingBatch->gsm }}</p>
                                    </div>

                                    <div class="col-md-1">
                                        <p style="margin-top: 17%;">LD no :</p>
                                    </div>
                                    <div class="col-md-3"
                                         style="border-bottom: 1px dashed;margin-left: -4%; width: 30%;">
                                        <p style="margin-bottom: 8%; line-height: 1px;"
                                           class="text-center">{{ $dyeingBatch->ld_no }}</p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-2" style="margin-right: -3%;">
                                        <p style="margin-top: 7%;">Remarks :</p>
                                    </div>
                                    <div class="col-md-10"
                                         style="border-bottom: 1px dashed;margin-left: -6%;width: 90.2%;">
                                        <p style="margin-bottom: 2.3%; line-height: 1px;"
                                           class="text-center">{{ $dyeingBatch->remarks }}</p>
                                    </div>
                                </div>

                                <table class="reportTable" style="width: 100%;margin-top: 3% ;">
                                    <thead>

                                    <tr>
                                        <th>Sl</th>
                                        <th>Knitting M/C</th>
                                        <th>Gauge</th>
                                        <th>Stich Length</th>
                                        <th>G/Dia</th>
                                        <th>G/GSM</th>
                                        <th>F/Dia</th>
                                        <th>No. Roll</th>
                                        <th>Weight (KG)</th>
                                        <th>Remarks</th>
                                    </tr>

                                    </thead>
                                    <tbody>

                                    @foreach ($dyeingBatch->dyeingBatchDetails as $details)
                                        @php
                                            $batchDetailsMachines = collect($details->dyeingBatch->machineAllocations)
                                                                    ->pluck('machine.name')
                                                                    ->implode(',');
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $batchDetailsMachines }}</td>
                                            <td></td>
                                            <td>{{ $details->stitch_length }}</td>
                                            <td>{{ $details->finish_dia }}</td>
                                            <td>{{ $details->gsm }}</td>
                                            <td>{{ $details->finish_dia }}</td>
                                            <td>{{ $details->batch_roll }}</td>
                                            <td>{{ $details->batch_weight }}</td>
                                            <td>{{ $details->remarks }}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>

                                <div class="row" style="margin-top: 3%;">
                                    <div class="col-md-3" style="margin-right: -3%;">
                                        <p style="margin-top: 5%;">(A)TOTAL GREY WT :</p>
                                    </div>
                                    <div class="col-md-3"
                                         style="border-bottom: 1px dashed; margin-left: -8%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                    <div class="col-md-3" style="margin-left: -1.2%;">
                                        <p style="margin-top: 5%;">(B)F/FAB :</p>
                                    </div>
                                    <div class="col-md-3"
                                         style="border-bottom: 1px dashed; margin-left: -18%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 1%;">
                                    <div class="col-md-2" style="margin-right: -3%;">
                                        <p style="margin-top: 7%;">(C)WASTAGE :</p>
                                    </div>
                                    <div class="col-md-4"
                                         style="border-bottom: 1px dashed; margin-left: -4%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                    <div class="col-md-3" style="margin-left: -1.2%;">
                                        <p style="margin-top: 5%;">(D)TOTAL(B+C) :</p>
                                    </div>
                                    <div class="col-md-3"
                                         style="border-bottom: 1px dashed; margin-left: -14%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                </div>


                                <div class="row" style="margin-top: 1%;">
                                    <div class="col-md-3" style="margin-right: -3%">
                                        <p style="margin-top: 5%;">(E)PROCESS LOSS:(A-D)</p>
                                    </div>
                                    <div class="col-md-3"
                                         style="border-bottom: 1px dashed; margin-left: -6%;width: 39%;">
                                        <p style="margin-bottom: 6%; line-height: 1px;" class="text-center"></p>
                                    </div>
                                    <div class="col-md-3" style="margin-left: -1.2%;">
                                        <p style="margin-top: 5%;">KG=</p>
                                    </div>
                                    <div class="col-md-3"
                                         style="border-bottom: 1px dashed; margin-left: -21%; width: 40%;">
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
                                    <div class="col-md-2" style="border-top: 1px dashed; width: 13%;">
                                        <p class="text-center">Sup.Finishing</p>
                                    </div>

                                    <div class="col-md-1" style="margin-left: 7%;"></div>
                                    <div class="col-md-2" style="border-top: 1px dashed; width: 12.5%;">
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
