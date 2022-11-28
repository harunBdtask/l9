<li>
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">Skill Matrix</span>
    </a>
    <ul class="nav-sub">
        <li>
            <a href="{{ url('sewing-machines') }}">
            <span class="nav-icon">
             <i class="fa fa-hand-o-right" aria-hidden="true"></i>
           </span>
                <span class="nav-text">Sewing Machines</span>
            </a>
        </li>       
        <li>
            <a href="{{ url('processes') }}" >
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Processes</span>
            </a>
        </li>
        <li>
            <a href="{{ url('process-assign-to-machines') }}" >
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Process Assign To Machine</span>
            </a>
        </li>
        <li>
            <a href="{{ url('sewing-operators') }}" >
                <span class="nav-icon">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                </span>
                <span class="nav-text">Sewing Operators</span>
            </a>
        </li>        
        <li>
            <a>
                <span class="nav-caret">
                    <i class="fa fa-caret-down"></i>
                </span>
                <span class="nav-icon">
                    <i class="fa fa-plus-square"></i>
                </span>
                <span class="nav-text">Reports</span>
            </a>
            <ul class="nav-sub">
                <li>
                    <a href="{{ url('operator-skill-inventory') }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
                        <span class="nav-text">Operator Skill Inventory</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>
