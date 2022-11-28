<table class="mt-2">
    <thead>
    <tr style="background-color: mintcream">
        <td colspan="3"><b>BUDGET SUMMARY ANALYSIS</b></td>
    </tr>
    </thead>
    <tbody>
    <tr style="background-color: aliceblue">
        <td><b>TOTAL COST PERCENTAGE OF YARN</b></td>
        <td><b>${{ number_format(collect($yarnCostData)->sum('total_amount'), 2) }}</b></td>
        <td><b>{{ number_format(collect($yarnCostData)->sum('pre_cost'), 2) }}%</b></td>
    </tr>
    <tr style="background-color: aliceblue">
        <td><b>TOTAL COST PERCENTAGE OF KNITTING</b></td>
        <td><b>${{ number_format(collect($knitCostData)->sum('total_amount'), 2) }}</b></td>
        <td><b>{{ number_format(collect($knitCostData)->sum('pre_cost'), 2) }}%</b></td>
    </tr>
    <tr style="background-color: aliceblue">
        <td><b>TOTAL COST PERCENTAGE OF DYEING</b></td>
        <td><b>${{ number_format(collect($dyingCostData)->sum('total_amount'), 2) }}</b></td>
        <td><b>{{ number_format(collect($dyingCostData)->sum('pre_cost'), 2) }}%</b></td>
    </tr>
    <tr style="background-color: aliceblue">
        <td><b>TOTAL COST PERCENTAGE OF EMBELLISHMENT</b></td>
        <td><b>${{ number_format($totalEmblCost, 2) }}</b></td>
        <td><b>{{ number_format($emblPreCost, 2) }}%</b></td>
    </tr>
    <tr style="background-color: aliceblue">
        <td><b>TOTAL COST PERCENTAGE OF ACCESSORIES</b></td>
        <td><b>${{ number_format(collect($trimsCostData)->sum('total_amount'), 2) }}</b></td>
        <td><b>{{ number_format(collect($trimsCostData)->sum('pre_cost'), 2) }}%</b></td>
    </tr>
    <tr style="background-color: aliceblue">
        <td><b>TOTAL COST PERCENTAGE OF COMMERCIAL</b></td>
        <td><b>${{ number_format($totalCommercialCost, 2) }}</b></td>
        <td><b>{{ number_format($totalCommercialPreCost, 2) }}%</b></td>
    </tr>
    <tr style="background-color: aliceblue">
        <td><b>TOTAL OTHER COST PERCENTAGE</b></td>
        <td><b>${{ number_format($totalOtherCost, 2) }}</b></td>
        <td><b>{{ number_format($totalOtherPreCost, 2) }}%</b></td>
    </tr>
    <tr style="background-color: gainsboro">
        <td><b>SUB TOTAL FOR BACK TO BACK</b></td>
        <td><b>${{ number_format($grandTotal, 2) }}</b></td>
        <td><b>{{ number_format($grandPreCost, 2) }}%</b></td>
    </tr>
    <tr style="background-color:greenyellow">
        <td><b>TOTAL COST PERCENTAGE OF CM</b></td>
        <td><b>${{ number_format($totalCm, 2) }}</b></td>
        <td><b>{{ number_format($cmPreCost, 2) }}%</b></td>
    </tr>
    <tr style="background-color:greenyellow">
        <td><b>SUB TOTAL</b></td>
        <td><b>${{ number_format($totalCm, 2) }}</b></td>
        <td><b>{{ number_format($cmPreCost, 2) }}%</b></td>
    </tr>
    <tr style="background-color: gainsboro">
        <td><b>TOTAL</b></td>
        <td><b>${{ number_format(($totalCm + $grandTotal), 2) }}</b></td>
        <td><b>{{ number_format(($cmPreCost + $grandPreCost), 2) }}%</b></td>
    </tr>
    </tbody>
</table>
