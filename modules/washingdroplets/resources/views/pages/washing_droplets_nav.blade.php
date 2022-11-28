@php
    $washingDroplets = [
        'washing-scan',
        'received-bundle-from-wash',
        'washing-challan-list',
        'order-wise-receievd-from-wash',
        'buyer-wise-receievd-from-wash',
        'manual-washing-received-challan-list',
        'date-wise-washing-report'
    ];
@endphp
<li class={{ setMultipleActiveClass($washingDroplets) }}>
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">Washing Droplets</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('washing-scan') }}>
            <a href="{{ url('/washing-scan') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Send To Wash</span>
            </a>
        </li>
        <li class={{ setActiveClass('received-bundle-from-wash') }}>
            <a href="{{ url('/received-bundle-from-wash') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Received Form Wash</span>
            </a>
        </li>
        <li class={{ setActiveClass('manual-washing-received-challan-list') }}>
            <a href="{{ url('/manual-washing-received-challan-list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Manual Washing Received Challan List</span>
            </a>
        </li>
        <li class={{ setActiveClass('washing-challan-list') }}>
            <a href="{{ url('/washing-challan-list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Washing Challan List</span>
            </a>
        </li>
        {{--
        <li>
            <a href="{{ url('/received-from-wash') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Receive From Wash</span>
            </a>
        </li>
        --}}
        @includeIf('washingdroplets::pages.washing_droplets_reports_nav')
    </ul>
</li>