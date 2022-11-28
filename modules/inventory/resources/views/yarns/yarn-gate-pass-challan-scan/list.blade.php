@extends('skeleton::layout')
@section('title','Yarn Issue')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Issue List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/inventory/yarn-gate-pass-challan-scan') }}"
                            class="btn btn-sm btn-info m-b">
                            <i class="fa fa-plus"></i> Gate Pass Challan Scan
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Issue No</th>
                                    <th>Challan No</th>
                                    <th>Issue Date</th>
                                    <th>Party Name</th>
                                    <th>Gate Pass No</th>
                                    <th>Vehicle Number</th>
                                    <th>Lock No</th>
                                    <th>Driver Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($gatePassChallan as $key => $gatePass)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $gatePass->issue_no }}</td>
                                        <td>{{ $gatePass->challan_no }}</td>
                                        <td>{{ $gatePass->challan_date }}</td>
                                        <td>{{ $gatePass->supplier->name ?? '' }}</td>
                                        <td>{{ $gatePass->gate_pass_no }}</td>
                                        <td>{{ $gatePass->vehicle_number }}</td>
                                        <td>{{ $gatePass->lock_no }}</td>
                                        <td>{{ $gatePass->driver_name }}</td>
                                        <td>
                                            <a
                                                title="Yarn Challan"
                                                class="btn btn-xs btn-info"
                                                href="/inventory/yarn-issue/challan/{{ $gatePass->yarn_issue_id }}/yarn-challan-view">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" align="center">No Data</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="row m-t">
                                <div class="col-sm-12">
                                    {{ $gatePassChallan->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
@endsection
