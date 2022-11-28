@extends('skeleton::layout')
@section("title","Application Menu Hide Status")
@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Application Menu Hide Status</h2>
        @if(getRole() == 'super-admin')
        <a href="{{ url('/garments-production-entry') }}" class="btn btn-sm btn-info"><i class="fa fa-arrow-right"></i> Go to Application Variable Page</a>
        @endif
      </div>
    </div>

    <div class="box-body">
      @if (getRole() != 'super-admin')
        <h3 class="text-danger text-center">Permission Denied</h3>
      @else
      <h6 class="small text-danger">N.B: Check to hide menus from the navbar!</h6>
        {!! Form::open(['url' => url('/application-menu-inactive'), 'method' => 'POST', 'id' => 'app-menu-form', 'autocomplete' => 'off']) !!}
        <div id="form">

        </div>
        <div class="row">
          <div class="col-md-12">
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
          </div>
        </div>
        {!! Form::close() !!}
      @endif
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var menus;
  var menu_urls;
  var id;
  var baseUrl = window.location.protocol + "//" + window.location.host + "/"
  $(function() {
    fetchData()
  })

  function fetchData()
  {
    $('#form').empty()
    $.ajax({
      type: 'GET',
      url : '/application-menu-inactive/fetch-data'
    }).done(function(response) {
      $('#form').html(response.html)
      setMenus(response.session_menus)
      setUrls(response.inactive_urls)
      setId(response.id)
    }).fail(function(res) {
      console.log(res)
    });
  }

  $(document).on('submit', '#app-menu-form', function(e) {
    e.preventDefault();
    let form = $(this);
    let type = form.attr('method')
    let url = form.attr('url')
    let data = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      id: id,
      inactive_menus: menus,
      inactive_urls: menu_urls,
    }
    showLoader()
    $.ajax({
      type: type,
      url: url,
      data: data
    }).done(function(response) {
      hideLoader()
      toastr.success(response.message)
      fetchData()
    }).fail(function(res) {
      hideLoader()
      toastr.error('Given data is invalid!');
    })
  })

  function setId(serverId) {
    id = serverId
  }

  function setMenus(sessionMenus) {
    menus = sessionMenus.sort((a, b) => a.priority - b.priority)
  }

  function setUrls(inactiveUrls) {
    menu_urls = inactiveUrls
  }

  $(document).on('click', '.app-menu-checkbox', function (e) {
    let thisHtml = $(this)
    let isChecked = thisHtml.prop('checked')
    setMenuViewStatus(thisHtml, isChecked)
    setChildrensChecked(thisHtml, isChecked)
  })

  function setMenuViewStatus(thisHtml, isChecked)
  {
    let title = thisHtml.data('title');
    let url = thisHtml.data('url');
    let priority = thisHtml.data('priority');
    let parentMenuData = findParentMenuData(thisHtml)
    let menuKeys = parentMenuData.menuKeys || []
    let getMenu = getMenuNode(menus, menuKeys)
    getMenu.view_status = !isChecked
    if ((getMenu.url != undefined || getMenu.url != '') && getMenu.url) {
      let menuUrl = getMenu.url.replace(baseUrl, '')
      if (isChecked) {
        menu_urls.push(menuUrl)
      } else {
        var menuUrlIndex = menu_urls.indexOf(menuUrl);
        if (menuUrlIndex > -1) {
          menu_urls.splice(menuUrlIndex, 1);
        }
      }
    }
  }

  function setChildrensChecked(thisHtml, isChecked)
  {
    let ul = $(thisHtml.parents('li')[0]).children('ul')
    if(ul.length > 0) {
      let lis = $(ul[0]).children('li')
      if (lis.length > 0) {
        lis.each((key, element) => {
          let checkbox = $(element).find('.app-menu-checkbox');
          checkbox.prop('checked', isChecked);
          checkbox.each((k, elm) => {
            setMenuViewStatus($(elm), isChecked)
          })
          setChildrensChecked(checkbox)
        });
      }
    }
  }

  function getMenuNode(menuData, menuKeys, i = 0) {
    while(i < menuKeys.length) {
      menuData = menuData[menuKeys[i]];
      if (i < (menuKeys.length - 1)) {
        menuData = menuData['items'];
      }
      i++;
      return getMenuNode(menuData, menuKeys, i)
    }
    return menuData;
  }

  function findParentMenuData(thisHtml, level = 0, titles = [], menuKeys = []) {
    let curHtml = $(thisHtml.parents('li')[0]);
    let priority = curHtml.data('priority');
    let title = $(curHtml.children()[0]).text();
    let url = $(curHtml.children()[0]).data('url');
    
    let allSiblings = $(curHtml.parents('ul')[0]).children();
    if (allSiblings && allSiblings.length) {
      allSiblings.each((elmKey, elm) => {
        let menuElem = $($(elm).find('span.title')[0]);
        let thisTitle = menuElem.text();
        let thisPriority = menuElem.data('priority');
        let thisUrl = menuElem.data('url');
        if (thisTitle === title && thisPriority === priority && thisUrl === url) {
          menuKeys.push(elmKey)
        }
      })
    }
    titles.push(title);
    if (!priority) {
      level++
      return findParentMenuData(curHtml, level, titles, menuKeys)
    } else {
      return {
        priority: priority,
        level: level,
        titles: titles.reverse(),
        menuKeys: menuKeys.reverse()
      }
    }
  }
</script>
@endsection