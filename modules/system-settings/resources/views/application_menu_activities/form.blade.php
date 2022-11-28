@php
  function print_menu($menu) {

    $base_url = url('/');
    $menu = $menu ?? [];
    echo "<ul>";

    foreach ($menu as $item) {
        $title = $item['title'] ?? '';
        $url = $item['url'] ?? '';
        $priority = (is_array($item) && array_key_exists('priority', $item)) ? $item['priority'] : '';
        $view_status = (is_array($item) && array_key_exists('view_status', $item)) ? $item['view_status'] : true;
        $checked = !$view_status ? 'checked' : '';

        echo "<li data-priority=\"$priority\">";
        echo "<span class=\"title\" data-priority=\"$priority\" data-url=\"$url\">$title</span>";
        echo "<span style=\"margin-left: 5px;\"><input class=\"app-menu-checkbox\" type=\"checkbox\" data-title=\"$title\" data-url=\"$url\" data-priority=\"$priority\" $checked></span>";

        if (count($item['items'] ?? [])) {
          print_menu($item['items']);
        }
        echo "</li>";
    }
    echo "</ul>";
  }

@endphp
<div class="row">
  <div class="col-md-12">
    <div class="table-responsive">
      @php
        print_menu($menus);
      @endphp
    </div>
  </div>
</div>