@extends('commercial::layout')
@section('title','BTB/Margin LC Amendment List')

@push('style')
    <style>
        .form-control form-control-sm {
            border: 1px solid #909ac8 !important;
            border-radius: 10px 0 0 0;
        }

        input, select {
            min-height: 10px !important;
        }

        .form-control form-control-sm:focus {
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
        <div class="box" >
            <div class="box-header text-center" >
                <h2 style="font-weight: 400; ">BTB/Margin LC Amendments List</h2>
            </div>

            <div class="box-body">
                @include('commercial::partials.flash')

                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/commercial/btb-lc-amendment/create') }}"
                            class="btn btn-sm btn-info"
                        >
                            <i class="fa fa-plus"></i> BTB/Margin LC Amendment
                        </a>
                    </div>

                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>LC No</th>
                                <th>Amendment No</th>
                                <th>Amendment Date</th>
                                <th>Amendment Value</th>
                                <th>Value changed by</th>
                                <th>Last Ship. Date</th>
                                <th>Pay Term</th>
                                <th>Delivery Mode</th>
                                <th>Inco Term</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($amendments as $key => $amendment)
                                <tr>
                                    <td>{{ $amendment->b2bMargin->lc_number }}</td>
                                    <td>{{ $amendment->amendment_no }}</td>
                                    <td>{{ $amendment->amendment_date }}</td>
                                    <td>{{ $amendment->amendment_value }}</td>
                                    <td>{{ $amendment->value_changed_by }}</td>
                                    <td>{{ $amendment->last_shipment_date }}</td>
                                    <td>{{ $amendment->pay_term }}</td>
                                    <td>{{ $amendment->delivery_mode }}</td>
                                    <td>{{$amendment->inco_term}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" align="text-center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $amendments->appends(request()->query())->links()  }}
                    </div>
                </div>

{{--                <div class="row">--}}
{{--                    <div class="col-md-12 text-center">--}}
{{--                        @if(count($amendments))--}}
{{--                            {{ $amendments->render() }}--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush

