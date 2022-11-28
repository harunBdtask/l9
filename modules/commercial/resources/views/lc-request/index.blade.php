@extends('commercial::layout')
@section('title','LC Request List')

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
                <h2 style="font-weight: 400; ">LC Request List</h2>
            </div>

            <div class="box-body">
                @include('commercial::partials.flash')

                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('commercial/lc-request/create') }}" class="btn  btn-primary btn-sm"><i
                                    class="fa fa-plus"></i> New LC Request</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="#" method="GET">
                            <div class="input-group">
                                {{--                                value="{{ $value ?? '' }}"--}}
                                <input type="text" class="form-control" name="search" placeholder="Search">
                                <span class="input-group-btn">
                                  <button class="btn" type="submit"> Search</button>
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
                                <th>Sl</th>
                                <th>System ID</th>
                                <th>Attention</th>
                                <th>Buyer</th>
                                <th>Request Date</th>
                                <th>Open date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($lcRequests))
                                @foreach($lcRequests as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1}}</td>
                                        <td>{{ $item->unique_id }}</td>
                                        <td>{{ $item->attention }}</td>
                                        <td>{{ $item->buyer->name }}</td>
                                        <td>{{ $item->request_date }}</td>
                                        <td>{{ $item->open_date }}</td>
                                        <td>
                                            <a href="{{ url('/commercial/'.$item->id.'/lc-request-view') }}"
                                               class="btn btn-xs btn-success"
                                               target="_blank"
                                            >
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            <a href="{{ url('/commercial/lc-request/'.$item->id.'/edit') }}"
                                               class="btn btn-xs btn-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a class="btn btn-xs btn-danger"
                                               href="{{ url('/commercial/lc-request/'.$item->id) }}"
                                               onclick="event.preventDefault();
                                                       document.getElementById('delete-form-{{ $item->id }}').submit();"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form id="delete-form-{{ $item->id }}"
                                                  action="{{ url('/commercial/lc-request/'.$item->id) }}"
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" align="text-center">No Data Found</td>
                                </tr>

                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $lcRequests->appends(request()->query())->links()  }}
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-md-12 text-center">--}}
{{--                        @if(count($lcRequests))--}}
{{--                            {{ $lcRequests->render() }}--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush

