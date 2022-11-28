@extends('skeleton::layout')
@section("title","MC Barcode Generation")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Machine Barcode</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('McInventory::partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('mc-inventory/machine-barcode-generation/create') }}"
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
                                <th>Company</th>
                                <th>No Of Machine</th>
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
                                <td>{{ $item->factory->factory_name }}</td>
                                <td>{{ $item->no_of_machine }}</td>
                                <td>
                                    {{-- <a class="btn btn-xs btn-info" type="button" href=""> <em class="fa fa-pencil"></em> </a> --}}
                                    <a class="btn btn-success btn-xs" type="button"
                                    href="{{ url('mc-inventory/machine-barcode-generation/create?id='.$item->id) }}">
                                        <em class="fa fa-eye"></em>
                                    </a>
                                    <button style="margin-left: 2px;" type="button"
                                            class="btn btn-xs btn-danger show-modal"
                                            title="Delete Barcode Generation"
                                            data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate"
                                            data-url="{{ url('/mc-inventory/machine-barcode-generation/destroy/'.$item->id) }}">
                                        <em class="fa fa-trash"></em>
                                    </button>
                                </td>
                            </tr>
                            @empty

                            <tr>
                                <td colspan="4" align="center">No Data</td>
                            </tr>
                            @endforelse

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $list->appends(request()->query())->links()  }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
