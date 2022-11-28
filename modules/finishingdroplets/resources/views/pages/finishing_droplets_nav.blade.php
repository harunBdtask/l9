@php
    $finishingDroplets = [
        'packing-list-generate',
        'update-getup-production',
        'poly-iron-packings',
        'finishing-receieved-report',
        'order-wise-finishing-report',
        'color-wise-finishing-report',
        'date-wise-finishing-report',
        'all-orders-poly-cartoon-report',
        'date-wise-poly-cartoon-summary',
        'finishing-production-status',
        'po-shipment-status',
        'date-wise-finishing-summary-report',
        'finishing-summary-report',
    ];
@endphp
<li class={{ setMultipleActiveClass($finishingDroplets) }}>
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">Finishing Droplets</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('packing-list-generate') }}>
            <a href="{{ url('/packing-list-generate') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Packing List</span>
            </a>
        </li>
        <li class={{ setActiveClass('update-getup-production') }}>
            <a href="{{ url('/update-getup-production') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Getup Production</span>
            </a>
        </li>
        <li class={{ setActiveClass('iron-poly-packings') }}>
            <a href="{{ url('/iron-poly-packings') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Iron Poly & Packings</span>
            </a>
        </li>
        @includeIf('finishingdroplets::pages.finishing_droplets_reports_nav')
    </ul>
</li>