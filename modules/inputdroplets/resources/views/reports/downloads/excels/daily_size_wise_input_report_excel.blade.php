<table>
    <thead>
    <tr>
        <td colspan="{{ 9 + $sizes->count() }}">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="{{ 9 + $sizes->count() }}">Daily Size Wise Input Report</td>
    </tr>
    </thead>
</table>

@include('inputdroplets::reports.tables.daily_size_wise_input_table')
