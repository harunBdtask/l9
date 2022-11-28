@php
    $finishingDropletsReport = [
        'finishing-receieved-report',
        'order-wise-finishing-report',
        'color-wise-finishing-report',
        'date-wise-finishing-report',
        'all-orders-poly-cartoon-report',
        'date-wise-iron-poly-packing-summary',
        'finishing-production-status',
        'po-shipment-status',
        'date-wise-finishing-summary-report',
        'finishing-summary-report',
        'style-wise-finishing-summary-report'
    ];
@endphp
<li class={{ setMultipleActiveClass($finishingDropletsReport) }}>
    <a href="#">
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square-o"></i>
        </span>
        <span class="nav-text">{{ (getDept() == 'report-viewer') ? 'Finishing' : '' }} Reports</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('finishing-receieved-report') }}>
            <a href="{{ url('/finishing-receieved-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order Wise Finishing V1</span>
            </a>
        </li>
        <li class={{ setActiveClass('order-wise-finishing-report') }}>
            <a href="{{ url('/order-wise-finishing-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order Wise Finishing V2</span>
            </a>
        </li>
        <li class={{ setActiveClass('color-wise-finishing-report') }}>
            <a href="{{ url('/color-wise-finishing-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Color Wise Finishing</span>
            </a>
        </li>
        <li class={{ setActiveClass('finishing-summary-report') }}>
            <a href="{{ url('/finishing-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Finishing Summary Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('style-wise-finishing-summary-report') }}>
            <a href="{{ url('/style-wise-finishing-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Style Wise Finishing Summary Report</span>
            </a>
        </li>
       {{-- <li class={{ setActiveClass('date-wise-finishing-summary-report') }}>
            <a href="{{ url('/date-wise-finishing-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Finishing Summary</span>
            </a>
        </li>--}}
        <li class={{ setActiveClass('date-wise-finishing-report') }}>
            <a href="{{ url('/date-wise-finishing-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Finishing</span>
            </a>
        </li>
        <li class={{ setActiveClass('all-orders-poly-cartoon-report') }}>
            <a href="{{ url('/all-orders-poly-cartoon-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">All Order's Poly &amp; Cartoon</span>
            </a>
        </li>
        <li class={{ setActiveClass('date-wise-iron-poly-packing-summary') }}>
            <a href="{{ url('/date-wise-iron-poly-packing-summary') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date wise Iron Poly &amp; Packing</span>
            </a>
        </li>
        <li class={{ setActiveClass('finishing-production-status') }}>
            <a href="{{ url('/finishing-production-status') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Finishing Production Status</span>
            </a>
        </li>
        <li class={{ setActiveClass('po-shipment-status') }}>
            <a href="{{ url('/po-shipment-status') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">PO &amp; Shipment Status</span>
            </a>
        </li>
    </ul>
</li>