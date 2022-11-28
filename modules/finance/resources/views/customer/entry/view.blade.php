@extends('skeleton::layout')
@section('title', 'Customer Invoice')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-body">
                <div class="row text-center">
                    <a class="btn pull-right" href="{{ url('finance/customer-bill-entry/'.$billEntry->id.'/view?type=pdf')}}" title="PDF"><i class="fa fa-file-pdf-o"></i></a>
                    <div class="col-md-6 col-md-offset-3" style="display: flex; flex-direction: column;">
                        <span style="font-size: 16px; font-weight: bold">{{ factoryName() }}</span>
                        <span>{{ factoryAddress() }}</span>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <u style="font-size: 15px; font-weight: bold;">INVOICE</u>
                </div>

                @includeIf('finance::customer.entry.view-body')
            </div>
        </div>
    </div>
@endsection
