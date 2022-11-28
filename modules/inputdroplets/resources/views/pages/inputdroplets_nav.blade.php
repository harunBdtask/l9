@php
  $inputDroplets = [
    'cutting-inventory-scan',
    'view-challan-list',
    'view-tag-list',
    'gatepasses',
    'challan-wise-bundles',
    'order-wise-cutting-inventory-summary',
    'cutting-no-wise-inventory-challan',
    'inventory-challan-count',
    'buyer-sewing-line-input',
    'bundle-scan-check',
    'cutting-no-wise-cutting-report',
    'order-sewing-line-input',
    'booking-no-po-and-color-report',
    'date-wise-sewing-input',
    'date-range-or-month-wise-sewing-input',
    'floor-line-wise-sewing-report',
    'input-closing',
    'floor-line-wise-input-report',
  ];
@endphp
<li class={{ setMultipleActiveClass($inputDroplets) }}>
  <a>
    <span class="nav-caret">
      <i class="fa fa-caret-down"></i>
    </span>
    <span class="nav-icon">
      <i class="fa fa-plus-square" aria-hidden="true"></i>
    </span>
    <span class="nav-text">Input Droplets</span>
  </a>
  <ul class="nav-sub">
    <li class={{ setActiveClass('cutting-inventory-scan') }}>
      <a href="{{ url('/cutting-inventory-scan') }}">
        <span class="nav-icon">
          <i class="fa fa-hand-o-right" aria-hidden="true"></i>
        </span>
        <span class="nav-text">Solid Input/Tag</span>
      </a>
    </li>
    <li class={{ setActiveClass('view-challan-list') }}>
      <a href="{{ url('/view-challan-list') }}">
        <span class="nav-icon">
          <i class="fa fa-hand-o-right" aria-hidden="true"></i>
        </span>
        <span class="nav-text">Challan List</span>
      </a>
    </li>
    <li class={{ setActiveClass('view-tag-list') }}>
      <a href="{{ url('/view-tag-list') }}">
        <span class="nav-icon">
          <i class="fa fa-hand-o-right" aria-hidden="true"></i>
        </span>
        <span class="nav-text">Tag List</span>
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
    <li class={{ setActiveClass('challan-wise-bundles') }}>
      <a href="{{ url('/challan-wise-bundles') }}">
        <span class="nav-icon">
          <i class="fa fa-hand-o-right" aria-hidden="true"></i>
        </span>
        <span class="nav-text">Challan Wise Bundles</span>
      </a>
    </li>
    @includeIf('inputdroplets::pages.inputdroplets_reports_nav')
  </ul>
</li>