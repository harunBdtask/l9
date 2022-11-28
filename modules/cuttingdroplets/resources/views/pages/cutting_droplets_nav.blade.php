@php
    $cuttingDroplets = [
        'bundle-card-generations',
        'bundle-card-generation-manual',
        'cutting-scan','challan-wise-bundle',
        'replace-bundle-card',
        'update-cutting-production',
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
        'cutting-requisitions',
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
<li class={{ setMultipleActiveClass($cuttingDroplets) }}>
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">Cutting Droplets</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('bundle-card-generations') }}>
            <a href="{{ url('/bundle-card-generations') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Bundle Card[Auto]</span>
            </a>
        </li>
        <li class={{ setActiveClass('bundle-card-generation-manual') }}>
            <a href="{{ url('/bundle-card-generation-manual') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Bundle Card[Manual]</span>
            </a>
        </li>
        <li class={{ setActiveClass('cutting-requisitions') }}>
            <a href="{{ url('/cutting-requisitions') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Cutting Requisitions</span>
            </a>
        </li>
        <li class={{ setActiveClass('cutting-scan') }}>
            <a href="{{ url('/cutting-scan') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Cutting Scan</span>
            </a>
        </li>
        <li class={{ setActiveClass('challan-wise-bundle') }}>
            <a href="{{ url('/challan-wise-bundle') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Challan Wise Bundle</span>
            </a>
        </li>
        <li class={{ setActiveClass('replace-bundle-card') }}>
            <a href="{{ url('/replace-bundle-card') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Bundle Card Replace</span>
            </a>
        </li>
        <li class={{ setActiveClass('update-cutting-production') }}>
            <a href="{{ url('/update-cutting-production') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Update Cutting Production</span>
            </a>
        </li>
        @includeIf('cuttingdroplets::pages.cutting_droplets_reports_nav')
    </ul>
</li>