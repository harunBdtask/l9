@extends('basic-finance::layout')
@section("title","Procurement Requisition")
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                
                <div class="row">
                    <form>
                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right">
                                <table>
                                    <tr>
                                        <td><a href="{{ url('procurement/requisitions/view/6?type=pdf') }}" style="margin-right: 1px;" type="button"
                                               class="form-control btn pdf pull-right"><i class="fa fa-file-pdf-o"></i></a>
                                        </td>
                                        <td>
                                           
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>

                <h2>Procurement Requisitions</h2>
                
            </div>
            

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        {{--                        <div class="header-section" style="padding-bottom: 0px;">--}}
                        {{--                            <div class="pull-right" style="margin-bottom: -5%;">--}}
                        {{--                                <a id="order_pdf" data-value="po_details" class="btn"--}}
                        {{--                                   href="{{ url('subcontract/dyeing-process/recipe-entry/pdf/'.$dyeingRecipe->id) }}"><i--}}
                        {{--                                        class="fa fa-file-pdf-o"></i></a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Requisition Details</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <div class="row p-x-1">
                            <div class="col-md-12">

                                @includeIf('procurement::requisitions.view-body')

                                <div class="row">
                                    <div class="col-md-4">
                                        <table style="border: 1px solid black;width: 48%; margin-top:80px">
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
                                            <table style="border: 1px solid black;width: 48%; margin-top:80px">
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
                                        <table style="border: 1px solid black;width: 99%; margin-top:80px">
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


                            </div>
                        </div>


                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection
