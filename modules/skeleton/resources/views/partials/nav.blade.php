@if(getRole() == 'super-admin' || getRole() == 'admin')

    @includeIf('skeleton::partials.super_admin_nav')
@else
    <ul class="nav" ui-nav>

        <li class="nav-header hidden-folded">
            <small class="text-muted">Main</small>
        </li>

        @php
            $permissions = Session::get('permission_details') ?? [];
        @endphp
        @foreach($permissions as $moduleKey => $module)
            {{--get the value of every module and check active for main li--}}
            @php
            $segments = collect(request()->segments())
                                ->filter(function ($segment){
                                    return $segment !== "#";
                                })->implode('/');
                $activeArray = [];
                        foreach ($module as $menuKey => $menu){
                                if (strpos($menu['menu_url'],'/') !== false && strstr($menu['menu_url'],'/',true) == $segments){
                                        array_push($activeArray, strstr($menu['menu_url'],'/',true));
                                } elseif ($menu['menu_url'] == $segments) {
                                        array_push($activeArray,$menu['menu_url']);
                                }
                        foreach ($menu['submodule_menu'] as  $submenu){
                                if (strpos($submenu->submenu_url,'/') !== false && strstr($submenu->submenu_url,'/',true) == $segments){
                                        array_push($activeArray,$submenu->submenu_url);
                                } elseif ($submenu->submenu_url == $segments) {
                                        array_push($activeArray,$submenu->submenu_url);
                                }
                        }
                }
                
            @endphp
            @php
                
                $activeMenu = in_array(request()->segment(1), $activeArray) /* || in_array($segments,$activeArray)*/;

            @endphp
            {{--logic implementation in main li--}}
            <li ui-sref-active="active"
                class={{  $activeMenu ? "active scroll-active" : "" }}>
                <a>
                <span class="nav-caret">
                    <i class="fa fa-caret-down"></i>
                </span>
                    <span class="nav-icon">
                    <i class="fa fa-plus-square"></i>
                </span>
                    <span class="nav-text">{{ $moduleKey }}</span>
                </a>
                <ul class="nav-sub">
                    @foreach($module as $menuKey => $menu)
                        @php
                            $arraySubActive = [];
                            foreach ($menu['submodule_menu'] as  $submenu){
                              if(strpos($menu['menu_url'],'/') !== false && $submenu->submenu_url == $segments){
                                array_push($arraySubActive,$submenu->submenu_url);
                              } elseif ($submenu->submenu_url == $segments) {
                                array_push($arraySubActive,$submenu->submenu_url);
                              }
                    }
                        @endphp
                        @if(count($menu['submodule_menu']) == 0)
                            {{-- logic implementation in individual li --}}
                            <li class={{setDynamicSingleActiveClass($menu['menu_url'])}}>
                                <a href="{{ URL::to($menu['menu_url']) }}">
                        <span class="nav-icon">
                            <i class="fa fa-hand-o-right " aria-hidden="true"></i>
                        </span>
                                    <span class="nav-text">{{ $menu['menu_name'] }} </span>
                                </a>
                            </li>
                        @else
                            @php
                                $subSegments = "/" .collect(request()->segments())
                                ->filter(function ($segment){
                                    return $segment !== "#";
                                })->implode('/');
                                $activeSubMenu =  in_array(request()->segment(1), $arraySubActive) /*|| in_array($subSegments,$arraySubActive)*/;
                            @endphp
                            {{--li for subActive menu--}}
                            <li class="{{ $activeSubMenu ? "active scroll-active" : "" }}">
                                <a>
                        <span class="nav-caret">
                            <i class="fa fa-caret-down"></i>
                        </span>
                                    <span class="nav-icon">
                            <i class="fa fa-plus-square"></i>
                        </span>
                                    <span class="nav-text">{{ $menu['menu_name'] }}</span>
                                </a>
                                <ul class="nav-sub">
                                    @foreach($menu['submodule_menu'] as $submenu)
                                        <li class={{setDynamicSingleActiveClass($submenu->submenu_url)}}>
                                            <a href="{{ url($submenu->submenu_url) }}">
                                                <span class="nav-icon">
                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                                                </span>
                                                <span class="nav-text">
                                                    {{ $submenu->submenu_name }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
@endif

<script>
    function scrollTo(element) {
        window.scroll({
            behavior: 'smooth',
            left: 0,
            top: element.offsetTop
        });
    }


    window.onload = function () {
        const themeColor = JSON.parse(localStorage.getItem("jqStorage-Flatkit-Setting")).color.primary
        const find = document.querySelectorAll('li.scroll-active');
        const lastIdx = find.length - 1;
        const lastNode = find[lastIdx];
        if (lastNode) {
            $("html, body").animate({ scrollTop: lastNode.top }, 1000);
            lastNode.scrollIntoView({behavior: "smooth", block: "center", inline: "nearest"});
            lastNode.style.color = themeColor
        }

    }
</script>
