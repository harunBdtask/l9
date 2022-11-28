<li class="">
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">Merchandising</span>
    </a>
    <ul class="nav-sub">
        <li class="{{setActiveClass('buyer')}}">
            <a href="{{ url('buyers') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Buyers</span>
            </a>
        </li>
        <li class="{{setActiveClass('sample')}}">
            <a href="{{ url('sample/list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Sample</span>
            </a>
        </li>
        <li class="{{setActiveClass('order')}}">
            <a href="{{ url('order/list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order</span>
            </a>
        </li>
        <li class="{{setActiveClass('purchase-order')}}">
            <a href="{{ url('purchase-order/list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Purchase Order</span>
            </a>
        </li>
        <li class="{{  setActiveClass('budget') }}">
            <a href="{{ url('budget/list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Budget</span>
            </a>
        </li>
        <li class="{{  setActiveClass('trims-accessories-booking') }}">
            <a href="{{ url('trims-accessories-booking') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Trims Accessories Booking</span>
            </a>
        </li>
        @includeIf('merchandising::pages.merchandising_reports_nav')
    </ul>

</li>
