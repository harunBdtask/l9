<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Pre Costing Report</h4>
    @includeIf('merchandising::pre-costing.table')
    <div style="margin-top: 50px;">
        <table class="borderless">
            <tbody>
            <tr>
                <td class="text-center"><u>Prepared By</u></td>
                <td class='text-center'><u>Checked By</u></td>
                <td class="text-center"><u>Approved By</u></td>
            </tr>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
