<table class="borderless">
    <tbody>
    <tr>
        @if(isset($signature->details))
            @foreach(collect($signature->details)->sortBy('sequence') as $detail)
                {{--                @if($detail->signature_type == 2)--}}
                <td class="text-center">
                    @if($detail->image && File::exists('storage/'.$detail->image))
                        <img src="{{ asset('storage/'. $detail->image) }}"
                             width="200px" height="50px" alt="signature">
                    @endif
                    <u><p>{!! $detail->name !!}</p></u>
                </td>
                {{--                @endif--}}
            @endforeach
        @else
            <td class="text-center"><u>Prepared By</u></td>
            <td class='text-center'><u>Checked By</u></td>
            <td class="text-center"><u>Approved By</u></td>
        @endif
    </tr>
    <tr>
        @if(isset($signature->details))
            @foreach(collect($signature->details)->sortBy('sequence') as $detail)
                <td class="text-center">{{ $date_time ?? null }}</td>
            @endforeach
        @endif
    </tr>
    <tr>
        @if(isset($signature->details))
            @foreach(collect($signature->details)->sortBy('sequence') as $detail)
                <td class="text-center">{{$detail->designation}}</td>
            @endforeach
        @endif
    </tr>
    </tbody>
</table>
