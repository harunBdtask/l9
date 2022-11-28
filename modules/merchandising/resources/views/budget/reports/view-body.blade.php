<div class="">
    <center>
        <table style="border: 1px solid black;width: 20%;">
            <thead>
            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt; font-weight: bold;">Budget</span>
                    <br>
                </td>
            </tr>
            </thead>
        </table>
    </center>
    <br>

    @include('merchandising::budget.reports.view-body-section-table',['type' => 'view'])
    @include('skeleton::reports.downloads.signature')
    @include('skeleton::reports.downloads.footer')
</div>
