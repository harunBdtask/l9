<li>
    <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
        <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
        <span class="nav-text">Security Control</span>
    </a>
    <ul class="nav-sub">
        <li class="">
            <a>
                <span class="nav-caret">
                    <i class="fa fa-caret-down"></i>
                </span>
                <span class="nav-icon">
                    <i class="fa fa-plus-square"></i>
                </span>
                <span class="nav-text">Vehicle Tracking System</span>
            </a>
            <ul class="nav-sub">
                <li class="">
                    <a href="{{ route('vehicle.index') }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
                        <span class="nav-text">In house Vehicle Settings</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('vehicle-assign-index') }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
                        <span class="nav-text">In house Vehicle Assign</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('third.vehicle.index') }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
                        <span class="nav-text">Third party Vehicle Tracking</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="">
            <a>
                <span class="nav-caret">
                    <i class="fa fa-caret-down"></i>
                </span>
                <span class="nav-icon">
                    <i class="fa fa-plus-square"></i>
                </span>
                <span class="nav-text">Employee Tracking System</span>
            </a>
            <ul class="nav-sub">
                <li class="">
                    <a href="{{ route('employee.index') }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
                        <span class="nav-text">Employee Settings</span>
                    </a>
                </li>

            </ul>
        </li>
        <li class="">
            <a>
                <span class="nav-caret">
                    <i class="fa fa-caret-down"></i>
                </span>
                <span class="nav-icon">
                    <i class="fa fa-plus-square"></i>
                </span>
                <span class="nav-text">Visitor Tracking System</span>
            </a>
            <ul class="nav-sub">
                <li class="">
                    <a href="{{ route('visitor.index') }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
                        <span class="nav-text">Visitor Settings</span>
                    </a>
                </li>

            </ul>
        </li>


    </ul>

</li>