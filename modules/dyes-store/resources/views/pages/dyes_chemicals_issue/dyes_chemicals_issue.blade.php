@extends('dyes-store::layout')
@section('title','Dyes Chemical Issue')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>DYES CHEMICALS ISSUES</h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row m-b-2">
                    <div class="box-body">
                        <a class="btn btn-sm btn-info" href="{{ url('/dyes-store/dyes-chemical-issue/create') }}">
                            <i class="glyphicon glyphicon-plus"></i> New Stock Out
                        </a>
                    </div>
                    <div class="col-md-2">
                        {{ Form::hidden('type', $type ?? null, ['class' => 'form-control','id' => 'type']) }}
                        {{ Form::label('start_date', 'From Date') }}
                        {{ Form::date('start_date', $start_date ?? null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-2">
                        {{ Form::label('to_date', 'To Date') }}
                        {{ Form::date('end_date', $end_date ?? null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-2">
                        {{ Form::label('search', 'Search') }}
                        {!! Form::text('search', $search ?? null, ['class' => 'form-control', 'id' => 'search', 'placeholder' => 'Write something...']) !!}
                    </div>

                    <div class="col-md-4 m-t-10" style="margin-top: 30px;">
                        <div class="btn-group">
                            <button id="filter-button" class="btn btn-sm btn-outline text-dark b-info">Filter</button>
                            <button id="clear-button" class="btn btn-sm btn-outline text-dark b-info">Clear</button>
                        </div>
                    </div>
                </div>

                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if (Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr style="background: ghostwhite;">
                                <th style="text-align:center; padding-left: 1em;">Sl</th>
                                <th style="text-align:center; padding-left: 1em;">Delivery Date</th>
                                <th style="text-align:center; padding-left: 1em;">Customer Name</th>
                                <th style="text-align:center; padding-left: 1em;">Batch No</th>
                                <th style="width: 170px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($issues as $value)
                                @php
                                    $batchNo  = collect($value->details)->pluck('batch_no')->unique()->join(' , ');
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td style="text-align:center; padding-left: 1em;">
                                        {{ \Carbon\Carbon::parse($value->delivery_date)->toFormattedDateString() }}
                                    </td>
                                    <td style="text-align:center; padding-left: 1em;">
                                        {{ $value->customer->name }}
                                    </td>
                                    <td>{{ $batchNo }}</td>
                                    <td>
                                        @if($value->readonly)
                                            <a class="btn btn-xs text-info" data-toggle="tooltip" data-placement="top"
                                               href="{{ url('/dyes-store/dyes-chemical-issue/create?dyes_issue_id=' . $value->id) }}"
                                               title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a class="btn btn-xs text-success"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               href="{{ url('/dyes-store/dyes-chemical/' . $value->id . '/stock-transaction?type=out') }}"
                                               onclick="return confirm('Are You Sure?');"
                                               title="Make Transaction">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>
                                            <a class="btn btn-xs text-danger" data-toggle="tooltip" data-placement="top"
                                               href="{{ url('/dyes-store/dyes-chemical-issue/' . $value->id . '/destroy') }}"
                                               onclick="return confirm('Are You Sure?');" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        @endif
                                        <a class="btn btn-xs text-dark" data-toggle="tooltip" data-placement="top"
                                           href="{{ url('/dyes-store/dyes-chemical-issue/' . $value->id) }}" title="View">
                                            <i class="fa  fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="9">Not Found!</td>
                            @endforelse
                            </tbody>
                        </table>
                        @if ($issues->total() > 15)
                            <div
                                class="text-center print-delete">{{ $issues->appends(request()->except('page'))->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        function select2() {
            $('select').select2({
                tags: false,
                tokenSeparators: [',', ' ']
            });
        }

        $(document).ready(function () {
            select2();

            const value = (selector) => $(selector).val();

            $('#create-button').click(() => gotoURL());

            $('#filter-button').click(() => filterVouchers());

            $('#clear-button').click(() => window.location = '/dyes-store/dyes-chemical-issue?type=out');

            function gotoURL() {
                window.location = '/dyes-store/dyes-chemical-issue/create';
            }

            function filterVouchers() {
                const type = value('#type');
                const startDate = value('input[name=start_date]');
                const endDate = value('input[name=end_date]');
                const search = value('input[name=search]');

                if (type === 'in') {
                    let URL = `/dyes-store/dyes-chemical?type=in`;
                    URL += `&start_date=${startDate}&end_date=${endDate}&search=${search}`;
                    window.location = URL;
                }
                if (type === 'out') {
                    let URL = `/dyes-store/dyes-chemical-issue?type=out`;
                    URL += `&start_date=${startDate}&end_date=${endDate}&search=${search}`;
                    window.location = URL;
                }
            }
        });
    </script>
@endpush
