@php
    function setLiActiveClass($items, $base_url, $request_url_first_segment) {
            if ($items['url']) {
                $item_route = str_replace($base_url.'/', '', $items['url']);
                return $request_url_first_segment == $item_route ? 'active' : '';
            }
            if (count($items['items']) == 1) {
                $item_route = str_replace($base_url.'/', '', $items['items'][0]['url']);
                return $request_url_first_segment == $item_route ? 'active' : '';
            } elseif (count($items['items']) > 1) {
                foreach ($items['items'] as $item) {
                    if (array_key_exists('items', $item) && count($item['items']) > 1) {
                        foreach ($item['items'] as $in_item) {
                             if (array_key_exists('items', $in_item) && count($in_item['items']) > 1) {
                                    foreach ($in_item['items'] as $inner_item) {
                                        $item_route = str_replace($base_url.'/', '', $inner_item['url']);
                                        if($request_url_first_segment == $item_route) {
                                                return 'active';
                                        }
                                    }
                            }
                            $item_route = str_replace($base_url.'/', '', $in_item['url']);
                            if($request_url_first_segment == $item_route) {
                                    return 'active';
                            }
                        }
                    }
                    $item_route = str_replace($base_url.'/', '', $item['url']);
                    if($request_url_first_segment == $item_route) {
                            return 'active';
                    }
                }
            } else {
                return '';
            }
    }
    function print_menu($menu, $nav_classs, $nav_padding_left, $nav_indicator = '') {
            $request_url_first_segment = request()->segments()[0] ?? '/';
            $base_url = url('/');
            $menu = $menu ?? [];
            echo "<ul class='".$nav_classs."' ".$nav_indicator.">";
                    foreach ($menu as $item) {
                          $li_active_class =  setLiActiveClass($item, $base_url,  $request_url_first_segment);
                            echo "<li class='".$li_active_class."'>";
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
                                            print_menu($item['items'], 'nav-sub', 1.80);
                                    }
                            echo "</li>";
                    }
            echo "</ul>";
    }
    print_menu(session('menu'), 'nav', 0.55, 'ui-nav');
@endphp
