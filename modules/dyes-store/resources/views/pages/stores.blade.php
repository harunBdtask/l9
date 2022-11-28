@extends('dyes-store::layout')
@section('title', 'Store')
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Stores</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="box-body">
                    <a style="margin-left: -1.5%;" href="{{ url('/dyes-store/stores/create') }}"
                       class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Create Stores
                    </a>
                </div>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <table class="reportTable" style="min-height: 150px;">
                            <thead>
                            <tr>
                                <th>Store</th>
                                <th colspan="2" style="width: 45%;">Operation</th>
                                <th>Report</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @isset($stores)
                                @foreach($stores as $store)
                                    <tr>
                                        <td><b>{{ $store['name'] }}</b></td>
                                        <td>
                                            <a href="{{ url('/dyes-store/stores/' . $store->id . '/in') }}"
                                               class="btn btn-xs btn-info">IN</a>
                                        </td>
                                        <td>
                                            <a href="{{ url('/dyes-store/stores/' . $store->id . '/out') }}"
                                               class="btn btn-xs btn-warning">OUT</a>
                                        </td>

                                        <td>
                                            <a href="{{ url('/dyes-store/stores/' . $store->id . '/report') }}"
                                               class="btn btn-xs btn-primary">STOCK SUMMARY</a>
                                            <a href="{{ url('/dyes-store/stores/daily/' . $store->id . '/report') }}"
                                               class="btn btn-xs btn-primary">DAILY REPORT</a>
                                        </td>
                                        <td>
                                            <a href="{{url('/dyes-store/stores/'.$store->id.'/edit')}}"
                                               class="btn btn-xs btn-success"
                                               data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
