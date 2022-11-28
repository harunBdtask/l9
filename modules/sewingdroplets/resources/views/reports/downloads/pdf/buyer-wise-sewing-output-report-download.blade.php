<!DOCTYPE html>

<html>
<head>
  <title>Report</title>
  @include('reports.downloads.includes.pdf-styles')
  <style>
    .reportTable thead,
    .reportTable tbody,
    .reportTable th {
      font-size: 7px;
    }

    .reportTable th,
    .reportTable td {
      font-size: 7px;
    }

    .table td, .table th {
      font-size: 7px;
    }
  </style>
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

  <h4 align="center">Buyer Wise Sewing Output Report || {{ date("jS F, Y") }}</h4>
  <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
    <tr>
      <th>Order/Style</th>
      <th>PO</th>
      <th>Order Qty</th>
      <th>Cutt. Qty</th>
      <th>WIP In<br/>Cutt./Pt./Embr.</th>
      <th>Print Sent</th>
      <th>Print Rcv</th>
      <th>Print WIP</th>
      <th>Today's Input</th>
      <th>Total Input</th>
      <th>Today's Output</th>
      <th>Total Output</th>
      <th>Sewing Rejection</th>
      <th>Total Rejection</th>
      <th>In_line WIP</th>
      <th>Cut 2 Sewing Ratio (%)</th>
    </tr>
    </thead>
    <tbody>
    @include('sewingdroplets::reports.includes.buyer_wise_report_inc')
    </tbody>
  </table>

</main>
</body>
</html>
