@extends('commercial::layout')
@section('title','Export LC Amendment List')

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

        .error + .select2-container .select2-selection--single {
            border: 1px solid red;
        }
    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header text-center">
                <h2 style="font-weight: 400; ">Export LC Amendments List</h2>
            </div>

            <div class="box-body">
                @include('commercial::partials.flash')

                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/commercial/export-lc-amendments/create') }}"
                            class="btn btn-sm btn-info"
                        >
                            <i class="fa fa-plus"></i> Export LC Amendment
                        </a>
                    </div>

                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Beneficiary</th>
                                <th>Buyer</th>
                                <th>Internal File No</th>
                                <th>Year</th>
                                <th>LC Value</th>
                                <th>LC Number</th>
                                <th>LC Date</th>
                                <th>Last Shipment Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($amendments))
                                @foreach($amendments as $key => $amendment)
                                    <tr>
                                        <td>{{ $amendment->factory->factory_name }}</td>
                                        <td>{{ collect($amendment->ExportLc->buyer_names)->implode('name',',') }}</td>
                                        <td>{{ $amendment->internal_file_no }}</td>
                                        <td>{{ $amendment->year }}</td>
                                        <td>{{ $amendment->lc_value }}</td>
                                        <td>{{ $amendment->lc_number }}</td>
                                        <td>{{ $amendment->lc_date }}</td>
                                        <td>{{ $amendment->last_shipment_date  }}</td>
                                        <td>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" align="text-center">No Data Found</td>
                                </tr>

                            @endif

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

