@extends('subcontract::layout')
@section("title","Machine Profile")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Machine Profile</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('McInventory::partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('mc-inventory/machine-profile/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Update
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Company</th>
                                    <th>M/C Name</th>
                                    <th>Barcode</th>
                                    <th>Brand</th>
                                    <th>Model No</th>
                                    <th>M/C Category</th>
                                    <th>Type</th>
                                    <th>Sub Type</th>
                                    <th>M/C Origin</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($list as $key=>$item)
                                @php 
                                    $key = $key+1+($list->currentPage()-1)*$list->perPage() 
                                @endphp
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $item->factory->factory_name??null }}</td>
                                    <td>{{ $item->name??null }}</td>
                                    <td>{{ $item->barcode??null }}</td>
                                    <td>{{ $item->brand->name??null }}</td>
                                    <td>{{ $item->model_no??null }}</td>
                                    <td>{{ $item->category??null }}</td>
                                    <td>{{ $item->type->machine_type??null }}</td>
                                    <td>{{ $item->subtype->machine_sub_type??null }}</td>
                                    <td>{{ $item->origin_value??null }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-success" type="button"
                                           href="/mc-inventory/machine-profile/{{$item->barcode}}/edit">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <!-- <a class="btn btn-success btn-xs" type="button"
                                        href="">
                                            <em class="fa fa-eye"></em>
                                        </a> -->
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('mc-inventory/machine-profile/delete/'.$item->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" align="center">No Data</td>
                                </tr>
                                @endforelse
                            
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $list->appends(request()->query())->links()  }}
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
