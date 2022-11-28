<table>
    <thead>
    <tr>
        <th style="text-align: center" colspan="{{collect($sizes)->count()+8}}" class="text-center">
            <b>{{ factoryName() }}</b></th>
    </tr>
    <tr>
        <th style="text-align: center" colspan="{{collect($sizes)->count()+8}}"><b>COLOR SIZE SUMMARY REPORT</b></th>
    </tr>
    </thead>
</table>
@include('cuttingdroplets::reports.tables.color_size_summary_report_table')