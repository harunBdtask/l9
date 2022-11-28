<table class="reportTable">
    <thead>
    <tr>
        <th rowspan="2">Sl</th>
        <th rowspan="2">Module</th>
        <th rowspan="2">Sub Module</th>
        <th rowspan="2">Menu</th>
        <th colspan="{{ $permissions ? $permissions->count() : 1 }}">Permissions</th>
        <th>Action</th>
    </tr>
    <tr>
        @if ($permissions && $permissions->count())
            @foreach($permissions as $p_key => $permission)
                <th>
                  <div title="Select All {{ strtoupper($permission->permission_name) }}" style="cursor: pointer;" class="permission_names" id="permission-{{ $permission->permission_name }}">{{ strtoupper($permission->permission_name) }}</div>
                </th>
            @endforeach
        @else
            <th> No Permission Found</th>
        @endif
        <th>
            <button type="button" class="btn btn-xs btn-primary-outline check-all-module-permission-btn">Check/ Uncheck All Permission</button>
        </th>
    </tr>
    </thead>
    <tbody id="permission-assign-tbody">
    @if ($menus && $menus->count())
      @php
        $sl = 0;
      @endphp
        @foreach($menus->groupBy('module_id') as $menuByModule)
          @php
            $module_menu_count = $menuByModule->count();
            $module_id = $menuByModule->first()->module_id;
            $moduleName = $menuByModule->first()->module->module_name;
          @endphp
          @foreach($menuByModule->whereNull('submodule_id') as $menu)
            @php
              $submodule_id = $menu->id;
              $submenu_count = $menus->where('submodule_id', $submodule_id)->count() + 1;
              $module_name = $menu->module->module_name;
            @endphp
              <tr class="tr-height">
                  <td>{{ ++$sl }}</td>
                  @if($module_name == $moduleName)
                    <td rowspan="{{ $module_menu_count }}">{{ $module_name }}</td>
                  @endif
                  <td rowspan="{{ $submenu_count }}">
                      {{ $menu->menu_name }}
                      {!! Form::hidden('menu_id[]', $menu->id) !!}
                      <span class="text-danger menu_id"></span>
                  </td>
                  <td>{{ $menu->menu_name }}</td>
                  @if ($permissions && $permissions->count())
                      @foreach($permissions as $permission)
                          @php
                              $permission_id_name_attr = 'permission_id['.$module_id.']['. $menu->id.'][]';
                          @endphp
                          <td>
                              <label class="md-check">
                                  {!! Form::checkbox($permission_id_name_attr, $permission->id, null, ['class' => 'permission_check permission-'. $permission->permission_name]) !!}
                                  <i class="teal-200"></i>
                              </label>
                          </td>
                      @endforeach
                  @else
                      <td> No Permission Found</td>
                  @endif
                  <td>
                      <button type="button" class="btn btn-xs btn-warning-outline check-all-permission-btn">Check/ Uncheck Permission</button>
                  </td>
              </tr>
            @foreach($menuByModule->where('submodule_id', $submodule_id) as $submenu)
              @php
                $submodule_id = $submenu->id;
              @endphp
              @if(!$loop->first)
                <tr class="tr-height">
              @endif
                <td>{{ ++$sl }}</td>
                <td>{{ $submenu->menu_name }}</td>
                  @if ($permissions && $permissions->count())
                      @foreach($permissions as $permission)
                          @php
                              $permission_id_name_attr = 'permission_id['.$module_id.']['. $submenu->id.'][]';
                          @endphp
                          <td>
                              <label class="md-check">
                                  {!! Form::checkbox($permission_id_name_attr, $permission->id, null, ['class' => 'permission_check permission-'. $permission->permission_name]) !!}
                                  <i class="teal-200"></i>
                              </label>
                          </td>
                      @endforeach
                  @else
                      <td> No Permission Found</td>
                  @endif
                  <td>
                      <button type="button" class="btn btn-xs btn-warning-outline check-all-permission-btn">Check/ Uncheck Permission</button>
                  </td>
              </tr>
            @endforeach
            @php
              $moduleName = '';
            @endphp
          @endforeach
        @endforeach
    @endif
    </tbody>
</table>