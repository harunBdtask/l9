@php
    $financeDroplets = [
        'finance'
    ];

    $financeDropletsReport = [

    ];
@endphp

<li class={{ setActiveClass('finance') }}>
    <a>
        <span class="nav-caret">
          <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
           <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">Finance</span>
    </a>
    <ul class="nav-sub">
        <li class={{ setActiveClass('finance/accounts') }}>
            <a href="{{ url('/basic-finance/accounts') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Chart of Accounts</span>
            </a>
        </li>
        <li class={{ setActiveClass('/basic-finance/vouchers/create?voucher_type=debit') }}>
            <a href="{{ url('/basic-finance/vouchers/create?voucher_type=debit') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Debit Vouchers</span>
            </a>
        </li>
        <li class={{ setActiveClass('/basic-finance/vouchers/create?voucher_type=credit') }}>
            <a href="{{ url('/basic-finance/vouchers/create?voucher_type=credit') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Credit Vouchers</span>
            </a>
        </li>
        <li class={{ setActiveClass('/basic-finance/vouchers/create?voucher_type=journal') }}>
            <a href="{{ url('/basic-finance/vouchers/create?voucher_type=journal') }}">
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Journal Vouchers</span>
            </a>
        </li>
        <li class={{ setMultipleActiveClass($financeDropletsReport) }}>
            <a href="#">
                <span class="nav-caret">
                    <i class="fa fa-caret-down"></i>
                </span>
                <span class="nav-icon">
                    <i class="fa fa-plus-square-o"></i>
                </span>
                <span class="nav-text">Reports</span>
            </a>
            <ul class="nav-sub"> 
                <li class="{{ setActiveClass('/basic-finance/vouchers') }}">
                    <a href="{{ url('/basic-finance/vouchers') }}">
                        <span class="nav-icon">
                          <i class="fa fa-hand-o-right"></i>
                        </span>
                        <span class="nav-text">Voucher List</span>
                    </a>
                </li>
                <li class="{{ setActiveClass('/basic-finance/transactions') }}">
                    <a href="{{ url('/basic-finance/transactions') }}">
                        <span class="nav-icon">
                          <i class="fa fa-hand-o-right"></i>
                        </span>
                        <span class="nav-text">Transactions</span>
                    </a>
                </li>
                <li class="{{ setActiveClass('basic-finance/ledger') }}">
                    <a href="{{ url('/basic-finance/ledger') }}">
                        <span class="nav-icon">
                          <i class="fa fa-hand-o-right"></i>
                        </span>
                        <span class="nav-text">Ledger</span>
                    </a>
                </li>               
                <li class= {{ setActiveClass('/basic-finance/trial-balance')  }}>

                    <a href="{{ url('/basic-finance/trial-balance') }}">
                        <span class="nav-icon">
                          <i class="fa fa-hand-o-right"></i>
                        </span>
                        <span class="nav-text">Trial Balance</span>
                    </a>
                </li>
                <li class="{{ setActiveClass('basic-finance/receipts-and-payments') }}">

                    <a href="{{ url('/basic-finance/receipts-and-payments') }}">
                        <span class="nav-icon">
                          <i class="fa fa-hand-o-right"></i>
                        </span>
                        <span class="nav-text">Receipts / Payments</span>
                    </a>
                </li>               
                <li class="{{ setActiveClass('basic-finance/income-statement') }}">
                    <a href="{{ url('/basic-finance/income-statement')  }}">
                        <span class="nav-icon">
                          <i class="fa fa-hand-o-right"></i>
                        </span>
                        <span class="nav-text">Income Statement</span>
                    </a>
                </li>
                <li class="{{ setActiveClass('basic-finance/balance-sheet') }}">
                    <a href="{{ url('/basic-finance/balance-sheet')  }}">
                        <span class="nav-icon">
                          <i class="fa fa-hand-o-right"></i>
                        </span>
                        <span class="nav-text">Balance Sheet</span>
                    </a>
                </li>
                <li class="{{ setActiveClass('basic-finance/cash-flow-statement') }}">
                    <a href="{{ url('/basic-finance/cash-flow-statement') }}">
                        <span class="nav-icon">
                          <i class="fa fa-hand-o-right"></i>
                        </span>
                        <span class="nav-text">Cash Flow Statement</span>
                    </a>
                </li>               
            </ul>
        </li>
    </ul>
</li>