<!DOCTYPE html>
<html>
<head>
    <title>Group Ledger Details</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Group Ledger Details Report</h4>
    @includeIf('basic-finance::tables.group_ledger_table')
    <div style="margin-top: 16mm">
        <table class="borderless">
            <tbody>
            <tr>
                <td class="text-center"><u>Prepared By</u></td>
                <td class='text-center'><u>Checked By</u></td>
                <td class='text-center'><u>Audit Department</u></td>
                <td class='text-center'><u>Manager (Account)</u></td>
                <td class="text-center"><u>Authorized By</u></td>
            </tr>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
