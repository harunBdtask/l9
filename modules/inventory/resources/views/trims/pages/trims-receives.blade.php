@extends('skeleton::layout')
@section('title','Trims Receives List')

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
        .error + .select2-container .select2-selection--single {
            border: 1px solid red;
        }

    </style>
@endpush

@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header text-center" >
                <h2 style="font-weight: 400; ">Trims Receive</h2>
            </div>

            <div class="box-body">
                @include('inventory::partials.flash')

                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/inventory/trims-receives/create') }}"
                            class="btn btn-sm btn-info"
                        >
                            <i class="fa fa-plus"></i> New Trims Receive
                        </a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/inventory/trims-receives') }}" method="GET">
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
                                <th>Sl No.</th>
                                <th>Receive Basis</th>
                                <th>Factory Name</th>
                                <th>Receive Date</th>
                                <th>Challan No</th>
                                <th>Buyer</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($trimReceives))
                                @foreach($trimReceives as $key => $trimReceive)
                                    <tr>
                                        <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $trimReceive->receive_basic }}</td>
                                        <td>{{ $trimReceive->factory->factory_name }}</td>
                                        <td>{{ $trimReceive->receive_date }}</td>
                                        <td>{{ $trimReceive->challan_no }}</td>
                                        <td>{{ $trimReceive->buyer->name  }}</td>
                                        <td>
                                            <a href="{{ url('/inventory/trims-receives/' . $trimReceive->id) . '/view'}}"
                                               class="btn btn-xs btn-success">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ url('/inventory/trims-receives/' . $trimReceive->id) . '/edit'}}"
                                               class="btn btn-xs btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button style="margin-left: 2px;" type="button"
                                                    class="btn btn-xs btn-danger show-modal"
                                                    title="Delete Realization"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('/inventory-api/v1/trims-receives/'.$trimReceive->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
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
                <div class="row">
                    <div class="col-md-12 text-center">
                        @if(count($trimReceives))
                            {{ $trimReceives->render() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush

