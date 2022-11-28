@php
    $swingDropletsReport = [
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
        'get-challans-by-bundlecard',
        'individual-bundle-scan-check',
        'sewing-line-plan-report',
        'booking-balance-bundle-scan-check',
    ];
@endphp
<li class={{ setMultipleActiveClass($swingDropletsReport) }}>
    <a href="#">
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square-o"></i>
        </span>
        <span class="nav-text">{{ (getDept() == 'report-viewer' || getDept() == 'sewing-output') ? 'Sewing' : '' }} Reports</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('all-orders-sewing-output-summary') }}>
            <a href="{{ url('/all-orders-sewing-output-summary') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">All PO's Summary</span>
            </a>
        </li>
        <li class={{ setActiveClass('buyer-wise-sewing-output') }}>
            <a href="{{ url('/buyer-wise-sewing-output') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Buyer Wise Output</span>
            </a>
        </li>
        {{-- <li class={{ setActiveClass('order-wise-sewing-output') }}>
            <a href="{{ url('/order-wise-sewing-output') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Our Ref &amp; PO Wise Output</span>
            </a>
        </li> --}}
        <li class={{ setActiveClass('booking-no-po-and-color-report') }}>
            <a href="{{ url('/booking-no-po-and-color-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Style/Order, PO &amp; Color Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('floor-line-wise-sewing-report') }}>
            <a href="{{ url('/floor-line-wise-sewing-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Floor &amp; Line Wise Report</span>
            </a>
        </li>
        {{-- <li class={{ setActiveClass('line-wise-sewing-input-output') }}>
            <a href="{{ url('/line-wise-sewing-input-output') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Line Wise Report</span>
            </a>
        </li> --}}
        <li class={{ setActiveClass('line-wise-hourly-sewing-output') }}>
            <a href="{{ url('/line-wise-hourly-sewing-output') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Line Wise Hr Prod.</span>
            </a>
        </li>
        <li class={{ setActiveClass('date-wise-hourly-sewing-output') }}>
            <a href="{{ url('/date-wise-hourly-sewing-output') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Hr Prod.</span>
            </a>
        </li>
        <li class={{ setActiveClass('date-wise-sewing-output') }}>
            <a href="{{ url('/date-wise-sewing-output') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Output</span>
            </a>
        </li>
        <li class={{ setActiveClass('daily-input-output-report') }}>
            <a href="{{ url('/daily-input-output-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Daily Input Output Summary</span>
            </a>
        </li>
        <li class={{ setActiveClass('monthly-line-wise-production-summary-report') }}>
            <a href="{{ url('/monthly-line-wise-production-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Monthly Production Summary</span>
            </a>
        </li>
        <li class={{ setActiveClass('line-date-wise-output-avg') }}>
            <a href="{{ url('/line-date-wise-output-avg') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Line & Date Wise Avg.</span>
            </a>
        </li>
        <li class={{ setActiveClass('sewing-line-plan-report') }}>
            <a href="{{ url('/sewing-line-plan-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Sewing Line Plan Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('production-dashboard') }}>
            <a href="{{ url('/production-dashboard') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Production on Graph</span>
            </a>
        </li>
        <li class={{ setActiveClass('production-board') }}>
            <a href="{{ url('/production-board') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Production Board(HOP)</span>
            </a>
        </li>
        <li class={{ setActiveClass('bundle-wise-qc') }}>
            <a href="{{ url('/bundle-wise-qc') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Bundle Wise QC</span>
            </a>
        </li>
        <li class={{ setActiveClass('get-challans-by-bundlecard') }}>
            <a href="{{ url('/get-challans-by-bundlecard') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Get Challans By Bundlecard</span>
            </a>
        </li>
        <li class={{ setActiveClass('individual-bundle-scan-check') }}>
            <a href="{{ url('/individual-bundle-scan-check') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Individual Bundle Scan Check</span>
            </a>
        </li>
        <li class={{setActiveClass('booking-balance-bundle-scan-check')}}>
            <a href="{{ url('/booking-balance-bundle-scan-check') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Booking Balance Bundle Check</span>
            </a>
        </li>
    </ul>
</li>
