<!DOCTYPE html>
<html>
<head>
    <title>Bank Book Details</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Bank Book Details Report</h4>
    @includeIf('basic-finance::reports.bank-management.bank-book.table')
    <div style="margin-top: 16mm">
        <table class="borderless">
            <tbody>
            <tr>
                <td class="text-center" style="border: 1px solid transparent !important;"><u>Prepared By</u></td>
                <td class='text-center' style="border: 1px solid transparent !important;"><u>Checked By</u></td>
                <td class='text-center' style="border: 1px solid transparent !important;"><u>Audit Department</u></td>
                <td class='text-center' style="border: 1px solid transparent !important;"><u>Manager (Account)</u></td>
                <td class="text-center" style="border: 1px solid transparent !important;"><u>Authorized By</u></td>
            </tr>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
