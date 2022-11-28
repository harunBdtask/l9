<div>
    <table class="reportTable" style="width: 30%">
        <tbody>
        <tr>
            <td style="width: 40%;"><strong>Last day Total Production</strong></td>
            <td style="width: 20%;">
                <strong>
                    {{ $totalProductions->where('production_date', $toDate)->sum('total_production_qty') }}
                </strong>
            </td>
            <td style="width: 20%;"><strong>Color</strong></td>
            <td style="width: 20%;"></td>
        </tr>
        <tr>
            <td><strong>Cumulative</strong></td>
            <td><strong>{{ $totalProductions->sum('total_production_qty') }}</strong></td>
            <td><strong>White</strong></td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>

<br/>
<div class="table-responsive">
    <div id="parentTableFixed">
        <table class="reportTable" id="fixTable" style="width: 100%;">
            <thead>
            <tr>
                <th>
                    DATE
                </th>
                <th>
                    M/C NO
                </th>
                <th>
                    BUYER
                </th>
                <th>
                    ORDER
                </th>
                <th>
                    F/TYPE
                </th>
                <th>
                    Dia TYPE
                </th>
                <th>
                    BATCH NO
                </th>
                <th>
                    COLOR
                </th>
                <th>
                    PROD. QTY
                </th>
                <th>
                    Total QTY
                </th>
                <th>
                    TUBE(QTY)
                </th>
                <th>
                    LOADING TIME
                </th>
                <th>
                    UNLOADING TIME
                </th>
                <th>
                    DURATION
                </th>
                <th>
                    REMARKS
                </th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalRow = $productions->pluck('subDyeingProductionDetails')->flatten()->count();
                $totalProductionQty = 0;
            @endphp

            @foreach($productions as $production)
                @php
                    $machines = collect($production->subDyeingBatch->machineAllocations)
                                           ->pluck('machine.name')
                                           ->implode(', ');

                    $machinesCapacity = collect($production->subDyeingBatch->machineAllocations)
                                           ->pluck('machine.capacity')
                                           ->implode(', ');

                    $unloadingDate = Carbon\Carbon::parse($production->unloading_date);
                    $dayDuration = Carbon\Carbon::parse($production->loading_date);

                    $diff = $dayDuration->diff($unloadingDate);
                    $totalDiffDays = $diff->d ? $diff->d . 'd ' : '';
                    $totalDiffHours = $diff->h ? $diff->h . 'h ' : '';
                    $totalDiffMinutes = $diff->i ? $diff->i . 'm ' : '';
                    $totalDiffSeconds = $diff->s ? $diff->s . 's ' : '';
                    $firstRow = $loop->first;
                    $totalQty = $production->subDyeingProductionDetails->sum('dyeing_production_qty');
                @endphp
                @foreach($production->subDyeingProductionDetails as $detail)
                    @php
                        $totalProductionQty += $detail->dyeing_production_qty;
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::make($production->production_date)->toFormattedDateString() }}</td>
                        @if($loop->first)
                            <td rowspan="{{ $production->subDyeingProductionDetails->count() }}">{{ $machines }}
                                , {{$machinesCapacity}}</td>
                            <td rowspan="{{ $production->subDyeingProductionDetails->count() }}">
                                {{ $production->supplier->name }}
                            </td>
                            <td rowspan="{{ $production->subDyeingProductionDetails->count() }}">
                                {{ $detail->order_no }}
                            </td>
                        @endif
                        <td>{{ $detail->fabric_composition_value }}</td>
                        <td>{{ $detail->dia_type_value['name'] }}</td>
                        @if($loop->first)
                            <td rowspan="{{ $production->subDyeingProductionDetails->count() }}">{{ $detail->batch_no }}</td>
                            <td rowspan="{{ $production->subDyeingProductionDetails->count() }}">{{ $detail->color->name }}</td>
                        @endif
                        <td>{{ $detail->dyeing_production_qty }}</td>
                        @if($loop->first)
                            <td rowspan="{{ $production->subDyeingProductionDetails->count() }}">{{ $totalQty }}</td>
                        @endif
                        <td>{{ $production->tube }}</td>
                        <td>{{ $production->loading_date ? \Carbon\Carbon::make($production->loading_date)->format('d/m/Y h:ia') : '' }}</td>
                        <td>{{ $production->unloading_date ? \Carbon\Carbon::make($production->unloading_date)->format('d/m/Y h:ia') : '' }}</td>
                        <td class="text-center">{{ $totalDiffDays . ' ' . $totalDiffHours . ' ' . $totalDiffMinutes . ' ' .$totalDiffSeconds }}</td>
                        <td class="text-center">{{ $production->remarks }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td colspan="8" style="text-align: right">
                    <strong>Total Production Qty</strong>
                </td>
                <td>
                    <strong>{{ $totalProductionQty }}</strong>
                </td>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: right">
                    <strong>Total</strong>
                </td>
                <td>
                    <strong>{{ $totalProductions->sum('total_production_qty') }}</strong>
                </td>
                <td colspan="6"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="{{ asset('modules/skeleton/flatkit/assets/table_head_fixer/tableHeadFixer.js') }}"></script>
<script>
    $(document).ready(function () {
        tableHeadFixer();
    });

    function tableHeadFixer() {
        $(document).find("#fixTable").tableHeadFixer();
        $(document).find(".fixTable").tableHeadFixer();
    }
</script>
