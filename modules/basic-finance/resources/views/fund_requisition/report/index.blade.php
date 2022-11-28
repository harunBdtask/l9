@extends('basic-finance::layout')
@section('title','Report List')

@push('style')
    <style>
        .form-control {
            border: 1px solid #909ac8 !important;
            border-radius: 10px 0 0 0;
        }

        input, select {
            min-height: 10px !important;
        }

        .form-control:focus {
            border: 2px solid #909ac8 !important;
        }

        .req {
            font-size: 1rem;
        }

        .mainForm td, .mainForm th {
            border: none !important;
            padding: .3rem !important;
        }

        li.parsley-required {
            color: red;
            list-style: none;
            text-align: left;
        }

        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            border-color: #843534;
            box-shadow: none;
        }


        input.parsley-error:focus,
        select.parsley-error:focus,
        textarea.parsley-error:focus {
            border-color: #843534;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 6px #ce8483
        }

        .remove-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #843534;
            color: whitesmoke;
        }

        .close-po {
            border: none;
            display: block;
            width: 100%;
            background-color: #6cc788;
            color: whitesmoke;
        }

        /* select2 */
        .select2-container .select2-selection--single {
            height: 35px !important;
            border-radius: 10px 0 0 0 !important;
            line-height: 1.5rem !important;
            border: 1px solid #909ac8 !important;
        }


        .reportTable .select2-container .select2-selection--single {
            border: 1px solid #e7e7e7;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            width: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px !important;
        }

        .error + .select2-container .select2-selection--single {
            border: 1px solid red;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 35px !important;
            border-radius: 0px;
            width: 100%;
        }

    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header text-center" style="border-bottom: 1px solid #D1C4E9;">
                <h2 style="font-weight: 400; ">Fund Requisition Report List</h2>
            </div>

            <div class="box-body">
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead class="btn-info">
                            <tr>
                                <th>SL</th>
                                <th>RQ No</th>
                                <th>Req Date</th>
                                <th>Req Qty</th>
                                <th>Req Amount(Tk)</th>
                                <th>Apprv Qty</th>
                                <th>Apprv Amount(Tk)</th>
                                <th>Balanced Qty</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requisitions as $index=>$requisition)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$requisition->requisition_no}}</td>
                                    <td>{{\Carbon\Carbon::parse($requisition->requisition_date)->toFormattedDateString()}}</td>
                                    <td>{{$requisition->details->sum('req_qty')}}</td>
                                    <td>{{$requisition->details->sum('amount')}}</td>
                                    <td>{{$requisition->acApproved->sum('approved_qty') ?? 0}}</td>
                                    <td>{{$requisition->acApproved->sum('amount') ?? 0}}</td>
                                    <td>{{$requisition->details->sum('req_qty') - ($requisition->acApproved->sum('approved_qty') ?? 0)}}</td>
                                    <td>
                                        <a class="btn btn-xs btn-primary"
                                           href="/basic-finance/fund-requisition/reports/{{$requisition->id}}"><i
                                                class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        @if(count($requisitions))
                            {{ $requisitions->render() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush

