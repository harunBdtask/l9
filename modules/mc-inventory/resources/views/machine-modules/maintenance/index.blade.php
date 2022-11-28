@extends('subcontract::layout')
@section("title","Maintanance")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Maintenance</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('McInventory::partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('mc-inventory/maintenance/create') }}"
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
                                <th>Barcode</th>
                                <th>M/C Serial No</th>
                                <th>Last Maintenance</th>
                                <th>Tenor</th>
                                <th>Next Maintenance</th>
                                <th>Description</th>
                                <th>Parts Change</th>
                                <th>Parts Change Description</th>
                                <th>Mechanic</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse ($maintenances as $maintenance)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$maintenance->machine->name}}</td>
                                <td>{{$maintenance->machine->barcode}}</td>
                                <td>{{$maintenance->machine->serial_no}}</td>
                                <td>{{$maintenance->last_maintenance}}</td>
                                <td>{{$maintenance->tenor}}</td>
                                <td>{{$maintenance->next_maintenance}}</td>
                                <td>{{$maintenance->description}}</td>
                                <td>{{$maintenance->parts_change_value}}</td>
                                <td>{{$maintenance->parts_change_description}}</td>
                                <td>{{$maintenance->mechanic}}</td>
                                <td>{{$maintenance->status_value}}</td>
                                <td>
                                    <a class="btn btn-xs btn-success" type="button"
                                        href="/mc-inventory/maintenance/{{$maintenance->id}}/{{$maintenance->machine->barcode}}/edit">
                                        <em class="fa fa-pencil"></em>
                                    </a>
                                    <button style="margin-left: 2px;" type="button"
                                            class="btn btn-xs btn-danger show-modal"
                                            title="Delete Order"
                                            data-toggle="modal"
                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                            ui-target="#animate"
                                            data-url="{{ url('mc-inventory/maintenance/' . $maintenance->id) }}">
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
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $maintenances->appends(request()->query())->links()  }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->

@endsection

