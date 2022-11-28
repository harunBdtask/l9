<div class="padding">
    <div class="box">
        <div class="box-body table-responsive b-t">
            <div class="row">
                <form action="">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="reportTable" style="margin-top: 2%;">
                                    @php
                                        $total_cols = count($units)+count($locations)+10;


                                    @endphp
                                    <thead>
                                    <tr>
                                        <th colspan="{{ $total_cols}}"> <h4>SATURN TEXTILES KNITS DIVISION MACHINE SUMMARY </h4> </th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2"><b>SL</b></th>
                                        <th rowspan="2"><b>Machine</b></th>
                                        <th rowspan="2"><b>Machine Sub Type</b></th>
                                        <th rowspan="2"><b>TOTAL STRENGTH(QNTY)</b></th>
                                        @foreach($units as $unit)
                                            <th rowspan="2"><b>{{ $unit->name }}</b></th>
                                        @endforeach
                                        <th colspan="{{ collect($locations)->count() }}"><b>Loan Given Details</b></th>
                                        <th rowspan="2"><b>TOTAL MACHINE LOAN GIVEN TO OTHER FACTORY </b></th>
                                        <th rowspan="2"><b>TOTAL SATURN INHOUSE MACHINE</b></th>
                                        <th rowspan="2"><b>TOTAL SATURN INCLUDING LOAN</b></th>
                                        <th rowspan="2"><b>DIFFERENCE TO STRENGTH</b></th>
                                        <th rowspan="2"><b>MACHINE LOAN TAKEN FROM OTHER FACTORY</b></th>
                                        <th rowspan="2"><b>REMARKS</b></th>
                                    </tr>
                                    <tr>
                                        @foreach($locations as $location)
                                            <th><b>{{ $location->location_name }}</b></th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $totalSumStength = 0;
                                        $totalSumUnit = [];
                                        $totalSumLocation = [];
                                        $totalSumMcTypeTotalGiven = 0;
                                        $totalSumMcTypeTotalInHouse = 0;
                                        $totalSumMcTypeTotalIncludeLoan = 0;
                                        $totalSumDiffToStength = 0;
                                        $totalSumLoanTaken = 0;
                                    @endphp
                                    @foreach($items as $key=>$val)
                                        @foreach($val->machineSubType as $type)
                                            @php
                                                $mcTypeTotalInHouse = 0;
                                                $mcTypeTotalGiven = 0;
                                                $mcTypeTotalIncludeLoan = 0;
                                                $total_loan_taken = collect($type->machine)->where('origin', $loan_taken_origin)->count();
                                                $totalStength = collect($machineProfile)->where('sub_type_id',$type->id)->count()??0;
                                                $rowSpan = $val->machineSubType->count();
                                                $totalSumStength += $totalStength;
                                                $totalSumLoanTaken += $total_loan_taken;
                                            @endphp
                                            <tr>
                                                @if($loop->first)
                                                    <td rowspan="{{ $rowSpan }}" class="text-center">{{ ++$key }}</td>
                                                    <td rowspan="{{ $rowSpan }}" class="text-center">{{ $val->machine_type }}</td>
                                                @endif
                                                <td class="text-center">{{ $type->machine_sub_type }}</td>
                                                <td class="text-center">{{ $totalStength }}</td>
                                                @foreach($units as $unit)
                                                    @php
                                                        $unit_machine = collect($unit->machine)->where('sub_type_id', $type->id)->where('unit_id', $unit->id)->count();
                                                        $mcTypeTotalInHouse +=$unit_machine;
                                                        $totalSumUnit[$unit->name] = ($totalSumUnit[$unit->name] ?? 0) + $unit_machine;
                                                        $totalSumMcTypeTotalInHouse += $unit_machine;
                                                    @endphp
                                                    <td>{{ $unit_machine??0 }}</td>
                                                @endforeach

                                                @foreach($locations as $location)
                                                    @php
                                                        $loc_machine = collect($location->machine)->where('sub_type_id', $type->id)->where('location_id', $location->id)->where('origin', $loan_given_origin)->count();
                                                        $mcTypeTotalGiven +=$loc_machine;
                                                        $totalSumLocation[$location->location_name] = ($totalSumLocation[$location->location_name] ?? 0) + $loc_machine;
                                                        $totalSumMcTypeTotalGiven += $loc_machine;
                                                    @endphp
                                                    <td>{{ $loc_machine??0 }}</td>
                                                @endforeach
                                                <td class="text-center">{{ $mcTypeTotalGiven }} </td>
                                                <td class="text-center">{{ $mcTypeTotalInHouse }}</td>
                                                @php
                                                    $mcTypeTotalIncludeLoan = $mcTypeTotalGiven + $mcTypeTotalInHouse;
                                                    $diffToStrength = $mcTypeTotalIncludeLoan - $totalStength - $total_loan_taken ;
                                                    $totalSumMcTypeTotalIncludeLoan += $mcTypeTotalIncludeLoan;
                                                    $totalSumDiffToStength += $diffToStrength;
                                                @endphp
                                                <td class="text-center">{{ $mcTypeTotalIncludeLoan }}</td>
                                                <td class="text-center">{{ $diffToStrength }}</td>
                                                <td class="text-center">{{ $total_loan_taken }}</td>
                                                <td class="text-center">{{ $type->description }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach

                                    <tr>
                                        <td colspan="3"> <b>Total</b> </td>
                                        <td> <b>{{ $totalSumStength }}</b> </td>
                                        @foreach($units as $unit)
                                            <td> <b>{{ $totalSumUnit[$unit->name] }}</b> </td>
                                        @endforeach
                                        @foreach($locations as $location)
                                            <td> <b>{{ $totalSumLocation[$location->location_name] }}</b> </td>
                                        @endforeach
                                        <td> <b>{{ $totalSumMcTypeTotalGiven }}</b> </td>
                                        <td> <b>{{ $totalSumMcTypeTotalInHouse }}</b> </td>
                                        <td> <b>{{ $totalSumMcTypeTotalIncludeLoan }}</b> </td>
                                        <td> <b>{{ $totalSumDiffToStength }}</b> </td>
                                        <td> <b>{{ $totalSumLoanTaken }}</b> </td>
                                        <td></td>
                                    </tr>
                                    @php
                                        $totalRowSpan = 6 + collect($units)->count() + collect($locations)->count();
                                    @endphp
                                    <tr>
                                        <td colspan="4"> <b>Total Machine Owned By {{ factoryName() }}</b> </td>
                                        <td class="text-center" colspan="{{ $totalRowSpan }}"> <b>{{ $totalSumStength }}</b> </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4"> <b> Loan Taken From Other Factory </b> </td>
                                        <td class="text-center" colspan="{{ $totalRowSpan }}"> <b>{{ $totalSumLoanTaken }}</b> </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4"> <b>Total Machine Given Loan Outside {{ factoryName() }} </b> </td>
                                        <td class="text-center" colspan="{{ $totalRowSpan }}"> <b>{{ $totalSumMcTypeTotalGiven }}</b> </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4"> <b>Total Machine Inside {{ factoryName() }} Campus At Present </b> </td>
                                        <td class="text-center" colspan="{{ $totalRowSpan }}"> <b>{{ $totalSumMcTypeTotalInHouse }}</b> </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <br>
    </div>
</div>
