@extends('skeleton::layout')
@section("title","Container Summaries")
@section('content')
    <style>
        .po-card {
            position: relative;
            margin-bottom: 0.75rem;
            background-color: #fff;
        }

        .row-v {
            height: 40px;
            width: 220px;
            padding: 9px 1%;
            border: 1px solid #08f3e0;
            border-radius: 0.25rem;
            text-align: center;
            margin: 5px;
        }

        .po-row {
            height: 70px;
            width: 45%;
            padding: 9px 1%;
            text-align: center;
            margin: 5px;
            border-radius: 0.25rem;
            border-color: #08f3e0;
        }

        .container-status {
            background: #23d096;
            height: 18px;
            border-radius: 5px 0 0 5px;
        }

        .container-progress {
            border: 1px solid black;
            height: 20px;
            width: 21%;
            border-radius: 5px;
            background: #fddcdb;
        }

        .vl {
            border-left: 6px solid green;
            height: 500px;
        }

        .wrapper {
            display: flex;
            flex-wrap: wrap;
            /*justify-content: center;*/
            align-items: center;
            height: 38vh;
            /*background-color: rgb(253, 252, 252);*/
        }

        .progressbar {
            height: 50px;
            width: 250px;
            position: relative;
            transform-style: preserve-3d;
            transform: rotateX(-20deg) rotateY(-40deg);
        }

        .side {
            width: 100%;
            height: 100%;
            background-color: rgb(183 180 180 / 30%);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 99;
        }

        .side__bottom {
            transform: rotateX(90deg);
            transform-origin: bottom;
            box-shadow: 10px 5px 50px 5px rgba(0, 0, 0, .25);
        }

        .side__top {
            transform: rotateX(-90deg);
            transform-origin: top;
        }

        .side__back {
            transform: translateZ(-50px);
        }

        .side__left {
            width: 50px;
            transform: rotateY(90deg);
            transform-origin: left;
            background-color: rgba(0, 139, 139, .5);
        }

        .side__fill {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background-color: rgba(0, 139, 139, .5);
            transition: .3s all linear;
        }


        .progressbar__text {
            display: inline-block;
            position: relative;
            top: -60px;
            left: 50px;
            font-size: 11px;
            padding: 5px;
            background-color: rgba(0, 139, 139, 1);
            cursor: pointer;
            margin-right: 17px;
            font-family: Arial;
            color: white;
            border-radius: 5px;
            box-shadow: 1px -2px 0px 0px rgba(56, 121, 121, 1),
            2px -2px 0px 0px rgba(56, 121, 121, 1),
            3px -2px 0px 0px rgba(56, 121, 121, 1);
        }

        .progressbar__text:last-of-type {
            margin-right: 0;
        }

        .progressbar__checker {
            display: none;
        }

        #zero:checked ~ div > .side__fill {
            width: 0%;
        }

        #twenty-five:checked ~ div > .side__fill {
            width: 25%;
        }

        #fifty:checked ~ div > .side__fill {
            width: 50%;
        }

        #seventy-five:checked ~ div > .side__fill {
            width: 75%;
        }

        #hundred:checked ~ div > .side__fill {
            width: 100%;
        }

        .progressbar__checker:checked + .progressbar__text {
            background-color: rgba(0, 139, 139, 1);
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Container Summaries</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 m-b">
                        <a href="{{ url('/planning/container-summaries/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-6">
                                <form action="{{ url('/planning/container-summaries') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="search"
                                               name="search"
                                               value="{{ $search ?? '' }}" placeholder="Search">
                                        <span class="input-group-btn">
                                                <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div
                            class="row wrap align-center sortable-list list-group"
                            style="background: #fff;padding: 0 12px;"
                        >
                            @foreach ($containerSummaries as $summaries)
                                @php
                                    $totalUseCBM = collect($summaries->po_list)->sum('cbm');
                                    $usedPercentage = ($totalUseCBM * 100) / $summaries->containerProfile->cbm;
                                    $remainingSpace = $summaries->containerProfile->cbm - $totalUseCBM;
                                @endphp
                                <div class="sortable list-group-item"
                                     style="height: fit-content;width: 100%;border: 1px dotted gray;margin-bottom: 10px;min-height: 100px;">
                                    <div>
                                        <span><b>Container Name : {{ $summaries->containerProfile->container_no }}</b></span>,
                                        <span><b>Container Space : {{ $summaries->containerProfile->cbm }} CBM</b></span>,
                                        <span><b>Remaining Space : {{ number_format($remainingSpace, 2, '.', '') }} CBM</b></span>&nbsp;
                                        <span style="float: right;">
                                        <b>EX Factory Date : {{ $summaries->ex_factory_date }}</b></span>
                                        @if($summaries->ex_factory_date >= date('Y-m-d'))
                                            <a class="btn btn-xs btn-info" type="button"
                                               href="{{ url('/planning/container-summaries/create?id='. $summaries->id) }}">
                                                <em class="fa fa-pencil"></em>
                                            </a>
                                        @endif
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="wrapper">
                                                    <div class="progressbar">

                                                        <input class="progressbar__checker" type="radio" id="zero"
                                                               name="progress-value">
                                                        <label class="progressbar__text" for="zero">0%</label>
                                                        <input class="progressbar__checker" type="radio"
                                                               id="twenty-five" name="progress-value">
                                                        <label class="progressbar__text" for="twenty-five">25%</label>
                                                        <input class="progressbar__checker" type="radio" id="fifty"
                                                               name="progress-value" checked>
                                                        <label class="progressbar__text" for="fifty">50%</label>
                                                        <input class="progressbar__checker" type="radio"
                                                               id="seventy-five" name="progress-value">
                                                        <label class="progressbar__text" for="seventy-five">75%</label>
                                                        <input class="progressbar__checker" type="radio" id="hundred"
                                                               name="progress-value">
                                                        <label class="progressbar__text" for="hundred">100%</label>

                                                        <div class="side side__front">
                                                            <div class="side__fill"
                                                                 style="width: {{ $usedPercentage }}%"></div>
                                                        </div>
                                                        <div class="side side__back">
                                                            <div class="side__fill"
                                                                 style="width: {{ $usedPercentage }}%"></div>
                                                        </div>
                                                        <div class="side side__top">
                                                            <div class="side__fill"
                                                                 style="width: {{ $usedPercentage }}%"></div>
                                                        </div>
                                                        <div class="side side__bottom">
                                                            <div class="side__fill"
                                                                 style="width: {{ $usedPercentage }}%"></div>
                                                        </div>
                                                        <div class="side side__left"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row wrap justify-space-around">
                                                    @foreach ($summaries->po_list as $po)
                                                        <div class="row-v col-md-2">
                                                            <div class="po-card"> {{$po['title']}} -
                                                                ({{number_format($po['cbm'], 2, '.', '')}})
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center">
                            {{ $containerSummaries->appends(request()->except('page'))->links() }}
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
    <script></script>
@endsection
