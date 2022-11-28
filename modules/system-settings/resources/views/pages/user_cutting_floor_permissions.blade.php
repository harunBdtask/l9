@extends('skeleton::layout')
@section('title', 'Cutting Plan Permission')
@section('content')
  <div class="padding">
    <div class="box user-cutting-plan-permission-page">
      <div class="box-header">
        <h2>Cutting Plan Permission List</h2>
      </div>
      <div class="box-body b-t">
        <div class="table-responsive">
          <table class="reportTable">
            <thead>
            <tr>
              <th>SL</th>
              <th>Company</th>
              <th>Cutting Floor</th>
              <th>Permitted User Email</th>
              <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @if($user_cutting_floor_permissions->count())
              @foreach($user_cutting_floor_permissions as $permission)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $permission->factory->factory_name ?? '' }}</td>
                  <td>{{ $permission->floor_no ?? '' }}</td>
                  <td>
                    <span class="email-view">{{ $permission->email ?? '' }}</span>
                    <span class="email-input hide">
                                                {!! Form::hidden('cutting_floor_id', $permission->id ?? null, ['class' => 'cutting_floor_id']) !!}
                      {!! Form::select('user_id', $permission->users ?? [], $permission->user_id ?? null, ['class' => 'form-control form-control-sm user-email-input', 'placeholder' => 'Please Select One']) !!}
                                            </span>
                  </td>
                  <td>
                    @if(Session::has('permission_of_cutting_plan_permission_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                      <button class="edit-button btn btn-sm btn-success">Edit</button>
                      <button class="submit-button btn btn-sm btn-success hide">Submit</button>
                      <button class="cancel-button btn btn-sm btn-warning hide">Cancel</button>
                    @endif
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3" align="center">No Data
                </td>
              </tr>
            @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('script-head')
  <script src="{{ asset('modules/system-settings/js/user_cutting_floor_permissions.js') }}"></script>
@endpush
