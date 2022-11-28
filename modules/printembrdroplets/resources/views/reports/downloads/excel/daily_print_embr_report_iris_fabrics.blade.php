<table class="reportTable">
  <thead>
    <tr colspan="21">
      <th>{{ sessionFactoryName() }}</th>
    </tr>
    <tr colspan="21">
      <th>{{ sessionFactoryAddress() }}</th>
    </tr>
    <tr>
      <th rowspan="2">Buyer</th>
      <th rowspan="2">Item</th>
      <th rowspan="2">Ref. No</th>
      <th rowspan="2">Style</th>
      <th rowspan="2">Color</th>
      <th rowspan="2">Order Qty</th>
      <th colspan="7">Print Section</th>
      <th colspan="7">Embr. Section</th>
      <th rowspan="2">Remarks</th>
    </tr>
    <tr>
      <th>Today Send</th>
      <th>Prev. Send</th>
      <th>Total Send</th>
      <th>Today Rcvd</th>
      <th>Prev. Rcvd</th>
      <th>Total Rcvd</th>
      <th>Balance</th>
      <th>Today Send</th>
      <th>Prev. Send</th>
      <th>Total Send</th>
      <th>Today Rcvd</th>
      <th>Prev. Rcvd</th>
      <th>Total Rcvd</th>
      <th>Balance</th>
    </tr>
  </thead>
  <tbody>
    @includeIf('printembrdroplets::reports.tables.daily_print_embr_report_iris_fabrics_table')
  </tbody>
</table>