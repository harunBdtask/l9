@php
    $washingDropletsReport = [
        'order-wise-receievd-from-wash',
        'buyer-wise-receievd-from-wash',
        'date-wise-washing-report'
    ];
@endphp
<li class={{ setMultipleActiveClass($washingDropletsReport) }}>
    <a href="#">
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square-o"></i>
        </span>
        <span class="nav-text">{{ (getDept() == 'report-viewer' || getDept() == 'washing') ? 'Washing' : '' }} Reports</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('order-wise-receievd-from-wash') }}>
            <a href="{{ url('/order-wise-receievd-from-wash') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">All Order's Summary</span>
            </a>
        </li>
        <li class={{ setActiveClass('buyer-wise-receievd-from-wash') }}>
            <a href="{{ url('/buyer-wise-receievd-from-wash') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Buyer Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('date-wise-washing-report') }}>
            <a href="{{ url('/date-wise-washing-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Washing Report</span>
            </a>
        </li>
    </ul>
</li>