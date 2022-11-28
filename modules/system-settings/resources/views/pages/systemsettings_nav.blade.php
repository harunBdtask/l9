@if(getRole() == 'super-admin' || getRole() == 'admin')
    @php
        $menuName = [
            'companies',
            'section',
            'factories',
            'roles',
            'permissions',
            'modules',
            'submodules',
            'menus',
            'assign-module-wise-full-permission',
            'assign-permissions',
            'departments',
            'users',
            'mail-employee-list',
            'lots',
            'colors',
            'sizes',
            'parts',
            'types',
            'production-date-change',
            'yarn-compositions',
            'yarn-counts',
            'item',
            'item-group',
            'item-to-group',
            'teams',
            'team-member-assign',
            'production-date-change',
            'process',
            'buying-agent',
            'unit-of-measurement',
            'incoterms',
            'currency',
            'product-department',
            'product-category',
            'stores',
            'yarn-types',
            'party-types',
            'parties',
            'shifts',
            'designations',
            'operators',
            'brands',
            'machines',
            'fabric-types',
            'color-types',
            'new-assign-permissions',
            'print-factory-tables'
        ];
    @endphp
    <li class={{setMultipleActiveClass($menuName)}}>
        <a>
        <span class="nav-caret">
            <i class="fa fa-caret-down"></i>
        </span>
            <span class="nav-icon">
            <i class="fa fa-plus-square"></i>
        </span>
            <span class="nav-text">System Settings</span>
        </a>
        <ul class="nav-sub">
            <!-- General Settings -->
            @php
                $generalSettingsMenuName = [
                    'companies',
                    'section',
                    'factories',
                    'roles',
                    'permissions',
                    'modules',
                    'submodules',
                    'menus',
                    'assign-module-wise-full-permission',
                    'assign-permissions',
                    'departments',
                    'users',
                    'mail-employee-list',
                    'new-assign-permissions'
                ];
            @endphp
            <li class="{{ setMultipleActiveClass($generalSettingsMenuName) }}">
                <a href="#">
                    <span class="nav-caret">
                        <i class="fa fa-caret-down"></i>
                    </span>
                    <span class="nav-icon">
                        <i class="fa fa-gear"></i>
                    </span>
                    <span class="nav-text">General Settings</span>
                </a>
                <ul class="nav-sub">
                    @if(getRole() == 'super-admin')
                        <li class="{{ setActiveClass('companies') }}">
                            <a href="{{ url('companies') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                                <span class="nav-text">Company</span>
                            </a>
                        </li>
                        <li class="{{ setActiveClass('section') }}">
                            <a href="{{ url('section') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                                <span class="nav-text">Section</span>
                            </a>
                        </li>
                        <li class="{{ setActiveClass('factories') }}">
                            <a href="{{ url('factories') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                                <span class="nav-text">Factories</span>
                            </a>
                        </li>
                        <li class="{{ setActiveClass('roles') }}">
                            <a href="{{ url('roles') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                                <span class="nav-text">Roles</span>
                            </a>
                        </li>
                        <li class="{{ setActiveClass('permissions') }}">
                            <a href="{{ url('permissions') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                                <span class="nav-text">Permissions Name</span>
                            </a>
                        </li>
                        <li class="{{ setActiveClass('modules') }}">
                            <a href="{{ url('modules') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                                <span class="nav-text">Modules</span>
                            </a>
                        </li>
                        {{--<li class="{{ setActiveClass('submodules') }}">
                            <a href="{{ url('submodules') }}">
                                <span class="nav-icon">
                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                                </span>
                                <span class="nav-text">Sub Modules</span>
                            </a>
                        </li>--}}
                        <li class="{{ setActiveClass('menus') }}">
                            <a href="{{ url('menus') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                                <span class="nav-text">Menus</span>
                            </a>
                        </li>
                        <li class="{{ setActiveClass('assign-module-wise-full-permission') }}">
                            <a href="{{ url('assign-module-wise-full-permission') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                                <span class="nav-text">Assign Full Permission</span>
                            </a>
                        </li>
                    @endif
                    <li class="{{ setActiveClass('assign-permissions') }} {{ setActiveClass('new-assign-permissions') }}">
                        <a href="{{ url('assign-permissions') }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
                            <span class="nav-text">Assign Permission</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('departments') }}">
                        <a href="{{ url('departments') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Departments</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('users') }}">
                        <a href="{{ url('users') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Users</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('mail-employee-list') }}">
                        <a href="{{ url('mail-employee-list') }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        </span>
                            <span class="nav-text">Mail Employee List</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Merchandising Settings -->
            @php
                $merchandisingMenuName = [
                    'colors', 'sizes', 'yarn-compositions', 'yarn-counts','item','item-group','item-to-group','teams',
                    'team-member-assign','process','buying-agent','unit-of-measurement','incoterms','currency',
                    'product-department','product-category','stores', 'color-types'
                ];
            @endphp
            <li class="{{ setMultipleActiveClass($merchandisingMenuName) }}">
                <a href="#">
                    <span class="nav-caret">
                        <i class="fa fa-caret-down"></i>
                    </span>
                    <span class="nav-icon">
                        <i class="fa fa-gear"></i>
                    </span>
                    <span class="nav-text">Merchandising</span>
                </a>
                <ul class="nav-sub">
                    <li class="{{ setActiveClass('colors') }}">
                        <a href="{{ url('colors') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Colors</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('sizes') }}">
                        <a href="{{ url('sizes') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Sizes</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('teams') }}">
                        <a href="{{ url('teams') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Team</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('team-member-assign') }}">
                        <a href="{{ url('team-member-assign') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Team Member Assign</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('item') }}">
                        <a href="{{ url('item') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Item</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('item-group') }}">
                        <a href="{{ url('item-group') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Item Group</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('item-to-group') }}">
                        <a href="{{ url('item-to-group') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Item to Group Assign</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('yarn-compositions') }}">
                        <a href="{{ url('yarn-compositions') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Yarn Composition</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('yarn-counts') }}">
                        <a href="{{ url('yarn-counts') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Yarn Count</span>
                        </a>
                    </li>
{{--                    <li class="{{ setActiveClass('suppliers') }}">--}}
{{--                        <a href="{{ url('suppliers') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Suppliers</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="{{ setActiveClass('process') }}">
                        <a href="{{ url('process') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Process</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('buying-agent') }}">
                        <a href="{{ url('buying-agent') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Buying Agent</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('unit-of-measurement') }}">
                        <a href="{{ url('unit-of-measurement') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Unit Of Measurement</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('incoterms') }}">
                        <a href="{{ url('incoterms') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Incoterms</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('currency') }}">
                        <a href="{{ url('currency') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Currency</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('product-department') }}">
                        <a href="{{ url('product-department') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Product Department</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('product-category') }}">
                        <a href="{{ url('product-category') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Product Category</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('stores') }}">
                        <a href="{{ url('stores') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Store</span>
                        </a>
                    </li>
{{--                    <li class="{{ setActiveClass('fabric-compositions') }}">--}}
{{--                        <a href="{{ url('fabric-compositions') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Fabric Compositions</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="{{ setActiveClass('color-types') }}">
                        <a href="{{ url('color-types') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Color Types</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Protracker Settings -->
            @php
                $protrackerMenuName = [
                    'print-factory-tables',
                    'lines',
                    'parts',
                    'types',
                    'production-date-change'
                ];
            @endphp
            <li class="{{ setMultipleActiveClass($protrackerMenuName) }}">
                <a href="#">
                    <span class="nav-caret">
                        <i class="fa fa-caret-down"></i>
                    </span>
                    <span class="nav-icon">
                        <i class="fa fa-gear"></i>
                    </span>
                    <span class="nav-text">Protracker</span>
                </a>
                <ul class="nav-sub">
{{--                    <li class="{{ setActiveClass('lots') }}">--}}
{{--                        <a href="{{ url('lots') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Lots</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}

                    <li class="{{ setActiveClass('parts') }}">
                        <a href="{{ url('parts') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Parts</span>
                        </a>
                    </li>
                    <li class="{{ setActiveClass('types') }}">
                        <a href="{{ url('types') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Types</span>
                        </a>
                    </li>
{{--                    <li class="{{ setActiveClass('others-factories') }}">--}}
{{--                        <a href="{{ url('others-factories') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Others Factories</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="{{ setActiveClass('print-factory-tables') }}">
                        <a href="{{ url('print-factory-tables') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Print Factory Tables</span>
                        </a>
                    </li>
{{--                    <li class="{{ setActiveClass('cutting-floors') }}">--}}
{{--                        <a href="{{ url('cutting-floors') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Cutting Floors</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="{{ setActiveClass('cutting-tables') }}">--}}
{{--                        <a href="{{ url('cutting-tables') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Cutting Tables</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="{{ setActiveClass('floors') }}">--}}
{{--                        <a href="{{ url('floors') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Sewing Floors</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="{{ setActiveClass('lines') }}">--}}
{{--                        <a href="{{ url('lines') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Sewing Lines</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="{{ setActiveClass('sewing-rejection-entry-type') }}">--}}
{{--                        <a href="{{ url('sewing-rejection-entry-type') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Sewing Rejection Entry Type</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}

                    <li class="{{ setActiveClass('production-date-change') }}">
                        <a href="{{ url('production-date-change') }}">
                            <span class="nav-icon">
                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                            </span>
                            <span class="nav-text">Production Date Change</span>
                        </a>
                    </li>
{{--                    <li class="{{ setActiveClass('user-cutting-floor-plan-permissions') }}">--}}
{{--                        <a href="{{ url('user-cutting-floor-plan-permissions') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Cutting Plan Permission</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="{{ setActiveClass('tasks') }}">--}}
{{--                        <a href="{{ url('tasks') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Sewing Line Tasks</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="{{ setActiveClass('machine-types') }}">--}}
{{--                        <a href="{{ url('machine-types') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Machine Types</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="{{ setActiveClass('operator-skill') }}">--}}
{{--                        <a href="{{ url('operator-skill') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Operator Skills</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="{{ setActiveClass('guide-or-folders') }}">--}}
{{--                        <a href="{{ url('guide-or-folders') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Guide or Folders</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}

{{--                    <li class="{{ setActiveClass('print-factory-tables') }}">--}}
{{--                        <a href="{{ url('print-factory-tables') }}">--}}
{{--                            <span class="nav-icon">--}}
{{--                                <i class="fa fa-hand-o-right" aria-hidden="true"></i>--}}
{{--                            </span>--}}
{{--                            <span class="nav-text">Print Factory Tables</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                </ul>
            </li>
            @includeIf('knittingdroplets::pages.knitracker_settings_nav')
        </ul>
    </li>
@elseif(getRole() == 'admin')
    {{--
        <li>
           <a>
           <span class="nav-caret">
           <i class="fa fa-caret-down"></i>
           </span>
           <span class="nav-icon">
           <i class="fa fa-plus-square"></i>
           </span>
           <span class="nav-text">System Settings</span>
           </a>
           <ul class="nav-sub">
              <li>
                 <a href="{{ url('print-factories') }}">
    <span class="nav-icon">
        <i class="fa fa-hand-o-right" aria-hidden="true"></i>
    </span>
    <span class="nav-text">Print Factories</span>
    </a>
    </li>
    <li>
        <a href="{{ url('cutting-floors') }}">
            <span class="nav-icon">
                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Cutting Floors</span>
        </a>
    </li>
    <li>
        <a href="{{ url('cutting-tables') }}">
            <span class="nav-icon">
                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Cutting Tables</span>
        </a>
    </li>
    <li>
        <a href="{{ url('production-date-change') }}">
            <span class="nav-icon">
                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Production Date Change</span>
        </a>
    </li>
    <li>
        <a href="{{ url('users') }}">
            <span class="nav-icon">
                <i class="fa fa-hand-o-right" aria-hidden="true"></i>
            </span>
            <span class="nav-text">Users</span>
        </a>
    </li>
    </ul>
    </li>
    --}}
@endif
