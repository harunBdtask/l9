<table class="borderless">
    <tbody>
    <tr>
        @if(isset($signature))
            <td class="text-center">
                @if($signature['created_by']['signature'] && File::exists('storage/'.$signature['created_by']['signature']))
                    <img src="{{ asset('storage/'. $signature['created_by']['signature']) }}"
                         width="200px" height="50px" alt="signature">
                    <br>
                @endif
                {{ $signature['created_by']['full_name'] ?? null }}
                <br>
                {{ $signature['created_by']['designation'] ?? null }}
                <br>
                {{ $signature['created_by']->departmnt->department_name }}
                <br>
                {{ $signature['created_at'] }}
            </td>
            @foreach(collect($signature['details']) as $detail)
                <td class="text-center">
                    @if($detail['signature'] && File::exists('storage/'.$detail['signature']))
                        <img src="{{ asset('storage/'. $detail['signature']) }}"
                             width="200px" height="50px" alt="signature">
                        <br>
                    @endif
                    {{ $detail['full_name'] }}
                    <br>
                    {{ $detail['designation'] }}
                    <br>
                    {{ $detail['department'] }}
                    <br>
                    {{ $detail['date_time'] }}
                </td>
            @endforeach
        @endif
    </tr>
    <tr>
        @if(isset($signature))
            <td class="text-center"><span style="border-top: 0.15rem solid #3D3D3D; padding: 0 5px;">Prepared By</span>
            </td>
            @foreach(collect($signature['details']) as $detail)
                @if($loop->last)
                    <td class="text-center"><span
                            style="border-top: 0.15rem solid #3D3D3D; padding: 0 5px;">Checked By</span></td>
                @else
                    <td class="text-center"><span
                            style="border-top: 0.15rem solid #3D3D3D; padding: 0 5px;">Approved By</span></td>
                @endif
            @endforeach
        @else
            <td class="text-center"><span style="border-top: 0.15rem solid #3D3D3D; padding: 0 5px;">Prepared By</span>
            </td>
            <td class="text-center"><span style="border-top: 0.15rem solid #3D3D3D; padding: 0 5px;">Approved By</span>
            </td>
            <td class='text-center'><span style="border-top: 0.15rem solid #3D3D3D; padding: 0 5px;">Checked By</span>
            </td>
        @endif
    </tr>
    </tbody>
</table>
