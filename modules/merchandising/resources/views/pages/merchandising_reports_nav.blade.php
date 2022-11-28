<li class="">
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">{{ (getDept() == 'report-viewer') ? 'Merchandising' : '' }} Reports</span>
    </a>
    <ul class="nav-sub">
        <li class="{{ setActiveClass('order-confirmation-list') }}">
            <a href="{{ url('order-confirmation-list') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order Confirmation List</span>
            </a>
        </li>
        <li class="{{ setActiveClass('recap-report') }}">
            <a href="{{ url('recap-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">PO Wise Recap Report</span>
            </a>
        </li>
        <li class="{{ setActiveClass('po-wise-recap-report') }}">
            <a href="{{ url('po-wise-recap-report') }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
                <span class="nav-text">PO Wise Recap Report New</span>
            </a>
        </li>
        <li class="{{ setActiveClass('order-recap-report') }}">
            <a href="{{ url('order-recap-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order Recap Report</span>
            </a>
        </li>
        <li class="{{ setActiveClass('order-recap-report-update') }}">
           <a href="{{ url('order-recap-report-update') }}">
               <span class="nav-icon">
                   <i class="fa fa-hand-o-right" aria-hidden="true"></i>
               </span>
               <span class="nav-text">Order Recap Report Update</span>
           </a>
        </li>
        <li class="{{ setActiveClass('order-recap-summary-report') }}">
            <a href="{{ url('order-recap-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order Recap Summary Report</span>
            </a>
        </li>
        <li class="{{ setActiveClass('recap-summary-report') }}">
            <a href="{{ url('recap-summary-report') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Recap Summary Report</span>
            </a>
        </li>
        <li class="{{ setActiveClass('budget-details-report') }}">
            <a href="{{ url('budget-details-report') }}">
                <span class="nav-icon">
                 <i class="fa fa-hand-o-right" aria-hidden="true"></i>
               </span>
                <span class="nav-text">Budget Details Report</span>
            </a>
        </li>
        <li class="{{ setActiveClass('short-fabric-book-report') }}">
            <a href="{{url('short-fabric-book-report')}}">
                 <span class="nav-icon">
                     <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Short Fabric Book Report</span>
            </a>
        </li>
        <li class="{{ setActiveClass('short-trim-book-report') }}">
            <a href="{{url('short-trim-book-report')}}">
                 <span class="nav-icon">
                     <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Short Trim Book Report</span>
            </a>
        </li>
        <li class="{{ setActiveClass('proft-loss-statement') }}">
            <a href="{{url('proft-loss-statement')}}">
                 <span class="nav-icon">
                     <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Profit Loss Statement</span>
            </a>
        </li>        
        <li class="{{ setActiveClass('order-current-status') }}">
            <a href="{{url('order-current-status')}}">
                 <span class="nav-icon">
                     <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Order Current Status</span>
            </a>
        </li>
         <li class="{{ setActiveClass('color-wise-summary') }}">
            <a href="{{url('color-wise-summary')}}">
                 <span class="nav-icon">
                     <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Color Wise Summary</span>
            </a>
        </li>
    </ul>
</li>