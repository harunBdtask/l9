<table>
    <thead>
    <tr>
        <td></td>
        <td>COLOR CHART</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>STYLE</td>
        <td>{{ $style }}</td>
    </tr>
    <tr>
        <td>SET COUNT</td>
        <td>{{ $creeper_count }}</td>
    </tr>
    <tr>
        <td>LEAGUE</td>
        <td>NCAA</td>
    </tr>
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <td></td>
        @for($i = 1; $i <= $creeper_count; $i++)
            <td colspan="{{ count($body_part_count) }}">
                <b>
                    PIECE - {{ $i }}
                </b>
            </td>
        @endfor

    </tr>
    <tr>
        <td></td>
        @for($i = 1; $i <= ($creeper_count * count($body_part_count)); $i++)
            <td></td>
        @endfor
    </tr>
    <tr>
        <td>
            <b>
                TEAM
            </b>
        </td>
        @for($i = 1; $i <= $creeper_count; $i++)
            @foreach($body_part_count as $body_part)
                <td>
                    <b>{{ $body_part }}</b>
                </td>
            @endforeach
        @endfor
    </tr>
    </thead>
    <tbody>
    @foreach($color_values as $color)
        <tr>
            <td>{{ $color['name'] }}</td>
            @for($i = 1; $i <= ($creeper_count * count($body_part_count)); $i++)
                <td></td>
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>
