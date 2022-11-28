<table class="reportTable">
    <thead>
    <tr>
        <th>Date</th>
        @foreach($slittingsMachine as $machine)
            <th>{{ $machine->name }}</th>
        @endforeach
        <th>Total slitter</th>
        @foreach($stenteringsMachine as $machine)
            <th>{{ $machine->name }}</th>
        @endforeach
        <th>Total Stentering</th>
        @foreach($subCompactorsMachine as $machine)
            <th>{{ $machine->name }}</th>
        @endforeach
        <th>Total Compactor</th>
        @foreach($tubeCompactingsMachine as $machine)
            <th>{{ $machine->name }}</th>
        @endforeach
        <th>Total Tube Compacting</th>
        @foreach($subDryersMachine as $machine)
            <th>{{ $machine->name }}</th>
        @endforeach
        <th>Total Dryer</th>
        @foreach($subSqueezersMachine as $machine)
            <th>{{ $machine->name }}</th>
        @endforeach
        <th>Total Squeezer</th>
        @foreach($subPeachsMachine as $machine)
            <th>{{ $machine->name }}</th>
        @endforeach
        <th>Total Peach</th>
        @foreach($subBrushesMachine as $machine)
            <th>{{ $machine->name }}</th>
        @endforeach
        <th>Total Brush</th>
        <th>Total Tumbler</th>
        @foreach($subHtSetsMachine as $machine)
            <th>{{ $machine->name }}</th>
        @endforeach
        <th>Total Ht Set</th>
        <th>Total</th>
    </tr>

    </thead>
    <tbody>
    @foreach($reportData as $data)
        <tr>
            <td>
                {{$data['date']}}
            </td>

            @foreach($slittingsMachine as $machine)
                <td style="text-align: right">
                    {{ $data['slittings'][$machine->name] }}
                </td>
            @endforeach
            <td style="text-align: right">
                {{$data['total_slitter']}}
            </td>

            @foreach($stenteringsMachine as $machine)
                <td style="text-align: right">
                    {{ $data['stenterings'][$machine->name] }}
                </td>
            @endforeach
            <td style="text-align: right">
                {{$data['total_stenterings']}}
            </td>

            @foreach($subCompactorsMachine as $machine)
                <td style="text-align: right">
                    {{ $data['compactors'][$machine->name] }}
                </td>
            @endforeach
            <td style="text-align: right">
                {{$data['total_compactors']}}
            </td>
            @foreach($tubeCompactingsMachine as $machine)
                <td style="text-align: right">
                    {{ $data['tube_compactings'][$machine->name] }}
                </td>
            @endforeach
            <td style="text-align: right">
                {{$data['total_tube_compactings']}}
            </td>
            @foreach($subDryersMachine as $machine)
                <td style="text-align: right">
                    {{ $data['dryers'][$machine->name] }}
                </td>
            @endforeach
            <td style="text-align: right">{{$data['total_dryers']}}</td>
            @foreach($subSqueezersMachine as $machine)
                <td style="text-align: right">
                    {{ $data['squeezers'][$machine->name] }}
                </td>
            @endforeach
            <td style="text-align: right">{{$data['total_dyeing_squeezers']}}</td>
            @foreach($subPeachsMachine as $machine)
                <td style="text-align: right">
                    {{ $data['peachs'][$machine->name] }}
                </td>
            @endforeach
            <td style="text-align: right">{{$data['total_dyeing_peachs']}}</td>
            @foreach($subBrushesMachine as $machine)
                <td style="text-align: right">
                    {{ $data['brushes'][$machine->name] }}
                </td>
            @endforeach
            <td style="text-align: right">{{$data['total_brushes']}}</td>
            <td style="text-align: right">{{$data['total_dyeing_tumbles']}}</td>
            @foreach($subHtSetsMachine as $machine)
                <td style="text-align: right">
                    {{ $data['ht_sets'][$machine->name] }}
                </td>
            @endforeach
            <td style="text-align: right">{{$data['total_dyeing_htSets']}}</td>
            <td style="text-align: right">
                {{
                $data['total_slitter']+$data['total_stenterings']+
                $data['total_compactors'] + $data['total_tube_compactings']+
                $data['total_dryers']+$data['total_dyeing_squeezers']+
                $data['total_dyeing_peachs']+ $data['total_brushes']+
                $data['total_dyeing_tumbles']+ $data['total_dyeing_htSets']
                }}
            </td>

        </tr>
    @endforeach
    <tr>
        <td colspan="{{ 1 + $slittingsMachine->count() }}" style="text-align: right">
            <strong>Total</strong>
        </td>
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_slitter') }}</strong>
        </td>
        @if($stenteringsMachine->count() > 0)
            <td colspan="{{ $stenteringsMachine->count() }}">
            </td>
        @endif
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_stenterings') }}</strong>
        </td>
        @if($subCompactorsMachine->count() > 0)
            <td colspan="{{ $subCompactorsMachine->count() }}">
            </td>
        @endif
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_compactors') }}</strong>
        </td>
        @if($tubeCompactingsMachine->count() > 0)
            <td colspan="{{ $tubeCompactingsMachine->count() }}">
            </td>
        @endif
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_tube_compactings') }}</strong>
        </td>
        @if($subDryersMachine->count() > 0)
            <td colspan="{{ $subDryersMachine->count() }}">
            </td>
        @endif
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_dryers') }}</strong>
        </td>
        @if($subSqueezersMachine->count() > 0)
            <td colspan="{{ $subSqueezersMachine->count() }}">
            </td>
        @endif
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_dyeing_squeezers') }}</strong>
        </td>
        @if($subPeachsMachine->count() > 0)
            <td colspan="{{ $subPeachsMachine->count() }}">
            </td>
        @endif
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_dyeing_peachs') }}</strong>
        </td>
        @if($subBrushesMachine->count() > 0)
            <td colspan="{{ $subBrushesMachine->count() }}">
            </td>
        @endif
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_brushes') }}</strong>
        </td>
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_dyeing_tumbles') }}</strong>
        </td>
        @if($subHtSetsMachine->count() > 0)
            <td colspan="{{ $subHtSetsMachine->count() }}">
            </td>
        @endif
        <td style="text-align: right">
            <strong>{{ collect($reportData)->sum('total_dyeing_htSets') }}</strong>
        </td>
        <td></td>
    </tr>
    </tbody>
</table>
