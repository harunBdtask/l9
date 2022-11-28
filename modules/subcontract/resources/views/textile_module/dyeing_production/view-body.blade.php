<div class="row p-x-1">
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-4" style="float: left; position:relative; margin-top:30px">

            </div>
            <div class="col-md-4" style="float: left; position:relative; margin-top:30px">

            </div>
            <div class="col-md-4" style="float: right; position:relative;margin-top:30px">
                <table class="borderless">
                    <tbody>
                    <tr>
                        <td style="padding-left: 0;" class="text-right">
                            <strong>Production Date :</strong>
                        </td>
                        <td style="padding-left: 30px;"
                            class="text-right"> {{ \Carbon\Carbon::make($dyeingProduction->production_date)->format('d/m/Y') }} </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <table class="reportTable" style="width: 100%;margin-top: 30px;">
            <thead>
            <tr>
                <td colspan="7" class="text-right"><strong>Total Production</strong></td>
                <td class="text-right">
                    <strong>{{ $dyeingProduction->subDyeingProductionDetails->sum('dyeing_production_qty') }}</strong>
                </td>
                <th colspan="5"></th>
            </tr>
            <tr>
                <th>M/C No</th>
                <th>Buyer
                <br>
                /Party
                </th>
                <th>Order No</th>
                <th>Fabric Type</th>
                <th>Fabric Composition</th>
                <th>Batch No</th>
                <th>Color</th>
                <th>Fabric
                    <br>
                    /Qty(KG)
                </th>
                <th>Tube</th>
                <th>Loading Time</th>
                <th>Unloading Time</th>
                <th>Duration</th>
                <th>Remarks</th>
            </tr>

            </thead>
            <tbody>
            @php
                $machines = collect($dyeingProduction->subDyeingBatch->machineAllocations)
                                   ->pluck('machine.name')
                                   ->implode(',');

                $machinesCapacity = collect($dyeingProduction->subDyeingBatch->machineAllocations)
                                   ->pluck('machine.capacity')
                                   ->implode(',');

            @endphp
            @foreach ($dyeingProduction->subDyeingProductionDetails as $item)
            @php
                $unloadingDate = Carbon\Carbon::parse($dyeingProduction->unloading_date);
                $dayDuration = Carbon\Carbon::parse($dyeingProduction->loading_date);

                $diff = $dayDuration->diff($unloadingDate);
                $totalDiffDays = $diff->d ? $diff->d . ' Day ' : '';
                $totalDiffHours = $diff->h ? $diff->h . ' Hour ' : '';
                $totalDiffMinutes = $diff->i ? $diff->i . ' Minute ' : '';
                $totalDiffSeconds = $diff->s ? $diff->s . ' Second ' : '';

            @endphp
                <tr>
                    <td class="text-center">{{ $machines }}, {{$machinesCapacity}}</td>
                    <td class="text-center">{{ $dyeingProduction->supplier->name }}</td>
                    <th class="text-center">{{ $item->order_no }}</th>
                    <th class="text-center">{{ $item->fabricType->construction_name }}</th>
                    <td class="text-center">{{ $item->batchDetail->material_description }}</td>
                    <td class="text-center">{{ $item->batch_no }}</td>
                    <td class="text-center">{{ $item->color->name }}</td>
                    <td class="text-right">{{ $item->dyeing_production_qty }}</td>
                    <td class="text-center">{{ $dyeingProduction->tube  }}</td>
                    <td class="text-center">{{ $dyeingProduction->loading_date  }}</td>
                    <td class="text-center">{{ $dyeingProduction->unloading_date  }}</td>
                    <td class="text-center">{{ $totalDiffDays . ' ' . $totalDiffHours . ' ' . $totalDiffMinutes . ' ' .$totalDiffSeconds }}</td>
                    <td class="text-center">{{ $dyeingProduction->remarks }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <br>
        <br>

    </div>
</div>
