@php
    function print_menu($url, $menu, $nav_class, $nav_padding_left, $nav_indicator = '') {
        $menu = $menu ?? [];

        echo "<ul class='".$nav_class."' ".$nav_indicator.">";
            foreach ($menu as $item) {
                echo "<li class='".(is_active($item, $url) ? 'active' : 'inactive')."'>";
                    if ($item['url']) {
                        echo "<a href='".$item['url']."' style='padding-left: ".$nav_padding_left."rem;'>";
                            echo "<span class='nav-icon'>";
                                echo "<i class='fa fa-hand-o-right' aria-hidden='true'></i>";
                            echo "</span>";
                            echo "<span class='nav-text'>".$item['title']."</span>";
                        echo "</a>";
                    } else {
                        echo "<a style='padding-left: ".$nav_padding_left."rem;'>";
                            echo "<span class='nav-caret'>";
                            echo "<i class='fa fa-caret-down'></i>";
                            echo "</span>";
                            echo "<span class='nav-icon'>";
                                echo "<i class='fa fa-plus-square'></i>";
                            echo "</span>";
                            echo "<span class='nav-text'>".$item['title']."</span>";
                        echo "</a>";
                    }

                    if (count($item['items'] ?? [])) {
                        print_menu($url, $item['items'], 'nav-sub', 1.80);
                    }
                echo "</li>";
            }
        echo "</ul>";
    }
    $menu = session('menu');
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    print_menu($url, $menu, 'nav', 0.55, 'ui-nav');

    function is_active($item, $url) : bool {
        if ($item['url'] && strstr($url, $item['url'])) {
            return true;
        }

        foreach ($item['items'] ?? [] as $item) {
            if ($item['url'] && strstr($url, $item['url'])) {
                return true;
            } else {
                is_active($item, $url);
            }
        }

        return false;
    }
@endphp
