<div class="padding">
  <div class="row">
    <div class="col-sm-6 col-md-6">
      <div class="box p-a">
        <div class="pull-left m-r">
          <span class="w-48 rounded  accent">
          <i class="material-icons">people</i>
          </span>
        </div>
        <div class="clear">
          <h4 class="m-a-0 text-lg _300">{{ $totalBuyers }}</h4>
          <small class="text-muted">Total Buyers</small>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-6">
        <div class="box p-a">
          <div class="pull-left m-r">
            <span class="w-48 rounded primary">
              <i class="material-icons">shopping_cart</i>
            </span>
          </div>
          <div class="clear">
            <h4 class="m-a-0 text-lg _300">{{ $totalOrders }}</h4>
            <small class="text-muted">Total Orders</small>
          </div>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-6 col-md-4">
      <div class="box">
        <div class="box-header">
          <h3 class="center">Cutting Production</h3>
          <small class="center">Cutting info for last two months</small>
        </div>
        <div class="box-body">
          <table class="reportTableDashboard" width="100%">
            <thead>
              <tr>
                <th width="50%"></th>
                <th width="50%">Cutting</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th>Today</th>
                <td>{{ $protracker_data['todayCutting'] }}</td>
              </tr>
              <tr>
                <th>Last Day</th>
                <td>{{ $protracker_data['lastDayCutting'] }}</td>
              </tr>
              <tr>
                <th>This Week</th>
                <td>{{ $protracker_data['thisWeekCutting'] }}</td>
              </tr>
              <tr>
                <th>Last Week</th>
                <td>{{ $protracker_data['lastWeekCutting'] }}</td>
              </tr>
              <tr>
                <th>This Month</th>
                <td>{{ $protracker_data['thisMonthCutting'] }}</td>
              </tr>
              <tr>
                <th>Last Month</th>
                <td>{{ $protracker_data['lastMonthCutting'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="box">
        <div class="box-header">
          <h3 class="center">Print Sent & Received Status</h3>
          <small class="center">Print sent info for last two month</small>
        </div>
        <div class="box-body">
          <table class="reportTableDashboard" id="print-sent">
            <tbody>
              <tr>
                <th width="40%"></th>
                <th width="30%">Sent</th>
                <th width="30%">Received</th>
              </tr>
              <tr>
                <th>Today</th>
                <td>{{ $protracker_data['todayPrintSent'] }}</td>
                <td>{{ $protracker_data['todayPrintReceived'] }}</td>
              </tr>
              <tr>
                <th>Last Day</th>
                <td>{{ $protracker_data['lastDayPrintSent'] }}</td>
                <td>{{ $protracker_data['lastDayPrintReceived'] }}</td>
              </tr>
              <tr>
                <th>This Week</th>
                <td>{{ $protracker_data['thisWeekPrintSent'] }}</td>
                <td>{{ $protracker_data['thisWeekPrintReceived'] }}</td>
              </tr>
              <tr>
                <th>Last Week</th>
                <td>{{ $protracker_data['lastWeekPrintSent'] }}</td>
                <td>{{ $protracker_data['lastWeekPrintReceived'] }}</td>
              </tr>
              <tr>
                <th>This Month</th>
                <td>{{ $protracker_data['thisMonthPrintSent'] }}</td>
                <td>{{ $protracker_data['thisMonthPrintReceived'] }}</td>
              </tr>
              <tr>
                <th>Last Month</th>
                <td>{{ $protracker_data['lastMonthPrintSent'] }}</td>
                <td>{{ $protracker_data['lastMonthPrintReceived'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="box">
        <div class="box-header">
          <h3 class="center">Embroidery Sent & Received Status</h3>
          <small class="center">Embroidery sent & received info for last two month</small>
        </div>
        <div class="box-body">
          <table class="reportTableDashboard" id="print-sent">
            <tbody>
              <tr>
                <th width="40%"></th>
                <th width="30%">Sent</th>
                <th width="30%">Received</th>
              </tr>
              <tr>
                <th>Today</th>
                <td>{{ $protracker_data['todayEmbrSent'] }}</td>
                <td>{{ $protracker_data['todayEmbrReceived'] }}</td>
              </tr>
              <tr>
                <th>Last Day</th>
                <td>{{ $protracker_data['lastDayEmbrSent'] }}</td>
                <td>{{ $protracker_data['lastDayEmbrReceived'] }}</td>
              </tr>
              <tr>
                <th>This Week</th>
                <td>{{ $protracker_data['thisWeekEmbrSent'] }}</td>
                <td>{{ $protracker_data['thisWeekEmbrReceived'] }}</td>
              </tr>
              <tr>
                <th>Last Week</th>
                <td>{{ $protracker_data['lastWeekEmbrSent'] }}</td>
                <td>{{ $protracker_data['lastWeekEmbrReceived'] }}</td>
              </tr>
              <tr>
                <th>This Month</th>
                <td>{{ $protracker_data['thisMonthEmbrSent'] }}</td>
                <td>{{ $protracker_data['thisMonthEmbrReceived'] }}</td>
              </tr>
              <tr>
                <th>Last Month</th>
                <td>{{ $protracker_data['lastMonthEmbrSent'] }}</td>
                <td>{{ $protracker_data['lastMonthEmbrReceived'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>     
  </div>
   <div class="row print-sent-received">
    <div class="col-sm-6 col-md-4">
      <div class="box">
        <div class="box-header">
          <h3 class="center">Sewing Input & Output Status</h3>
          <small class="center">Line sewing input info for last two months</small>
        </div>
        <div class="box-body">
          <table class="reportTableDashboard" id="sewing-input">
            <thead>
              <tr>
                <th width="40%"></th>
                <th width="30%">Input</th>
                <th width="30%">Output</th>
              </tr>
            </thead>
            <tbody>                
              <tr>
                <th>Today</th>
                <td>{{ $protracker_data['todayInput'] }}</td>
                <td>{{ $protracker_data['todayOutput'] }}</td>
              </tr>
              <tr>
                <th>Last Day</th>
                <td>{{ $protracker_data['lastDayInput'] }}</td>
                <td>{{ $protracker_data['lastDayOutput'] }}</td>
              </tr>
              <tr>
                <th>This Week</th>
                <td>{{ $protracker_data['thisWeekInput'] }}</td>
                <td>{{ $protracker_data['thisWeekOutput'] }}</td>
              </tr>
              <tr>
                <th>Last Week</th>
                <td>{{ $protracker_data['lastWeekInput'] }}</td>
                <td>{{ $protracker_data['lastWeekOutput'] }}</td>
              </tr>
              <tr>
                <th>This Month</th>
                <td>{{ $protracker_data['thisMonthInput'] }}</td>
                <td>{{ $protracker_data['thisMonthOutput'] }}</td>
              </tr>
              <tr>
                <th>Last Month</th>
                <td>{{ $protracker_data['lastMonthInput'] }}</td>
                <td>{{ $protracker_data['lastMonthOutput'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div> 
    <div class="col-sm-6 col-md-4">
      <div class="box">
        <div class="box-header">
          <h3 class="center">Washing Sent & Received Status</h3>
          <small class="center">Washing sent & received info for last two months</small>
        </div>
        <div class="box-body">
          <table class="reportTableDashboard" id="washing-sent">
            <thead>
              <tr>
                <th width="40%"></th>
                <th width="30%">Sent</th>
                <th width="30%">Received</th>
              </tr>
            </thead>
            <tbody>
              <tr>                
                <th>Today</th>
                <td>{{ $protracker_data['todayWashingSent'] }}</td>
                <td>{{ $protracker_data['todayWashingReceived'] }}</td>
              </tr>
              <tr>
                <th>Last Day</th>
                <td>{{ $protracker_data['lastDayWashingSent'] }}</td>
                <td>{{ $protracker_data['lastDayWashingReceived'] }}</td>
              </tr>
              <tr>
                <th>This Week</th>
                <td>{{ $protracker_data['thisWeekWashingSent'] }}</td>
                <td>{{ $protracker_data['thisWeekWashingReceived'] }}</td>
              </tr>
              <tr>
                <th>Last Week</th>
                <td>{{ $protracker_data['lastWeekWashingSent'] }}</td>
                <td>{{ $protracker_data['lastWeekWashingReceived'] }}</td>
              </tr>
              <tr>
                <th>This Month</th>
                <td>{{ $protracker_data['thisMonthWashingSent'] }}</td>
                <td>{{ $protracker_data['thisMonthWashingReceived'] }}</td>
              </tr>
              <tr>
                <th>Last Month</th>
                <td>{{ $protracker_data['lastMonthWashingSent'] }}</td>
                <td>{{ $protracker_data['lastMonthWashingReceived'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="box">
        <div class="box-header">
          <h3 class="center">Finishing & Shipment Status</h3>
          <small class="center">Finishing & shipment info for last two months</small>
        </div>
        <div class="box-body">
          <table class="reportTableDashboard" id="washing-sent">
            <thead>
              <tr>
                <th width="40%"></th>
                <th width="30%">Finishing</th>
                <th width="30%">Shipment</th>
              </tr>
            </thead>
            <tbody>
              <tr>                
                <th>Today</th>
                <td>{{ $protracker_data['todayFinishing'] }}</td>
                <td>{{ $protracker_data['todayShipQty'] }}</td>
              </tr>
              <tr>
                <th>Last Day</th>
                <td>{{ $protracker_data['lastDayFinishing'] }}</td>
                <td>{{ $protracker_data['lastDayShipQty'] }}</td>
              </tr>
              <tr>
                <th>This Week</th>
                <td>{{ $protracker_data['thisWeekFinishing'] }}</td>
                <td></span>{{ $protracker_data['thisWeekShipQty'] }}</td>
              </tr>
              <tr>
                <th>Last Week</th>
                <td>{{ $protracker_data['lastWeekFinishing'] }}</td>
                <td>{{ $protracker_data['lastWeekShipQty'] }}</td>
              </tr>
              <tr>
                <th>This Month</th>
                <td>{{ $protracker_data['thisMonthFinishing'] }}</td>
                <td>{{ $protracker_data['thisMonthShipQty'] }}</td>
              </tr>
              <tr>
                <th>Last Month</th>
                <td>{{ $protracker_data['lastMonthFinishing'] }}</td>
                <td>{{ $protracker_data['lastMonthShipQty'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8 col-md-8">
      <div class="box">
        <div class="box-header">
          <h3 class="center">Rejection Status</h3>
          <small class="center">Rejection info for last two months</small>
        </div>
        <div class="box-body">
          <table class="reportTableDashboard" id="allrejection">
            <thead>
              <tr>
                <th></th>
                <th>Fabric</th>
                <th>Print</th>
                <th>Embroidery</th>
                <th>Sewing</th>
                <th>Washing</th>
                <th>Finishing</th>
              </tr>
            </thead>
            <tbody class="all-rejection">
                <tr>
                    <th>Today</th>
                    <td>{{ $protracker_data['todayCuttingRejection'] }}</td>
                    <td>{{ $protracker_data['todayPrintRejection'] }}</td>
                    <td>{{ $protracker_data['todayEmbrRejection'] }}</td>
                    <td>{{ $protracker_data['todaySewingRejection'] }}</td>
                    <td>{{ $protracker_data['todayWashingRejection'] }}</td>
                    <td>{{ $protracker_data['todayFinishingRejection'] }}</td>
                </tr>
                <tr>
                    <th>Last Day</th>
                    <td>{{ $protracker_data['lastDayCuttingRejection'] }}</td>
                    <td>{{ $protracker_data['lastDayPrintRejection'] }}</td>
                    <td>{{ $protracker_data['lastDayEmbrRejection'] }}</td>
                    <td>{{ $protracker_data['lastDaySewingRejection'] }}</td>
                    <td>{{ $protracker_data['lastDayWashingRejection'] }}</td>
                    <td>{{ $protracker_data['lastDayFinishingRejection'] }}</td>
                </tr>
                <tr>
                    <th>This Week</th>
                    <td>{{ $protracker_data['thisWeekCuttingRejection'] }}</td>
                    <td>{{ $protracker_data['thisWeekPrintRejection'] }}</td>
                    <td>{{ $protracker_data['thisWeekEmbrRejection'] }}</td>
                    <td>{{ $protracker_data['thisWeekSewingRejection'] }}</td>
                    <td>{{ $protracker_data['thisWeekWashingRejection'] }}</td>
                    <td>{{ $protracker_data['thisWeekFinishingRejection'] }}</td>
                </tr>
                <tr>
                    <th>Last Week</th>
                    <td>{{ $protracker_data['lastWeekCuttingRejection'] }}</td>
                    <td>{{ $protracker_data['lastWeekPrintRejection'] }}</td>
                    <td>{{ $protracker_data['lastWeekEmbrRejection'] }}</td>
                    <td>{{ $protracker_data['lastWeekSewingRejection'] }}</td>
                    <td>{{ $protracker_data['lastWeekWashingRejection'] }}</td>
                    <td>{{ $protracker_data['lastWeekFinishingRejection'] }}</td>
                </tr>
                <tr>
                    <th>This Month</th>
                    <td>{{ $protracker_data['thisMonthCuttingRejection'] }}</td>
                    <td>{{ $protracker_data['thisMonthPrintRejection'] }}</td>
                    <td>{{ $protracker_data['thisMonthEmbrRejection'] }}</td>
                    <td>{{ $protracker_data['thisMonthSewingRejection'] }}</td>
                    <td>{{ $protracker_data['thisMonthWashingRejection'] }}</td>
                    <td>{{ $protracker_data['thisMonthFinishingRejection'] }}</td>
                </tr>
                <tr>
                    <th>Last Month</th>
                    <td>{{ $protracker_data['lastMonthCuttingRejection'] }}</td>
                    <td>{{ $protracker_data['lastMonthPrintRejection'] }}</td>
                    <td>{{ $protracker_data['lastMonthEmbrRejection'] }}</td>
                    <td>{{ $protracker_data['lastMonthSewingRejection'] }}</td>
                    <td>{{ $protracker_data['lastMonthWashingRejection'] }}</td>
                    <td>{{ $protracker_data['lastMonthFinishingRejection'] }}</td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
  /* table style */
  .reportTableDashboard {
      margin-bottom: 1rem;
      width: 100%;
      max-width: 100%;
  }
  .reportTableDashboard thead,
  .reportTableDashboard tbody,
  .reportTableDashboard th {
      padding: 3px;
      font-size: 12px;
      text-align: center;       
  }
  .reportTableDashboard th,
  .reportTableDashboard td {
      border: 1px solid #e7e7e7;
      padding: 0.1rem;
  }
  .box-header {
    padding-top: 1rem !important;padding-right: 1rem;padding-bottom: 0rem !important; padding-left: 1rem;
    
  }
  .center {
    text-align: center;
  }
  .box-body {
    padding-top: 3px !important;
  }
</style>

