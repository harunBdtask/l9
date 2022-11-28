@extends('commercial::layout')
@section('title','Sales Contract Amendment List')

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
            <div class="box-header text-center" >
                <h2 style="font-weight: 400; ">Sales Contract Amendments List</h2>
            </div>

            <div class="box-body">
                @include('commercial::partials.flash')

                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/commercial/sales-contract-amendments/create') }}"
                            class="btn btn-sm btn-info"
                        >
                            <i class="fa fa-plus"></i> Sales Contract Amendment
                        </a>
                    </div>
{{--                    <div class="col-sm-4 col-sm-offset-2">--}}
{{--                        <form action="{{ url('/commercial/sales-contract-search') }}" method="GET">--}}
{{--                            <div class="input-group">--}}
{{--                                <input type="text" class="form-control form-control-sm" name="search" value="{{ $value ?? '' }}"--}}
{{--                                       placeholder="Search">--}}
{{--                                <span class="input-group-btn">--}}
{{--                                  <button class="btn" type="submit"> Search</button>--}}
{{--                                </span>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
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
                                <th>Amend. Value</th>
                                <th>Contract Value</th>
                                <th>Contract Number</th>
                                <th>Contract Date</th>
                                <th>Last Shipment Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($amendments))
                                @foreach($amendments as $key => $amendment)
                                    <tr>
                                        <td>{{ $amendment->factory->factory_name }}</td>
                                        <td>{{ collect($amendment->salesContract->buyer_names)->implode('name',',') }}</td>
                                        <td>{{ $amendment->internal_file_no }}</td>
                                        <td>{{ $amendment->year }}</td>
                                        <td>{{ $amendment->amendment_value }}</td>
                                        <td>{{ $amendment->contract_value }}</td>
                                        <td>{{ $amendment->contract_number }}</td>
                                        <td>{{ $amendment->contract_date }}</td>
                                        <td>{{ $amendment->last_shipment_date  }}</td>
                                        <td>
                                            @if(Session::has('permission_of_sales_contract_amendment_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button style="margin-top: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Amendment"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/commercial/sales-contract-amendments/'.$amendment->id) }}">
                                                    <i class="fa fa-trash"></i> 
                                            </button>
                                            @endif

{{--                                            <ul>--}}
{{--                                                <li class="nav-item inline dropdown">--}}
{{--                                                    <a class="nav-link" data-toggle="dropdown" aria-expanded="false">--}}
{{--                                                        <i class="material-icons md-18"></i></a>--}}
{{--                                                    <div class="dropdown-menu dropdown-menu-scale pull-right">--}}
{{--                                                        <a class="dropdown-item"--}}
{{--                                                           href="{{ url('/commercial/sales-contract/'.$amendment->id.'/edit') }}">Edit</a>--}}
{{--                                                        <a class="dropdown-item" href="{{ url('/commercial/sales-contract/'.$amendment->id.'/amendment') }}">Amendment</a>--}}
{{--                                                        <a class="dropdown-item" href="{{ url('/commercial/sales-contract/'.$amendment->id) }}"--}}
{{--                                                           onclick="event.preventDefault();--}}
{{--                                                               document.getElementById('delete-form-{{ $amendment->id }}').submit();"--}}
{{--                                                        >--}}
{{--                                                            delete--}}
{{--                                                        </a>--}}
{{--                                                        <form id="delete-form-{{ $amendment->id }}" action="{{ url('/commercial/sales-contract/'.$amendment->id) }}"--}}
{{--                                                              method="POST" style="display: none;">--}}
{{--                                                            @csrf--}}
{{--                                                        </form>--}}
{{--                                                    </div>--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}
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

