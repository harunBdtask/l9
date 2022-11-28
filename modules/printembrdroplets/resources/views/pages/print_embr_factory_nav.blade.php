@php
  $printEmbrDroplets = [
      'print-embr-factory-receive-scan',
      'print-embr-production-scan',
      'print-embr-qc-scan',
      'print-embroidery-target',
      'print-embr-factory-receive-challan-list',
      'print-embr-factory-receive-tag-list',
      'print-embr-qc-tag-list',
      'print-embr-delivery-challan-list',
      'print-embroidery-target'
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
    <span class="nav-text">Print/Embr. Factory</span>
  </a>
  <ul class="nav-sub">
    <li class={{ setActiveClass('/print-embr-factory-receive-scan') }}>
      <a href="{{ url('/print-embr-factory-receive-scan') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
        <span class="nav-text">Print/Embr Factory Receive Scan</span>
      </a>
    </li>
    <li class={{ setActiveClass('/print-embr-production-scan') }}>
      <a href="{{ url('/print-embr-production-scan') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
        <span class="nav-text">Print/Embr Production Scan</span>
      </a>
    </li>
    <li class={{ setActiveClass('/print-embr-qc-scan') }}>
      <a href="{{ url('/print-embr-qc-scan') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
        <span class="nav-text">Print/Embr QC Scan</span>
      </a>
    </li>
    {{-- <li class={{ setActiveClass('/print-embr-factory-delivery-scan') }}>
        <a href="{{ url('/print-embr-factory-delivery-scan') }}">
            <span class="nav-icon">
                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Print/Embr Factory Delivery Scan</span>
        </a>
    </li>  --}}
    <li class={{ setActiveClass('print-embr-factory-receive-challan-list') }}>
      <a href="{{ url('/print-embr-factory-receive-challan-list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
        <span class="nav-text">Print/Embr Factory Receive Challan List</span>
      </a>
    </li>
    <li class={{ setActiveClass('print-embr-factory-receive-tag-list') }}>
      <a href="{{ url('/print-embr-factory-receive-tag-list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
        <span class="nav-text">Print/Embr Factory Receive Tag List</span>
      </a>
    </li>
    <li class={{ setActiveClass('print-embr-qc-tag-list') }}>
      <a href="{{ url('/print-embr-qc-tag-list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
        <span class="nav-text">Print/Embr Qc Tag List</span>
      </a>
    </li>
    <li class={{ setActiveClass('print-embr-delivery-challan-list') }}>
      <a href="{{ url('/print-embr-delivery-challan-list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
        <span class="nav-text">Print Embr Delivery Challan List</span>
      </a>
    </li>
    <li class={{ setActiveClass('print-embroidery-target') }}>
      <a href="{{ url('/print-embroidery-target') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
        <span class="nav-text">Print/Embroidery Target</span>
      </a>
    </li>
    @includeIf('printembrdroplets::pages.print_factory_reports_nav')
  </ul>
</li>