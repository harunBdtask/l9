@extends('finishingdroplets::layout')
@section("title","Packing List View")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Packing List View</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="header-section" style="padding-bottom: 0;">
                            {{--                            <div class="pull-right" style="margin-bottom: -5%;">--}}
                            {{--                                <a class="btn"--}}
                            {{--                                   href="{{ url('erp-packing-list-v3/pdf/'.$garmentPackingProduction->id) }}">--}}
                            {{--                                    <em class="fa fa-file-pdf-o"></em></a>--}}
                            {{--                            </div>--}}
                            <div class="pull-right" style="margin-right: 25px;margin-bottom: -5%;">
                                <a class="btn"
                                   href="{{ url('erp-packing-list-v3/excel/'.$garmentPackingProduction->id) }}">
                                    <em class="fa fa-file-excel-o"></em></a>
                            </div>
                        </div>

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Packing List</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>

                        @include('finishingdroplets::finishing-packing-list-v3.view.view-body')

                    </div>
                </div>


            </div>
        </div>
    </div>
    <style>
    </style>
@endsection
