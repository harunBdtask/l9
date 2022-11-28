<div style="margin-top: 3rem;">
    @include('merchandising::gate-pass-challan.report.common_table')
    @switch($data['good_id'])
        @case(1)
            @include('merchandising::gate-pass-challan.report.sample_table')
            @break
        @case(2)
            @include('merchandising::gate-pass-challan.report.fabric_table')
            @break
        @case(3)
            @include('merchandising::gate-pass-challan.report.trims_table')
            @break
        @case(4)
            @include('merchandising::gate-pass-challan.report.yarn_table')
            @break
    @endswitch
</div>
