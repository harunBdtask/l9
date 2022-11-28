@extends('skeleton::layout')
@section('title', 'Sewing Line Tasks')
@section('content')
  <div class="padding">
    <div class="box" >
      <div class="box-header">
        <h2>Task List</h2>
      </div>
      <div class="box-body b-t">
        @if(Session::has('permission_of_sewing_line_tasks_add') || getRole() == 'super-admin')
          <a class="btn btn-sm white m-b" href="{{ url('tasks/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Task
          </a>
        @endif
        <div class="pull-right">
          <form action="{{ url('search-tasks') }}" method="GET">
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
            <th>Task Name</th>
            <th>Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(!$tasks->getCollection()->isEmpty())
            @foreach($tasks->getCollection() as $task)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $task->name }}</td>
                <td>
                  @if(Session::has('permission_of_sewing_line_tasks_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white" href="{{ url('tasks/'.$task->id.'/edit') }}"><i
                          class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_sewing_line_tasks_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                            data-url="{{ url('tasks/'.$task->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr style="height: 40px">
              <td colspan="3" class="text-danger" align="center">No Tasks</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($tasks->total() > 15)
            <tr>
              <td colspan="4" align="center">{{ $tasks->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
