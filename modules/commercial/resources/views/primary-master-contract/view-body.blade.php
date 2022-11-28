<style>
    .v-align-top td, .v-algin-top th {
            vertical-align: top;
    }
    table, th, td {
        border: 1px solid white;
        padding-top: 0;
        margin: 0;
        vertical-align: top;
    }
    .borderless td, .borderless th {
        border: none;
    }

    .body-section .borderless td, th {
        text-align: left;
    }
</style>
<div class="box-body">
    
    <div style="margin-top: 5%; padding-right: 5%;">
        <p class="text-center"> <b> <u>Export Contract</u> </b> </p>
        <p><b>This Irrevocable Contract Made Between {{ $contracts->buyingAgent->buying_agent_name ?? '' }} {{ $contracts->buyingAgent->address ?? '' }} of {{factoryName()}} {{factoryAddress()}} Under the following terms and condition:-</b></p>
        <table style="width: 100%">
            <tbody>
                @php $i=1; @endphp
                @foreach ($dataItem as $item)
                @if(!empty($item['value']))
                
                <tr>
                    <td style="width: 10%"> <b>{{ (($i < 10)?'0'.$i++:$i++) }}</b> </td>
                    <td style="width: 20%"><b>{{ $item['title']}}</b></td>
                    <td style="width: 10%"><b>&nbsp;:&nbsp;</b></td>
                    <td><b>{!! $item['value'] !!}</b></td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <br/>
    
</div>