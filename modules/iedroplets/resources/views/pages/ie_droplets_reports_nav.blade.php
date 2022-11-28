@php
    $ieDropletsReport = [
        'all-orders-shipment-summary',
        'buyer-wise-shipment-report',
        'booking-no-po-and-color-report'
    ];
@endphp

<li class={{ setMultipleActiveClass($ieDropletsReport) }}>
    <a href="#">
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square-o"></i>
        </span>
        <span class="nav-text">{{ (getDept() == 'report-viewer' || getDept() == 'ie') ? 'IE' : '' }} Reports</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('booking-no-po-and-color-report') }}>
            <a href="{{ url('/booking-no-po-and-color-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Style/Order, PO &amp; Color Wise Input</span>
            </a>
        </li>
        <li class={{ setActiveClass('all-orders-shipment-summary') }}>
            <a href="{{ url('/all-orders-shipment-summary') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">All orders Shipment Summary</span>
            </a>
        </li>
        <li class={{ setActiveClass('buyer-wise-shipment-report') }}>
            <a href="{{ url('/buyer-wise-shipment-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Buyer Wise Shipment Report</span>
            </a>
        </li>

        <li class={{ setActiveClass('daily-shipment-report') }}>
            <a href="{{ url('/daily-shipment-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Daily Shipment Report</span>
            </a>
        </li>

        <li class={{ setActiveClass('daily-shipment-report') }}>
            <a href="{{ url('/overall-shipment-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Overall Shipment Report</span>
            </a>
        </li>
    </ul>
</li>
