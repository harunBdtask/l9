@extends('commercial::layout')
@section('title','Sales Contract Entry List')

@push('style')
    <style>
        .form-control .form-control-sm {
            border: 1px solid #909ac8 !important;
            border-radius: 10px 0 0 0;
        }

        input, select {
            min-height: 10px !important;
        }

        .form-control .form-control-sm:focus {
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
                <h2 style="font-weight: 400; ">Sales Contract List</h2>
            </div>

            <div class="box-body">
                @include('commercial::partials.flash')

                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/commercial/sales-contract/entry') }}"
                            class="btn btn-sm btn-info"
                        >
                            <i class="fa fa-plus"></i> Sales Contract
                        </a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/commercial/sales-contract-search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search" value="{{ $value ?? '' }}"
                                       placeholder="Search">
                                <span class="input-group-btn">
                                  <button class="btn btn-sm btn-info" type="submit"> Search</button>
                                </span>
                            </div>
                        </form>
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
                                <th>Contract Value</th>
                                <th>Contract Number</th>
                                <th>Contract Date</th>
                                <th>Last Shipment Date</th>
                                <th>Lien Bank</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($contracts))
                                @foreach($contracts as $key=>$contract)
                                    <tr>
                                        <td>{{ $contract->factory->factory_name }}</td>
                                        <td>{{ $contract->buyers }}</td>
                                        <td>{{ $contract->internal_file_no }}</td>
                                        <td>{{ $contract->year }}</td>
                                        <td>{{ $contract->contract_value }}</td>
                                        <td>{{ $contract->contract_number }}</td>
                                        <td>{{ $contract->contract_date }}</td>
                                        <td>{{ $contract->last_shipment_date  }}</td>
                                        <td>{{ $contract->lienBank->name??null  }}</td>
                                        <td>
                                            <a href="{{ url('/commercial/sales-contract/'.$contract->id) }}" title="View"
                                               class="btn btn-xs btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a target="_blank" class="btn btn-xs btn-success" title="Pad-preview"
                                               href="{{ url('/commercial/sales-contract/'. $contract->id . '/pad-preview') }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ url('/commercial/sales-contract/'.$contract->id.'/edit') }}" title="Edit"
                                               class="btn btn-xs btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Budget"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/commercial/sales-contract/'.$contract->id) }}">
                                                    <i class="fa fa-trash"></i>
                                            </button>

                                            <?php /*
                                           <a class="btn btn-xs btn-danger" href="{{ url('/commercial/sales-contract/'.$contract->id) }}" title="Delete"
                                               onclick="event.preventDefault();
                                                   document.getElementById('delete-form-{{ $contract->id }}').submit();"
                                            >
                                                <i class="fa fa-trash"></i>

                                            </a>
                                            <form id="delete-form-{{ $contract->id }}" action="{{ url('/commercial/sales-contract/'.$contract->id) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                            </form> */ ?>
{{--                                            <li class="nav-item inline dropdown">--}}
{{--                                                <a class="nav-link" data-toggle="dropdown" aria-expanded="false">--}}
{{--                                                    <i class="material-icons md-18"></i></a>--}}
{{--                                                <div class="dropdown-menu dropdown-menu-scale pull-right">--}}
{{--                                                    <a class="dropdown-item"--}}
{{--                                                       href="{{ url('/commercial/sales-contract/'.$contract->id.'/edit') }}">Edit</a>--}}
{{--                                                    <a class="dropdown-item" href="{{ url('/commercial/sales-contract/'.$contract->id.'/amendment') }}">Amendment</a>--}}
{{--                                                    <a class="dropdown-item" href="{{ url('/commercial/sales-contract/'.$contract->id) }}"--}}
{{--                                                       onclick="event.preventDefault();--}}
{{--                                                           document.getElementById('delete-form-{{ $contract->id }}').submit();"--}}
{{--                                                    >--}}
{{--                                                        Delete--}}
{{--                                                    </a>--}}
{{--                                                    <form id="delete-form-{{ $contract->id }}" action="{{ url('/commercial/sales-contract/'.$contract->id) }}"--}}
{{--                                                          method="POST" style="display: none;">--}}
{{--                                                        @csrf--}}
{{--                                                    </form>--}}
{{--                                                </div>--}}
{{--                                            </li>--}}
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
                        {{ $contracts->appends(request()->query())->links()  }}
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-md-12 text-center">--}}
{{--                        @if(count($contracts))--}}
{{--                            {{ $contracts->render() }}--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush

