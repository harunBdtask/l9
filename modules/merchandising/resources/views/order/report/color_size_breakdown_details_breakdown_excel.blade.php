<div>
    <table class="reportTable" style="width: 100%">
        <thead>
        <tr>
            <td class="text-center" colspan="12"
                style="background-color: aliceblue; height: 20px;">{{ factoryName() }}</td>
        </tr>
        <tr>
            <td class="text-center" colspan="12"
                style="background-color: aliceblue; height: 20px;">{{ factoryAddress() }}</td>
        </tr>
        <tr></tr>
        <tr>
            <td class="text-center" colspan="12"
                style="background-color: aliceblue; height: 20px;">{{ "Color Size Breakdown Details"}}</td>
        </tr>
        <tr></tr>
        </thead>
    </table>
    @include('merchandising::order.report.color_size_breakdown_details_breakdown')

</div>
