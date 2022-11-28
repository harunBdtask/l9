@extends('skeleton::layout')
@section('title','Knit Card')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-body">
                <div class="row text-center">
                    <a class="btn pull-right" href="{{ url('knitting/knit-card/'.$data->id.'/view?type=pdf') }}" title="PDF"><i class="fa fa-file-pdf-o"></i></a>
                    <div class="col-md-6 col-md-offset-3" style="display: flex; flex-direction: column;">
                        <span style="font-size: 16px; font-weight: bold">{{ factoryName() }}</span>
                        <span>{{ factoryAddress() }}</span>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <span style="font-size: 25px; font-weight: bold;">Knit Card</u><br>
                    <span style="width: 9%;">&nbsp;<?php echo DNS1D::getBarcodeSVG(($data->knit_card_no), "C128A", 2, 24, '', false); ?> &nbsp;</span>
                    <p style="font-size: 14px; font-weight: normal;">{{ $data->knit_card_no ?? '' }}</p>
                </div>

                @includeIf('knitting::knit-card.view-body')
            </div>
        </div>
    </div>
@endsection