@php
    $swingDroplets = [
        'sewing-output-scan',
        'all-orders-sewing-output-summary',
        'buyer-wise-sewing-output',
        //'order-wise-sewing-output',
        'booking-no-po-and-color-report',
        'floor-line-wise-sewing-report',
        'line-wise-sewing-input-output',
        'line-wise-hourly-sewing-output',
        'date-wise-hourly-sewing-output',
        'date-wise-sewing-output',
        'line-date-wise-output-avg',
        'production-dashboard',
        'production-board',
        'bundle-wise-qc',
        'daily-input-output-report',
        'monthly-line-wise-production-summary-report',
        'sewing-line-plan-report',
        'sewingoutput-challan-list',
        'booking-balance-bundle-scan-check',
    ];
@endphp

<li class="{{ setMultipleActiveClass($swingDroplets) }}">
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">Sewing Droplets</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('sewing-output-scan') }}>
            <a href="{{ url('/sewing-output-scan') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Sewing Output Scan</span>
            </a>
        </li>
        <li class={{ setActiveClass('sewingoutput-challan-list') }}>
            <a href="{{ url('/sewingoutput-challan-list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Sewing Challan List</span>
            </a>
        </li>
        @includeIf('sewingdroplets::pages.sewing_droplets_reports_nav')
    </ul>
</li>