<table class="borderless" style="width: 100%">
    <tr>
        <td>
            <h6 class="text-left">{{ $program->program_no }}</h6>
        </td>
        <td>
            <a style="font-size: 10px; float: right; margin-bottom: 0.5rem;"
               class="btn btn-info btn-xs float-right"
               href="/knitting/program/fabric-color/{{ $program->id }}/{{ $program->plan_info_id }}?program_no={{ $program->program_no }}">
                Proceed to Fabric Color
            </a>
        </td>
    </tr>
</table>


<table class="reportTable">
    <tr>
        <th>Color</th>
        <th>Qty</th>
    </tr>
    @forelse($program->knitting_program_colors_qtys as $value)
        <tr>
            <td>{{ $value->item_color }}</td>
            <td>{{ $value->program_qty }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2">No color found!</td>
        </tr>
    @endforelse
</table>
