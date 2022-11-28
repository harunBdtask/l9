@extends('skeleton::layout')
@section('title', 'Guide or Folders')

@section('content')
  <div class="padding">
    <div class="box" >
      <div class="box-header">
        <h2>Guide or Folders List</h2>
      </div>
      <div class="box-body b-t">
        @if(Session::has('permission_of_guide_or_folders_add') || getRole() == 'super-admin'|| getRole() == 'admin')
          <a class="btn btn-sm white m-b" href="{{ url('guide-or-folders/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Guide or Folders
          </a>
        @endif
        <div class="pull-right">
          <form action="{{ url('search-guide-or-folders') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm white" value="Search">
            </div>
          </form>
        </div>
        <hr>
        @include('partials.response-message')
        <table class="reportTable">
          <thead>
          <tr>
            <th>SL</th>
            <th>Name</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(!$guide_or_folders->getCollection()->isEmpty())
            @foreach($guide_or_folders->getCollection() as $folder)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $folder->name }}</td>
                <td>
                  @if(Session::has('permission_of_guide_or_folders_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white" href="{{ url('guide-or-folders/'.$folder->id.'/edit') }}"><i
                          class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_guide_or_folders_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                            data-url="{{ url('guide-or-folders/'.$folder->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr style="height: 40px">
              <td colspan="3" class="text-danger" align="center">No Guide or Folders</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($guide_or_folders->total() > 15)
            <tr>
              <td colspan="4" align="center">{{ $guide_or_folders->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
