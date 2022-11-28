<table class="borderless">
    <tr>
        <td style="width: 25%; border: none;">
            <table style="border: none;">
                <tr id="pdfGenerateInfo" style="border: none;">
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
            </table>
        </td>
        <td style="width: 50%; border: none;">
            <table style="border: none;">
                <tr id="pdfGenerateInfo" style="text-align: center; border: none;">
                    <td style="color: black; text-align: center;">
                        @if (!empty($name))
                            <b style="font-size: 14px;">{{ "Report: ".$name ?? null}}</b> <br>
                        @endif
                        <span style="text-align: center;font-size: 13px;color: black;">
                            {{ factoryName() }}
                        </span><br>
                        <span style="text-align: right;font-size: 11px; color: black;">
                            {{ factoryAddress() }}
                        </span>
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 25%; border: none;">
            <table style="border: none;">
                <tr id="pdfGenerateInfo" style="border: none;">
                    <td style="text-align: right; width: 30%; font-size: 10px; color: black;">
                        <span style="padding-right: 5%;">
                            Printed By- {{ auth()->user()->first_name }}
                        </span><br>
                        <span style="padding-right: 5%;">
                            Print Date- {{ date('d-m-Y H:i') }}
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<hr style="background: black">
<br>
