@php
    $cuttingDropletsReports = [
        'all-orders-cutting-report',
        'buyer-wise-cutting-report',
        'order-wise-cutting-report',
        'color-wise-cutting-summary',
        'excess-cutting-report',
        'daily-cutting-report',
        'date-wise-cutting-report',
        'month-wise-cutting-report',
        'cutting-no-wise-cutting-report',
        'lot-wise-cutting-report',
        'bundle-scan-check',
        'booking-no-po-and-color-report',
        'monthly-table-wise-cutting-production-summary-report',
        'buyer-style-wise-fabric-consumption-report',
        'daily-fabric-consumption-report',
        'monthly-fabric-consumption-report',
        'consumption-report',
        'floor-line-wise-cutting-report',
        'cutting-production-summary-report',
    ];
@endphp
<li class={{ setMultipleActiveClass($cuttingDropletsReports) }}>
    <a href="#">
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square-o"></i>
        </span>
        <span class="nav-text">{{ (getDept() == 'report-viewer' || getDept() == 'cutting') ? 'Cutting' : '' }} Reports</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('all-orders-cutting-report') }}>
            <a href="{{ url('/all-orders-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">All PO's Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('buyer-wise-cutting-report') }}>
            <a href="{{ url('/buyer-wise-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Buyer Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('booking-no-po-and-color-report') }}>
            <a href="{{ url('/booking-no-po-and-color-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Style, PO &amp; Color Wise Report</span>
            </a>
        </li>
        {{-- <li class={{ setActiveClass('order-wise-cutting-report') }}>
            <a href="{{ url('/order-wise-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order &amp; PO &amp; Color Wise Report</span>
            </a>
        </li> --}}
        {{-- <li class={{ setActiveClass('color-wise-cutting-summary') }}>
            <a href="{{ url('/color-wise-cutting-summary') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Color Wise Report</span>
            </a>
        </li> --}}
        <li class={{ setActiveClass('excess-cutting-report') }}>
            <a href="{{ url('/excess-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Excess Cutting Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('daily-cutting-report') }}>
            <a href="{{ url('/daily-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Daily Cutting Report </span>
            </a>
        </li>
        <li class={{ setActiveClass('date-wise-cutting-report') }}>
            <a href="{{ url('/date-wise-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('month-wise-cutting-report') }}>
            <a href="{{ url('/month-wise-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Month Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('monthly-table-wise-cutting-production-summary-report') }}>
            <a href="{{ url('/monthly-table-wise-cutting-production-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Monthly Table Wise Cutting Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('cutting-no-wise-cutting-report') }}>
            <a href="{{ url('/cutting-no-wise-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Cutting Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('lot-wise-cutting-report') }}>
            <a href="{{ url('/lot-wise-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Lot Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('consumption-report') }}>
            <a href="{{ url('/consumption-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Consumption Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('buyer-style-wise-fabric-consumption-report') }}>
            <a href="{{ url('/buyer-style-wise-fabric-consumption-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Buyer Booking Wise Fabric Consumption</span>
            </a>
        </li>
        <li class={{ setActiveClass('daily-fabric-consumption-report') }}>
            <a href="{{ url('/daily-fabric-consumption-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Daily Fabric Consumption</span>
            </a>
        </li>
        <li class={{ setActiveClass('monthly-fabric-consumption-report') }}>
            <a href="{{ url('/monthly-fabric-consumption-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Monthly Fabric Consumption</span>
            </a>
        </li>
        <li class={{setActiveClass('bundle-scan-check')}}>
            <a href="{{ url('/bundle-scan-check') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Bundle Scan Check</span>
            </a>
        </li>
        <li class={{setActiveClass('floor-line-wise-cutting-report')}}>
            <a href="{{ url('/floor-line-wise-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Line Wise Cutting Inhand Report</span>
            </a>
        </li>

        <li class={{setActiveClass('cutting-production-summary-report')}}>
            <a href="{{ url('/cutting-production-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Daily Table Wise Cutting & Input Summary</span>
            </a>
        </li>
        <!-- <li>
            <a href="{{ url('/order-wise-qc-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order Wise Qc Report</span>
            </a>
        </li> -->
        <!-- <li>
            <a href="{{ url('/buyer-wise-qc-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Buyer Wise Qc Report</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/month-wise-qc-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Month Wise Qc Report</span>
            </a>
        </li> -->
    </ul>
</li>