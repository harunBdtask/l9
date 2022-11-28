@php
$ieDroplets = [
    'operation-bulletins',
    'date-wise-cutting-targets',
    'sewing-line-target',
    'show-smv',
    'shipment-date-and-unit-price-update',
    'shipment-status',
    'shipments',
    'all-orders-shipment-summary',
    'buyer-wise-shipment-report',
    'booking-no-po-and-color-report',
];
@endphp
<li class={{ setMultipleActiveClass($ieDroplets ) }}>
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">IE Droplets</span>
    </a>
    <ul class="nav-sub">
        {{--
        <li>
            <a href="{{ url('cutting-targets') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Cutting Targets</span>
            </a>
        </li>
        --}}
        @if(getRole() == 'super-admin')
        <li class={{ setActiveClass('operation-bulletins') }}>
            <a href="{{ url('operation-bulletins') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Operation Bulletins</span>
            </a>
        </li>
        @endif
        <li class={{ setActiveClass('date-wise-cutting-targets') }}>
            <a href="{{ url('date-wise-cutting-targets') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Cutting Targets</span>
            </a>
        </li>
        <li class={{ setActiveClass('sewing-line-target') }}>
            <a href="{{ url('/sewing-line-target') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Sewing Line Target</span>
            </a>
        </li>
        <li class={{ setActiveClass('show-smv') }}>
            <a href="{{ url('/show-smv') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Show SMV</span>
            </a>
        </li>
        {{--
        <li>
            <a href="{{ url('/line-wise-npt') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Line Wise NPT</span>
            </a>
        </li>
        --}}
        <li class={{ setActiveClass('shipment-date-and-unit-price-update') }}>
            <a href="{{ url('/shipment-date-and-unit-price-update') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Shipment Date &amp; Unit Price Update</span>
            </a>
        </li>
        {{--
        <li class={{ setActiveClass('shipment-status') }}>
            <a href="{{ url('/shipment-status') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Shipment Status</span>
            </a>
        </li>
        --}}
        <li class={{ setActiveClass('shipments') }}>
            <a href="{{ url('/shipments') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Shipments</span>
            </a>
        </li>
        @includeIf('iedroplets::pages.ie_droplets_reports_nav')
    </ul>
</li>