<table class="borderless">
    <tr id="pdfGenerateInfo">
        <td rowspan="4">
            @if(factoryImage() && File::exists('storage/factory_image/'.factoryImage()))
                <img
                    src="{{ asset('storage/factory_image/'. factoryImage()) }}"
                    alt="Logo" style="min-width:100px;max-width:200px;max-height:200px;">
            @else
                <img src="{{ asset('images/no_image.jpg') }}" width="100"
                     alt="no image">
            @endif
        </td>
    </tr>
    <tr id="pdfGenerateInfo" style="text-align: center;">
        <td style="font-size: 12px;color: black;">
            <strong>{{ 'Report: '.$name ?? null}}</strong>
        </td>
        <td style="text-align: right;width: 30%;font-size: 10px; color: black;">
            Print By- {{ auth()->user()->first_name }}
        </td>
    </tr>
    <tr id="pdfGenerateInfo">
        <td style="text-align: center;font-size: 12px;color: black;">
            {{ factoryName() }}
        </td>
        <td style="text-align: right;font-size: 10px; color: black;">
            Print Date- {{ date('d-m-Y H:i') }}
        </td>
    </tr>
    <tr id="pdfGenerateInfo">
        <td style="text-align: center;font-size: 10px; color: black;">
            {{ factoryAddress() }}
        </td>
        <td>
        </td>
    </tr>
</table>
<hr style="background: black">
<br>
