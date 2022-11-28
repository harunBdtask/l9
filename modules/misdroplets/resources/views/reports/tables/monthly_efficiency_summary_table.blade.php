<thead>
    <tr>
        <th>Floor</th>
        <th>Line</th>     
        @for ($i = 1; $i <= $daysOfMonth; $i++)
          <th>{{ $i.'-'.$monthName }}</th>
        @endfor
        <th>Floor Eff.</th>
        <th>Factory Eff.</th>
    </tr>
</thead>
<tbody>
    @php 
        $totalUsedMin = 0;
        $totalProducedMin = 0;
        $f_key = 0;
    @endphp
    
    @foreach($lines->groupBy('floor_id') as $groupByFloor)
        @php 
            $totalFloorUsedMin = 0;
            $totalFloorProducedMin = 0;
            $l_key = 0;
            $groupByFloor->each(function($line) use(&$totalFloorUsedMin, &$totalFloorProducedMin) {
              $efficiencyData = collect($line->efficiencyData);
              $totalFloorUsedMin = $efficiencyData->sum('used_minutes');
              $totalFloorProducedMin = $efficiencyData->sum('produced_minutes');
            })
        @endphp        

        <tr>
            <td rowspan="{{ count($groupByFloor) }}" style="background-color: #75ade1;">
                {{ $groupByFloor->first()->floor_no ?? 'N/A' }}
            </td>
            @foreach($groupByFloor as $line)
              @if($l_key > 0)
                <tr>
              @endif
              <td>{{ $line->line_no }}</td>
              @foreach($line->efficiencyData as $effData)
                  <td>{{ $effData->line_efficiency ?? '0'  }}&#37;</td>         
              @endforeach
              @if($l_key == 0)
              <td rowspan="{{ count($groupByFloor) + 1 }}" style="background-color: #EBF1DE;">
                {{ ($totalFloorUsedMin > 0) ? number_format(($totalFloorProducedMin / $totalFloorUsedMin) * 100, 2) : '0' }}&#37;
              </td>
              @endif
              @if($l_key == 0 && $f_key == 0)
              <td rowspan="{{ count($lines) + $lines->groupBy('floor_id')->count() }}" style="background-color: #75ade1;">
                {{ $factoryEfficiency }}&#37;
              </td>
              @endif
              @php
                $l_key++;
              @endphp
            </tr>
            @endforeach
        </tr>
        <tr>
            <td style="height: 5px; background-color: #75ade1 !important" colspan="{{ $daysOfMonth + 3 }}"></td>
        </tr>
    @php
      $f_key++;
    @endphp
    @endforeach
</tbody>


<style type="text/css">
    .rotate {        
        /* Safari */
        -webkit-transform: rotate(-90deg);
        /* Firefox */
        -moz-transform: rotate(-90deg);
        /* IE */
        -ms-transform: rotate(-90deg);
        /* Opera */
        -o-transform: rotate(-90deg);
        /* Internet Explorer */
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }
</style>
