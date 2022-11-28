<table>
    <thead>
    <tr>
        <td colspan="8"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="8"
            style="text-align: center;height: 35px">
            <b>Recipe Details</b>
        </td>
    </tr>
    </thead>
</table>

<table class="reportTable" style="width: 100%;margin-top: 30px;">
    <thead>
    <tr>
        <th>Item Name</th>
        <th>Dosing Percent</th>
        <th>Dosing Quantity</th>
        <th>G/Ltr</th>
        <th>GPL Quantity</th>
        <th>Unit</th>
        <th>Additional Quantity(KG)</th>
        <th>Remarks</th>
    </tr>

    </thead>
    <tbody>

    @foreach ($dyeingRecipe as $details)
        <tr>
            <td class="text-center" style="background-color: lightgrey;" colspan="9">
                <strong>{{ $details->first()->recipeOperation->name }}</strong>
            </td>
        </tr>
        @foreach ($details as $item)
            <tr>
                <td>{{ $item->dsItem->name }}</td>
                <td>{{ $item->total_percentage }}</td>
                <td>
                    @if ($item->total_percentage)
                        {{ number_format($item->sum_total_qty, 3) }}
                    @endif
                </td>
                <td>{{$item->total_g_per_ltr}}</td>
                <td>
                    @if ($item->total_g_per_ltr)
                        {{ number_format($item->sum_total_qty, 3) }}
                    @endif
                </td>
                <td>{{$item->unitOfMeasurement->name}}</td>
                <td></td>
                <td>{{$item->remarks}}</td>
            </tr>
        @endforeach
    @endforeach

    </tbody>
</table>
