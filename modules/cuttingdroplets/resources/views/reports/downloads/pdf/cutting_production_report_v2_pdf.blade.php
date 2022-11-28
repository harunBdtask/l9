<!DOCTYPE html>
<html>
<head>
    <title>Cutting Production Report V2</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Cutting Production Report V2</h4>
    <table class="reportTable" style="border-collapse: collapse;font-size:9px !important;">
      <thead>
        <tr>
          <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Buyer</td>
          <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Mer. Name</td>
          <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Item</td>
          <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Style</td>
          <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Ref. No</td>
          <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Fab. Type</td>
          <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Color</td>
          <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Order Qty</td>
          <td style="text-align: center; font-weight: bold;background-color: #a1c9ed" colspan="5">Cutting</td>
          <td style="text-align: center; font-weight: bold;background-color: aliceblue" colspan="6">Print</td>
          <td style="text-align: center; font-weight: bold;background-color: #a1c9ed" colspan="6">Embroidery
          </td>
          <td style="text-align: center; font-weight: bold;background-color: aliceblue" colspan="3">Input</td>
          <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Remarks</td>
        </tr>
        <tr>
          <th style="background-color: #a1c9ed; font-weight: bold;">Today Cut</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Total Cut</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Cut. Rej.</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Ok Cut</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Cut Blnc.</th>
          <th style="background-color: aliceblue; font-weight: bold;">Today Print Send</th>
          <th style="background-color: aliceblue; font-weight: bold;">Total Print Send</th>
          <th style="background-color: aliceblue; font-weight: bold;">Send Print Blnc.</th>
          <th style="background-color: aliceblue; font-weight: bold;">Today Print Rec.</th>
          <th style="background-color: aliceblue; font-weight: bold;">Total Print Rec.</th>
          <th style="background-color: aliceblue; font-weight: bold;">Rec. Print Blnc.</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Today Embr Send</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Total Embr Send</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Send Embr Blnc.</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Today Embr Rec.</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Total Embr Rec.</th>
          <th style="background-color: #a1c9ed; font-weight: bold;">Rec. Embr Blnc</th>
          <th style="background-color: aliceblue; font-weight: bold;">Today Input</th>
          <th style="background-color: aliceblue; font-weight: bold;">Total Input</th>
          <th style="background-color: aliceblue; font-weight: bold;">Input Blnc.</th>
        </tr>
      </thead>
      <tbody>
        @includeIf('cuttingdroplets::reports.tables.cutting_production_report_v2_table')
      </tbody>
    </table>
</main>
</body>
</html>
