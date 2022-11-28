@php
    $printEmbrDropletsReports =[
        'buyer-wise-print-send-receive-report',
        'order-wise-print-send-receive-report',
        'booking-no-po-and-color-report',
        'cutting-no-wise-color-print-send-receive-report',
        'date-wise-print-send-report',
        'bundle-scan-check',
    ];
@endphp
<li class={{ setMultipleActiveClass($printEmbrDropletsReports) }}>
    <a href="#">
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square-o"></i>
        </span>
        <span class="nav-text">{{ (getDept() == 'report-viewer' || getDept() == 'print-send' || getDept() == 'print-received') ? 'Print/Embr' : '' }} Reports</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('buyer-wise-print-send-receive-report') }}>
            <a href="{{ url('/buyer-wise-print-send-receive-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Buyer Wise Report</span>
            </a>
        </li>
        {{-- <li class={{ setActiveClass('order-wise-print-send-receive-report') }}>
            <a href="{{ url('/order-wise-print-send-receive-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order &amp; PO Wise Report</span>
            </a>
        </li> --}}
        <li class={{ setActiveClass('booking-no-po-and-color-report') }}>
            <a href="{{ url('/booking-no-po-and-color-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Booking No, PO &amp; Color Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('cutting-no-wise-color-print-send-receive-report') }}>
            <a href="{{ url('/cutting-no-wise-color-print-send-receive-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Cutting Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('date-wise-print-send-report') }}>
            <a href="{{ url('/date-wise-print-send-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('Received-and-delivery-status-report') }}>
            <a href="{{ url('/Received-and-delivery-status-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('bundle-scan-check') }}>
            <a href="{{ url('/bundle-scan-check') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Bundle Scan Check</span>
            </a>
        </li>
    </ul>
</li>