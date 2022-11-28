@unless(count($data) === 0)
    <div class="row">
        @php $i = 0 @endphp
        @foreach(collect($data)->chunk(4) as $key => $chunkData)
        <div class="col-md-{{ $i % 2 == 0 ? 9 : 3 }}" style="float: left; position:relative; {{ $i % 2 == 0 ? 'width: 75%' : 'width: 25%' }}">
            <table class="borderless">
                @foreach($chunkData as $key => $value)
                    @if($value)
                    <tr>
                        <td style="padding-left: 0; width: 200px;">
                            <b>{{ ucfirst(str_replace('_', ' ', $key)) }} :</b>
                        </td>
                        <td> {{ $value }} </td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </div>
        @php $i += 1 @endphp
        @endforeach
        {{--        <div class="col-md-4"></div>--}}
        {{--        <div class="col-md-3" style="float: right; position:relative">--}}
        {{--            <table class="borderless">--}}
        {{--                @foreach($data as $key => $value)--}}
        {{--                    @if($value && ($key == 'reference_no' || $key == 'remarks'))--}}
        {{--                        <tr>--}}
        {{--                            <td style="padding-left: 0;">--}}
        {{--                                <b>{{ ucfirst(str_replace('_', ' ', $key)) }} :</b>--}}
        {{--                            </td>--}}
        {{--                            <td style="word-break: break-all;"> {{ $value }} </td>--}}
        {{--                        </tr>--}}
        {{--                    @endif--}}
        {{--                @endforeach--}}
        {{--            </table>--}}
        {{--        </div>--}}
    </div>
@endunless
