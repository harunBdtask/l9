<!DOCTYPE html>

<html>
<head>
    <title>Order Confirmation Report</title>
    @include('merchandising::reports.downloads.includes.pdf_style')
</head>

<body>
<main>
<h4 align="center">Order Confirmation Report </h4>

@include('merchandising::reports.downloads.includes.pdf_header')
    <table class="reportTable">
        <thead>
        <tr>
            <th>Sl.</th>
            <th>Buyer</th>
            <th>Order</th>
            <th>Style</th>
            <th>PO</th>
            <th>Order Quantity</th>
            <th>Order Confirmation Date</th>
            <th>Ship Date</th>
            <th>Remarks</th>
        </tr>
        </thead>
        <tbody class="table-body">
        {!! $html !!}
        </tbody>
    </table>


</main>
@include('merchandising::reports.downloads.includes.pdf_footer')
</body>
</html>