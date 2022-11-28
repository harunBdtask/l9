@extends('skeleton::layout')
@section("title","PO Files(Excel)")

@section('styles')
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header" style="height: 55px;">
                <div class="row">
                    <div class="col-sm-12">
                        <h2>PO File Log <span class="label bg-info">{{ request('poNo') }}</span></h2>
                    </div>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        @forelse($logs as $log)
                            <span><strong>Replaced by :</strong> {{ $log['replaced_by'] }}</span> <br>
                            <span><strong>Replaced at :</strong> {{ $log['created_at'] }}</span> <br>
                            <span><strong>Remarks :</strong> {{ $log['remarks'] }}</span>
                            @foreach($log['quantity_matrix'] as $item => $matrix)
                                <table class="reportTable">
                                    <thead>
                                    <tr>
                                        <th colspan="{{ count($sizes) + 2 }}">{{ $item }}</th>
                                    </tr>
                                    <tr class="table-header">
                                        <th>Color</th>
                                        <th>Particular</th>
                                        @foreach($sizes as $size)
                                            <th class="text-uppercase">{{ $size }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($matrix as $color => $colorWise)
                                        @php
                                            $colorRowSpan = true;
                                        @endphp
                                        @foreach($colorWise as $particular => $value)
                                            <tr>
                                                @if($colorRowSpan)
                                                    <td rowspan="{{ count($colorWise) }}">{{ $color }}</td>
                                                @endif
                                                <td><b>{{ $particular }}</b></td>
                                                @foreach($sizes as $size)
                                                    <td>{{ $value[$size] ?? '' }}</td>
                                                @endforeach
                                            </tr>
                                            @php
                                                $colorRowSpan = false;
                                            @endphp
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        @empty
                            <strong class="text-center">No Log Found</strong>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
