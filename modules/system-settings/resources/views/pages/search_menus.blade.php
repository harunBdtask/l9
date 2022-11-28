@extends('skeleton::layout')
@section('title', 'Menu')
@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>Menu List</h2>
    </div>
    <div class="box-body b-t">
      @if(Session::has('permission_of_menus_add') || getRole() == 'super-admin')
        <a class="btn btn-sm white m-b" href="{{ url('menus/create') }}">
          <i class="glyphicon glyphicon-plus"></i> New Menu
        </a>
      @endif
        <div class="pull-right">
            <form action="{{ url('/search-menus') }}" method="GET">
                <div class="pull-left" style="margin-right: 10px;">
                    <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
                </div>
                <div class="pull-right">
                    <input type="submit" class="btn btn-sm white" value="Search">
                </div>
            </form>
        </div>
    </div>
    <table class="reportTable">
      <thead>
      <tr>
        <th>SL</th>
        <th>Menu's Name</th>
        <th>Menu's Url</th>
        <th>Module's Name</th>
        <th>Sub Module's Name</th>
        <th>Is Left Menu</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      @if($menus)
        @foreach($menus->getCollection() as $menu)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $menu->menu_name }}</td>
            <td>{{ $menu->menu_url }}</td>
            <td>{{ $menu->module->module_name ?? 'N/A' }}</td>
            <td>{{ $menu->sub_module->menu_name ?? 'N/A' }}</td>
            <td style="color: {{ $menu->left_menu == 1 ? 'green' : 'red' }};">{{ $menu->left_menu == 1 ? 'Yes' : 'No' }}</td>
            <td>
              @if(Session::has('permission_of_menus_edit') || getRole() == 'super-admin')
                <a class="btn btn-sm white" href="{{ url('menus/'.$menu->id.'/edit') }}"><i class="fa fa-edit"></i></a>
              @endif
              @if(Session::has('permission_of_menus_delete') || getRole() == 'super-admin')
                <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('menus/'.$menu->id) }}">
                  <i class="fa fa-times"></i>
                </button>
              @endif
            </td>
          </tr>
        @endforeach
      @else
        <tr>
          <td colspan="6" align="center">No Menus<td>
        </tr>
      @endif
      </tbody>
      <tfoot>
      @if($menus->total() > 15)
        <tr>
          <td colspan="6" align="center">{{ $menus->appends(request()->except('page'))->links() }}</td>
        </tr>
      @endif
      </tfoot>
    </table>
  </div>
</div>
@endsection
