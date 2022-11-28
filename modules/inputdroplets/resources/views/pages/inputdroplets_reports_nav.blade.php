@php
$inputDropletsReport = [
    'order-wise-cutting-inventory-summary',
    'cutting-no-wise-inventory-challan',
    'cutting-no-wise-cutting-report',
    'inventory-challan-count',
    'order-sewing-line-input',
    'buyer-sewing-line-input',
    'booking-no-po-and-color-report',
    'date-wise-sewing-input',
    'floor-line-wise-sewing-report',
    'line-wise-sewing-input-output',
    'input-closing',
    'bundle-scan-check',
    'cutting-no-wise-cutting-report',
    'date-range-or-month-wise-sewing-input',
    'floor-line-wise-input-report',
];
@endphp
<li class={{ setMultipleActiveClass($inputDropletsReport) }}>
   <a href="#">
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square-o"></i>
        </span>
        <span class="nav-text">
            {{ 
                (getDept() == 'report-viewer' 
                || getDept() == 'sewing-input' 
                || getDept() == 'print-received') ? 'Input' : '' 
            }} Reports
        </span>
   </a>
   <ul class="nav-sub">
        <li class={{ setActiveClass('order-wise-cutting-inventory-summary') }}>
            <a href="{{ url('/order-wise-cutting-inventory-summary') }}">
                <span class="nav-icon">
                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">All PO's Inventory Summary</span>
            </a>
        </li>
        <li class={{ setActiveClass('cutting-no-wise-inventory-challan') }}>
            <a href="{{ url('/cutting-no-wise-inventory-challan') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Cutting Wise Inventory Challan</span>
            </a>
        </li>
        <li class={{ setActiveClass('cutting-no-wise-cutting-report') }}>
            <a href="{{ url('/cutting-no-wise-cutting-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Cutting Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('inventory-challan-count') }}>
            <a href="{{ url('/inventory-challan-count') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Challan Count</span>
            </a>
        </li>     
        <li class={{ setActiveClass('order-sewing-line-input') }}>
            <a href="{{ url('/order-sewing-line-input') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">All PO's Input Summary </span>
            </a>
        </li>
        <li class={{ setActiveClass('buyer-sewing-line-input') }}>
            <a href="{{ url('/buyer-sewing-line-input') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Buyer Wise Input</span>
            </a>
        </li>
        <li class={{ setActiveClass('booking-no-po-and-color-report') }}>
            <a href="{{ url('/booking-no-po-and-color-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Booking No, PO &amp; Color Wise Report</span>
            </a>
        </li>      
        <li class={{ setActiveClass('date-wise-sewing-input') }}>
            <a href="{{ url('/date-wise-sewing-input') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Date Wise Input</span>
            </a>
        </li>              
        <li class={{ setActiveClass('date-range-or-month-wise-sewing-input') }}>
            <a href="{{ url('/date-range-or-month-wise-sewing-input') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Month Wise Input</span>
            </a>
        </li>
        {{--
          <li>
             <a href="{{ url('/line-wise-input') }}">
             <span class="nav-icon">
             <i class="fa fa-hand-o-right" aria-hidden="true"></i>
             </span>
             <span class="nav-text">Line Wise Report</span>
             </a>
          </li>
        --}}                
        {{-- <li class={{ setActiveClass('line-wise-sewing-input-output') }}>
            <a href="{{ url('/line-wise-sewing-input-output') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Line Wise Report</span>
            </a>
        </li> --}}
        <li class={{ setActiveClass('floor-line-wise-sewing-report') }}>
            <a href="{{ url('/floor-line-wise-sewing-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Floor &amp; Line Wise Report</span>
            </a>
        </li>
        <li class={{ setActiveClass('input-closing') }}>
            <a href="{{ url('/input-closing') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Input Closing</span>
            </a>
        </li>
        <li class={{ setActiveClass('bundle-scan-check') }}>
            <a href="{{ url('/bundle-scan-check') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Bundlecard Scan Check</span>
            </a>
        </li>

       <li class={{ setActiveClass('floor-line-wise-input-report') }}>
           <a href="{{ url('/floor-line-wise-input-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
               <span class="nav-text">Line Wise Input Inhand Report</span>
           </a>
       </li>
   </ul>
</li>
