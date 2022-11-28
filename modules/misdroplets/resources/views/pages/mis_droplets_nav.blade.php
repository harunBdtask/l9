@php
    $misDroplets = ['color-wise-production-summary-report', 'audit-report', 'monthly-efficiency-summary-report','factory-wise-cutting-report','factory-wise-print-sent-received-report','factory-wise-input-output-report'];
@endphp
@if(getRole() == 'super-admin' || getRole() == 'admin')
    <li class={{ setMultipleActiveClass($misDroplets) }}>
        <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
            <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
            <span class="nav-text">MIS Reports</span>
        </a>
        <ul class="nav-sub">
            <li class={{ setActiveClass('audit-report') }}>
                <a href="{{ url('audit-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                    <span class="nav-text">Audit Report</span>
                </a>
            </li>
            <li class={{ setActiveClass('color-wise-production-summary-report') }}>
                <a href="{{ url('color-wise-production-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                    <span class="nav-text">Color Wise Production Summary Report</span>
                </a>
            </li>
            <li class={{ setActiveClass('monthly-efficiency-summary-report') }}>
                <a href="{{ url('monthly-efficiency-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                    <span class="nav-text">Monthly Efficiency Summary Report</span>
                </a>
            </li>
            <li class={{ setActiveClass('factory-wise-cutting-report') }}>
                <a href="{{ url('factory-wise-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                    <span class="nav-text">Factory Wise Cutting Report</span>
                </a>
            </li>
            <li class={{ setActiveClass('factory-wise-print-sent-received-report') }}>
                <a href="{{ url('factory-wise-print-sent-received-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                    <span class="nav-text">Factory Wise Print Send &amp; Receive Report</span>
                </a>
            </li>
            <li class={{ setActiveClass('factory-wise-input-output-report') }}>
                <a href="{{ url('factory-wise-input-output-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                    <span class="nav-text">Factory Wise Input &amp; Output Report</span>
                </a>
            </li>
        </ul>
    </li>
@endif