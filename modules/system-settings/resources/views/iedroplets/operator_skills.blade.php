@extends('skeleton::layout')
@section('title', 'Operator Skill')

@section('content')
  <div class="padding">
    <div class="box" >
      <div class="box-header">
        <h2>Skills List</h2>
      </div>
      <div class="box-body b-t">
        @if(Session::has('permission_of_operator_skill_add') || getRole() == 'super-admin')
          <a class="btn btn-sm white m-b" href="{{ url('operator-skill/create') }}">
            <i class="glyphicon glyphicon-plus"></i>Add New Skill
          </a>
        @endif
        <div class="pull-right">
          <form action="{{ url('/search-operator-skill') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm white" value="Search">
            </div>
          </form>
        </div>
        <hr>
        <table class="reportTable">
          <thead>
          <tr>
            <th width="20%">SL</th>
            <th width="60%">Skill Name</th>
            <th width="20%">Actions</th>
          </tr>
          </thead>
          <tbody>
          @if(!$parts->getCollection()->isEmpty())
            @foreach($parts->getCollection() as $part)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $part->name }}</td>
                <td>
                  @if(Session::has('permission_of_operator_skill_edit') || getRole() == 'super-admin')
                    <a class="btn btn-sm white" href="{{ url('operator-skill/'.$part->id.'/edit') }}"><i
                          class="fa fa-edit"></i></a>
                  @endif
                  @if(getRole() == 'super-admin' || Session::has('permission_of_operator_skill_delete'))
                    <button type="button" class="btn btn-sm white show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                            data-url="{{ url('operator-skill/'.$part->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr style="height: 40px">
              <td colspan="3" align="center" class="text-danger">No Operator Skills</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($parts->total() > 15)
            <tr>
              <td colspan="3" align="center">{{ $parts->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
