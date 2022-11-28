{{--@if( count(collect($pos)->pluck('style')->unique())  <= 1)--}}
{{--    <div class="col-md-6 col-lg-offset-3">--}}
{{--        <table class="borderless">--}}
{{--            <tr>--}}
{{--                <th>Buyer:</th>--}}
{{--                <td>{{ collect($pos)->pluck('buyer')->unique()->join(' ') }}</td>--}}
{{--                <td rowspan="3">--}}
{{--                    @php--}}
{{--                        $images = collect($pos[0])['images'];--}}
{{--                        $imagePath = $images ? $images[0] : null;--}}
{{--                    @endphp--}}
{{--                    @if($imagePath && File::exists('storage/'.$imagePath))--}}
{{--                        <img src="{{asset('storage/'. $imagePath)}}" alt=""--}}
{{--                             height="90" width="90">--}}
{{--                    @else--}}
{{--                        <img src="{{ asset('images/no_image.jpg') }}" height="90" width="90"--}}
{{--                             alt="no image">--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>Style:</th>--}}
{{--                <td>{{ collect($pos)->pluck('style')->unique()->join(' ') }}</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>Item:</th>--}}
{{--                <td>{{ collect($pos)->pluck('item')->unique()->join(' ') }}</td>--}}
{{--            </tr>--}}
{{--        </table>--}}
{{--    </div>--}}
{{--@endif--}}


@if(isset($pdf))
    @includeIf('merchandising::order.report.color_size_breakdown_details_breakdown_pdf')
@else
    @includeIf('merchandising::order.report.color_size_breakdown_details_breakdown')
@endif
