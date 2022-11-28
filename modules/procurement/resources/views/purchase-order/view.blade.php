@extends('basic-finance::layout')
@section("title","Procurement Purchase Order")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Procurement Purchase Order</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Purchase Order Details</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <div class="row p-x-1">
                            <div class="col-md-12">
                                @includeIf('procurement::purchase-order.view-body')
                            </div>
                        </div>


                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection
