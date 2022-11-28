<table>
  <tr>
    <th colspan="15">{{ sessionFactoryName() }}</th>
  </tr>
  <tr>
    <th colspan="15">{{ sessionFactoryAddress() }}</th>
  </tr>
  <tr>
    <th colspan="15">Date Wise Cutting Report | Repoet Date {{ date('d M, Y', strtotime($date)) }}</th>
  </tr>
  @include('cuttingdroplets::reports.tables.v2.date_wise_cutting_report_table')
</table>