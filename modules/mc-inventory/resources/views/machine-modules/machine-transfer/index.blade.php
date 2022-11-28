@extends('subcontract::layout')
@section("title","Machine Transfer")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Machine Transfer</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('mc-inventory/machine-transfer/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Machine</th>
                                <th>Transfer From</th>
                                <th>Transfer To</th>
                                <th>Reason</th>
                                <th>Attention</th>
                                <th>Contact No</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse ($machineTransfers as $key=>$transfer)
                            @php
                                $key = $key+1+($machineTransfers->currentPage()-1)*$machineTransfers->perPage()
                            @endphp
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$transfer->machine->name}}</td>
                                <td>{{$transfer->machineTransferFrom->location_name}}</td>
                                <td>{{$transfer->machineTransferTo->location_name}}</td>
                                <td>{{$transfer->reason}}</td>
                                <td>{{$transfer->attention}}</td>
                                <td>{{$transfer->contact_no}}</td>
                                <td>
                                    <a class="btn btn-xs btn-success" type="button"
                                           href="{{ url('mc-inventory/machine-transfer/create?id='.$transfer->id) }}">
                                            <em class="fa fa-pencil"></em>
                                    </a>
                                    <a class="btn btn-primary btn-xs" type="button"
                                       href="{{ url('mc-inventory/machine-transfer/view/'.$transfer->id) }}">
                                        <em class="fa fa-eye"></em>
                                    </a>
                                    <button style="margin-left: 2px;" type="button"
                                            class="btn btn-xs btn-danger show-modal"
                                            title="Delete Order"
                                            data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate"
                                            data-url="{{ url('mc-inventory/machine-transfer/' . $transfer->id) }}">
                                        <em class="fa fa-trash"></em>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" align="center">No Data</td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $machineTransfers->appends(request()->query())->links()  }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#party_id').select2({
                ajax: {
                    url: "/subcontract/api/v1/textile-parties/select-search",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response.data,
                            pagination: {
                                more: false
                            }
                        };
                    },
                    cache: true,
                    delay: 150,
                },
                placeholder: 'Search',
                allowClear: true,
            });

            $(document).on('click', '#recipeModal', function () {
                const recipeId = $(this).attr('data');
                let url = $('#sub_dyeing_recipe_requisition_form').attr('action');
                url += `/${recipeId}/store`;
                $('#sub_dyeing_recipe_requisition_form').attr('action', url);
            });

        });

    </script>
@endsection
