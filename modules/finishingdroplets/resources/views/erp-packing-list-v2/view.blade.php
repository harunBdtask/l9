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
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="header-section" style="padding-bottom: 0;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a class="btn"
                                   href="{{ url('erp-packing-list-v2/pdf/'.$uid) }}"><i
                                        class="fa fa-file-pdf-o"></i></a>
                            </div>
                            <div class="pull-right" style="margin-right: 25px;margin-bottom: -5%;">
                                <a class="btn"
                                   href="{{ url('erp-packing-list-v2/excel/'.$uid) }}"><i
                                        class="fa fa-file-excel-o"></i></a>
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

                        @include('finishingdroplets::erp-packing-list-v2.view-body')

                    </div>
                </div>


            </div>
        </div>
    </div>
    <style>
    </style>
@endsection
@section('scripts')
    <script>

    </script>
@endsection
