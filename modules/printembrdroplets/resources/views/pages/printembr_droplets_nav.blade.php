@php
    $printEmbrDroplets = [
        'print-send-scan',
        'bundle-received-from-print',
        'bundle-received-from-print',
        'gatepasses',
        'print-factory-receive-challan-list',
        'buyer-wise-print-send-receive-report',
        'order-wise-print-send-receive-report',
        'cutting-no-wise-color-print-send-receive-report',
        'date-wise-print-send-report',
        'bundle-scan-check',
        'booking-no-po-and-color-report'
    ];
@endphp
<li class={{ setMultipleActiveClass($printEmbrDroplets) }}>
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">Print/Embr. Droplets</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('print-send-scan') }}>
            <a href="{{ url('/print-send-scan') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Send To Print/Embr.</span>
            </a>
        </li> 

        <li class={{ setActiveClass('bundle-received-from-print') }}>
            <a href="{{ url('/bundle-received-from-print') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Receive From Print/Embr.</span>
            </a>
        </li>
        <li class={{ setActiveClass('gatepasses') }}>
            <a href="{{ url('/gatepasses') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Gatepass List</span>
            </a>
        </li>
        @includeIf('printembrdroplets::pages.printembr_droplets_reports_nav')
    </ul>
</li>