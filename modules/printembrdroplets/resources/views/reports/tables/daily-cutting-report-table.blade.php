 <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>                
      <tr>                 
        <th>Buyer</th>
        <th>Style/Order No</th>
        <th>PO</th>
        <th>Type</th>
        <th>OQ</th>
        <th>Today Cutting</th>
        <th>Total Cutting</th>
        <th>Cutting Balance</th>
        <th>Input Ready</th>
      </tr>
    </thead>
    <tbody class="color-wise-report">
      @php
          $all_oq = 0;
          $all_today_quantity = 0;
          $all_cutting_quantity = 0;
          $all_cutting_balance = 0;
      @endphp
      @if(!empty($cutting_floors))
        @foreach($cutting_floors as $floor)
          <tr style="width:30px !important">
            <td colspan="9"><span style="font-weight: bold;font-size: 14px;">{{ $floor->floor_no }}</span></td>
          </tr>
          @php
              $total_oq = 0;
              $total_today_quantity = 0;
              $total_cutting_quantity = 0;
              $total_cutting_balance = 0;
          @endphp          
          @if(!empty($floor->cutting_result))                      
            @foreach($floor->cutting_result as $report)
              @php
                  $total_oq += $report['order_quantity'];
                  $total_today_quantity += $report['today_quantity'];
                  $total_cutting_quantity += $report['cutting_quantity'];
                  $total_cutting_balance += $report['cutting_balance'];                
              @endphp
            <tr>                    
              <td>{{ $report['buyer'] }}</td>
              <td>{{ $report['style'] }}</td>
              <td>{{ $report['order'] }}</td>
              <td>{{ $report['type'] }}</td>                     
              <td>{{ $report['order_quantity'] }}</td>
              <td>{{ $report['today_quantity'] }}</td>
              <td>{{ $report['cutting_quantity'] }}</td>
              <td>{{ $report['cutting_balance'] }}</td>
              <td>{{ $report['cutting_quantity'] }}</td>
            </tr>
            @endforeach
              @php
                  $all_oq += $total_oq;
                  $all_today_quantity += $total_today_quantity;
                  $all_cutting_quantity += $total_cutting_quantity;
                  $all_cutting_balance += $total_cutting_balance;
              @endphp
            <tr style="font-weight:bold;">
              <td colspan="4">{{ $floor->floor_no ?? '' }} = Total</td>
              <td>{{ $total_oq }}</td>
              <td>{{ $total_today_quantity }}</td>
              <td>{{ $total_cutting_quantity }}</td>
              <td>{{ $total_cutting_balance }}</td>
              <td>{{ $total_cutting_quantity }}</td>
            </tr>
          @else
            <tr style="font-weight:bold;">
              <td colspan="4">{{ $floor->floor_no ?? '' }} = Total</td>
              <td>{{ $total_oq }}</td>
              <td>{{ $total_today_quantity }}</td>
              <td>{{ $total_cutting_quantity }}</td>
              <td>{{ $total_cutting_balance }}</td>
              <td>{{ $total_cutting_quantity }}</td>
            </tr>  
          @endif
        @endforeach
            <tr style="height:50px;font-size:16px; font-weight:bold;">
              <td colspan="4">Total</td>
              <td>{{ $all_oq }}</td>
              <td>{{ $all_today_quantity }}</td>
              <td>{{ $all_cutting_quantity }}</td>
              <td>{{ $all_cutting_balance }}</td>
              <td>{{ $all_cutting_quantity }}</td>
            </tr>
      @else
        <tr>
          <td colspan="5" class="text-danger text-center">Not found<td>
        </tr>
      @endif
    </tbody>     
  </table>      