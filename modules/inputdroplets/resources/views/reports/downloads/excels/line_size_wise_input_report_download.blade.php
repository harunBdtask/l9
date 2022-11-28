<table>
  <tr>
    <th colspan="{{ count($size_ids) + 8 }}">{{ sessionFactoryName() }}</th>
  </tr>
  <tr>
    <th colspan="{{ count($size_ids) + 8 }}">{{ sessionFactoryAddress() }}</th>
  </tr>
  @includeIf('inputdroplets::reports.tables.line_size_wise_input_report_table')
</table>