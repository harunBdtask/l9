@php
    $printFactoryReports =[
        'date-wise-send-receive-report',
    ];
@endphp
<li class={{ setMultipleActiveClass($printFactoryReports) }}>
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
        <li class={{ setActiveClass('date-wise-send-receive-report') }}>
            <a href="{{ url('/date-wise-print-rcv-production-delivery-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Report</span>
            </a>
        </li>
    </ul>
</li>