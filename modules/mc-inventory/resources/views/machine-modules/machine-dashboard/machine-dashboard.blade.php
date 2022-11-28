@extends('skeleton::layout')
@section("title","Dashboard")
@push('style')
    <style>
        .tile {
            cursor: pointer;
        }

        .dbl-tile,
        .cursor-pointer {
            cursor: pointer;
        }

        @media (max-width: 576px) {
            .footerSocialIcons {
                padding-top: 3.5rem;
            }

            .greetings {
                font-size: 24px !important;
            }
        }

        .col-xs-15,
        .col-sm-15,
        .col-md-15,
        .col-lg-15 {
            position: relative;
            min-height: 1px;
            padding-right: 10px;
            padding-left: 10px;
        }

        .col-xs-15 {
            width: 20%;
            float: left;
        }

        #machine {
            height: 115px;
        }

        @media (min-width: 768px) {
            .col-sm-15 {
                width: 20%;
                float: left;
            }
        }

        @media (min-width: 992px) {
            .col-md-15 {
                width: 20%;
                float: left;
            }
        }

        @media (min-width: 1200px) {
            .col-lg-15 {
                width: 20%;
                float: left;
            }
        }
    </style>
@endpush
@php
    $user_roles = ['super-admin', 'admin'];
    $user_list_permission = in_array(getRole(), $user_roles);
    $sewing_achievement_color = '#0cc2aa';
    $sewing_target_color = '#fcc100';
@endphp
@section('content')
    <div class="p-a white lt box-shadow">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="m-b-0 _300 greetings"
                    style="font-style: italic !important;font-size: 30px;font-family: 'FontAwesome' !important;">
                    {{ Carbon\Carbon::greetings() }}{{ auth()->user()->full_name }}!</h4>
                {{--                <small class="text-muted">Thank you for logging in. We hope to have the pleasure of doing business with--}}
                {{--                    you for many years to come.</small>--}}
            </div>
            <div class="col-sm-6 text-sm-right">
                <div class="m-y-sm">
                    <div class="btn-group dropdown">
                        {{--                        <button class="btn white btn-sm ">Help</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="padding">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex flex-wrap">

                    <div class="col-sm-3">
                        <div class="tile box cursor-pointer p-a light-blue"  style="height: 115px !important" data-toggle="tooltip"
                             data-placement="top"
                             title="Buyer List" data-url="" id="buyer_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-user text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">Total No Of Machine In {{ sessionFactoryName() }}</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$machineInFactory}}</a></h4>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="box p-a cursor-pointer warn tile"  style="height: 115px !important"  data-toggle="tooltip"
                             data-placement="top"
                             title="Order List" data-url="" id="order_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-truck text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">TOTAL RUNNING MACHINE</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$totalRunningMachine}}</a></h4>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="box p-a cursor-pointer primary tile"
                             data-toggle="tooltip"
                             style="height: 115px !important" 
                             data-placement="top" title="User List"
                             data-url="" id="user_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-users text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">TOTAL IDLE MACHINE</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$totalIdleMachine}}</a></h4>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="box p-a cursor-pointer accent" style="height: 115px !important"  data-toggle="tooltip"
                             data-placement="top"
                             title="Price Quotation List" data-url=""
                             id="price_quotation_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-newspaper-o text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">TOTAL MACHINE TAKEN AS LOAN</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$totalMachineTakenAsLoan}}</a></h4>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="box p-a cursor-pointer accent" data-toggle="tooltip"
                             data-placement="top"
                             style="height: 115px !important"
                             title="Price Quotation List" data-url=""
                             id="price_quotation_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-newspaper-o text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">MACHINE SERVICE - PLANNEDN</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$servicePlanned}}</a></h4>
                            </div>
                        </div>
                    </div>



                    <div class="col-sm-3" >
                        <div class="box p-a cursor-pointer accent" data-toggle="tooltip"
                             data-placement="top"
                             style="height: 115px !important"
                             title="Price Quotation List" data-url=""
                             id="price_quotation_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-newspaper-o text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">MACHINE SERVICE- ACTUAL</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$actualMachineService}}</a></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3" >
                        <div class="box p-a cursor-pointer accent" data-toggle="tooltip"
                             data-placement="top"
                             style="height: 115px !important"
                             title="Price Quotation List" data-url=""
                             id="price_quotation_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-newspaper-o text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">MACHINE SERVICE - DUE</div>
                                <h4 class="m-a-0 text-md _600"><a>{{ $servicePlanned - $actualMachineService }}</a></h4>
                            </div>
                        </div>
                    </div>

                    @foreach($totalMachineInLocations as $machineLocation)
                    <div class="col-sm-3">
                        <div class="tile box cursor-pointer p-a bg-info" data-toggle="tooltip"
                             data-placement="top"
                             style="height: 115px !important"
                             title="Budget List" data-url="" id="budget_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-user text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">TOTAL {{ sessionFactoryName() }} MACHINE IN {{ $machineLocation->location_name }}</div>
                                <h4 class="m-a-0 text-md _600"><a>{{ $machineLocation->totalMachineLocation }}</a></h4>
                            </div>
                        </div>
                    </div>
                    @endforeach




                </div>
            </div>


            {{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-md-15">
                        <div class="tile box cursor-pointer p-a light-blue" data-toggle="tooltip"
                             data-placement="top"
                             title="Buyer List" data-url="" id="buyer_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-user text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">Total No Of Machine In {{ sessionFactoryName() }}</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$machineInFactory}}</a></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15">
                        <div class="box p-a cursor-pointer warn tile" data-toggle="tooltip"
                             data-placement="top"
                             title="Order List" data-url="" id="order_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-truck text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">TOTAL RUNNING MACHINE</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$totalRunningMachine}}</a></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15">
                        <div class="box p-a cursor-pointer primary tile"
                             data-toggle="tooltip"
                             data-placement="top" title="User List"
                             data-url="" id="user_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-users text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">TOTAL IDLE MACHINE</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$totalIdleMachine}}</a></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15">
                        <div class="box p-a cursor-pointer accent" data-toggle="tooltip"
                             data-placement="top"
                             title="Price Quotation List" data-url=""
                             id="price_quotation_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-newspaper-o text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">TOTAL MACHINE TAKEN AS LOAN</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$totalMachineTakenAsLoan}}</a></h4>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-15">
                        <div class="box p-a cursor-pointer accent" data-toggle="tooltip"
                             data-placement="top"
                             title="Price Quotation List" data-url=""
                             id="price_quotation_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-newspaper-o text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">MACHINE SERVICE - PLANNEDN</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$servicePlanned}}</a></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-15">
                        <div class="box p-a cursor-pointer accent" data-toggle="tooltip"
                             data-placement="top"
                             title="Price Quotation List" data-url=""
                             id="price_quotation_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-newspaper-o text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">MACHINE SERVICE- ACTUAL</div>
                                <h4 class="m-a-0 text-md _600"><a>{{$actualMachineService}}</a></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-15">
                        <div class="box p-a cursor-pointer accent" data-toggle="tooltip"
                             data-placement="top"
                             title="Price Quotation List" data-url=""
                             id="price_quotation_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-newspaper-o text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">MACHINE SERVICE - DUE</div>
                                <h4 class="m-a-0 text-md _600"><a>{{ $servicePlanned - $actualMachineService }}</a></h4>
                            </div>
                        </div>
                    </div>

                    @foreach($totalMachineInLocations as $machineLocation)
                    <div class="col-md-15">
                        <div class="tile box cursor-pointer p-a bg-info" data-toggle="tooltip"
                             data-placement="top"
                             title="Budget List" data-url="" id="budget_tile">
                            <div class="pull-left m-r">
                                <em class="fa fa-user text-2x text-white m-y-sm"></em>
                            </div>
                            <div class="clear">
                                <div class="text-white">TOTAL {{ sessionFactoryName() }} MACHINE IN {{ $machineLocation->location_name }}</div>
                                <h4 class="m-a-0 text-md _600"><a>{{ $machineLocation->totalMachineLocation }}</a></h4>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div> --}}
        </div>

    </div>
@endsection

