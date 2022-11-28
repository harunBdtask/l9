@php
    function setLiActiveClass($items, $base_url, $request_url_segments)
    {
        $active = '';
        if ($items['url'] && isset($items['url']) && $items['url'] != '') {
            $item_route = str_replace($base_url . '/', '', $items['url']);
            return $request_url_segments == $item_route ? 'active scroll-active' : '';
        }
        if (is_array($items) && array_key_exists('items', $items) && count($items['items'])) {
            foreach ($items['items'] as $item) {
                $active = setLiActiveClass($item, $base_url, $request_url_segments);
                if ($active == 'active scroll-active') {
                    return $active;
                }
            }
        }
        return $active;
    }

    function print_menu($menu, $nav_class, $nav_padding_left, $nav_indicator = '') {

        $request_url_segments = request()->segments()[0] ?? '/';
        if(count(request()->segments()) > 0 ) {
          switch ($request_url_segments) {
            case 'commercial':
            case 'finance':
            case 'work-order':
            case 'yarn-purchase':
            case 'knitting':
            case 'inventory':
            case 'subcontract':
            case 'v2':
            case 'hr':
            case 'dyeing':
            case 'dyes-store':
            case 'basic-finance':
            case 'mc-inventory':
              $request_url_segments .= '/'.request()->segments()[1];
              break;
            case 'sample-management':
            case 'planning':
            case 'trims-store':
            case 'approvals':
              $request_url_segments .=  (array_key_exists(2, request()->segments()) && request()->segments()[2]) ? '/'.request()->segments()[1].'/'.request()->segments()[2] : ( (array_key_exists(1, request()->segments()) && request()->segments()[1]) ? '/'.request()->segments()[1] : '/');
              break;
            default:
              break;
          }
        }
        $base_url = url('/');
        $menu = $menu ?? [];
        echo "<ul class='".$nav_class."' ".$nav_indicator.">";

        foreach ($menu as $item_key => $item) {
            $view_status = array_key_exists('view_status', $item) ? $item['view_status'] : true;
            if (!$view_status) {
              continue;
            }
            $li_active_class =  setLiActiveClass($item, $base_url,  $request_url_segments);
            if($request_url_segments == '/' || $request_url_segments == 'dashboard') {
                $li_active_class = isset($item['default']) && $item['default'] == true ? 'active' : '';
            }

            if ($item['url']) {
            echo "<li class='".$li_active_class."'>";
                $icon = $item['icon'] !== '' ? $item['icon'] :'&#x2022;';
                echo "<a href='".$item['url']."' style='padding-left: ".$nav_padding_left."rem;'>";
                echo "<span class='nav-icon'>";
                echo "<i class='material-icons' aria-hidden='true'>$icon</i>";
                echo "</span>";
                echo "<span class='nav-text'>".$item['title']."</span>";
                echo "</a>";
            } else {
                $inner_icon = $item['icon'] !== '' ? $item['icon'] :'&#xe145;';
                $class = array_key_exists('class', $item) ? $item['class'] : '';
                if ($class) {
                    echo "<li class='".$item['class']."'>";
                    echo "<small class='text-muted'>".$item['title']."</small>";
                } else {
                    echo "<li class='".$li_active_class."'>";
                    echo "<a style='padding-left: ".$nav_padding_left."rem;'>";
                    echo "<span class='nav-caret'>";
                    echo "<i class='fa fa-caret-down'></i>";
                    echo "</span>";
                    echo "<span class='nav-icon'>";
                    echo "<i class='material-icons'>$inner_icon</i>";
                    echo "</span>";
                    echo "<span class='nav-text'>".$item['title']."</span>";
                    echo "</a>";
                }

            }
            if (count($item['items'] ?? [])) {
                print_menu($item['items'], 'nav-sub', 1.80);
            }
            echo "</li>";
        }
        echo "</ul>";
    }

    $menus = collect(session('menu'))->where('view_status', true);

    echo '<ul><li class="nav-header" style="margin-bottom: -13px;">
            <small class="text-muted">Main Menu</small>
        </li></ul>';

    print_menu($menus, 'nav', 0.55, 'ui-nav');
@endphp
