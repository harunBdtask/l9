<div class="header-section" style="padding-bottom: 0px;">
    <table class="borderless">
        <thead>
        <tr>
            <td class="text-left" style="background-color: lightblue"
                colspan="{{count(collect($reportData)->flatten(1))+1}}"><b>{{ factoryName() }}</b></td>
        </tr>
        <tr>
            <td class="text-left" style="background-color: lightblue"
                colspan="{{count(collect($reportData)->flatten(1))+1}}"><b>{{ factoryAddress() }}</b></td>
        </tr>
        </thead>
    </table>
</div>

<div class="body-section" style="margin-top: -10px;">
    <table class="borderless">
        <thead>
        <tr>
            <td class="text-center" style="text-align: center;" colspan="{{count(collect($reportData)->flatten(1))+1}}">Report Title: Actual Month Wise
                Receipt Payment Report
            </td>
        </tr>
        </thead>
    </table>
    @includeIf('basic-finance::tables.month_wise_receipt_payment_table_without_contra')
</div>

