@extends('skeleton::layout')
@section('title','Knit Card')
@section('styles')
    <style>
        .dashedBorder {
            border-bottom: 1px dashed #b7b7b7;
            width: 100%;
            margin-top: 5px;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-body">
                <div class="row text-center">
                    <a class="btn pull-right" href="{{ url('knitting/knit-card/'.$data->id.'/view-2?type=pdf') }}" title="PDF"><i class="fa fa-file-pdf-o"></i></a>
                    <div class="col-md-6 col-md-offset-3" style="display: flex; flex-direction: column;">
                        <span style="font-size: 18px; font-weight: bold">{{ factoryName() }}</span>
                        <span>{{ factoryAddress() }}</span>
                    </div>
                </div>
                <table class="table" style="margin-top: 10px;">
                    <tr>

                        <td style="text-align: center; width: 60%;">
                            <span style="font-size: 18px; font-weight: bold;">KNIT CARD</u><br>
                            <span style="width: 9%;">&nbsp;<?php echo DNS1D::getBarcodeSVG(($data->knit_card_no), "C128A", 2, 24, '', false); ?> &nbsp;</span><br>
                            <span style="font-size: 14px;">{{ $data->knit_card_no }}</span>
                        </td>

                    </tr>
                </table>

                @includeIf('knitting::knit-card.view-2-body')
            </div>
        </div>
    </div>
@endsection
