@extends('skeleton::layout')
@section('title', 'User')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      @if(getRole() == 'super-admin' || getRole() == 'admin')
      <div class="box">
        <div class="box-header">
          <h2>{{ $user ? 'Update User' : 'New User' }}</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          {!! Form::model($user, ['url' => $user ? 'users/'.$user->id : 'users', 'method' => $user ? 'PUT' : 'POST',
          'autocomplete' => 'off']) !!}

          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label for="name">First Name</label>
                {!! Form::text('first_name', null, ['class' => 'form-control form-control-sm', 'id' => 'first_name',
                'placeholder' => 'Write first name here']) !!}

                @if($errors->has('first_name'))
                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                @endif
              </div>

            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="last_name">Last Name</label>
                {!! Form::text('last_name', null, ['class' => 'form-control form-control-sm', 'id' => 'last_name',
                'placeholder' => 'Write user last name here']) !!}

                @if($errors->has('last_name'))
                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                @endif
              </div>

            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="email">E-mail</label>
                {!! Form::email('email', null, ['class' => 'form-control form-control-sm', 'id' => 'email',
                'placeholder' => 'Write user email here']) !!}

                @if($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label for="last_name">Phone No.</label>
                {!! Form::text('phone_no', null, ['class' => 'form-control form-control-sm', 'id' => 'phone_no',
                'placeholder' => 'Write user phone no here']) !!}

                @if($errors->has('phone_no'))
                <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                @endif
              </div>

            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="last_name">Designation</label>
                {!! Form::text('designation', null, ['class' => 'form-control form-control-sm', 'id' => 'designation',
                'placeholder' => 'Write user designation here']) !!}
              </div>

            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="department">Department</label>
                {!! Form::select('department', $departments, null, ['class' => 'form-control form-control-sm
                select2-input',
                'id' => 'department', 'placeholder' => 'Select a department']) !!}

                @if($errors->has('department'))
                <span class="text-danger">{{ $errors->first('department') }}</span>
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label for="factory">Company</label>
                {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm
                select2-input',
                'id' => 'factory', 'placeholder' => 'Select a factory']) !!}

                @if($errors->has('factory_id'))
                <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                @endif
              </div>

            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="country">Role</label>
                {!! Form::select('role_id', $roles, null, ['class' => 'form-control form-control-sm select2-input', 'id'
                =>
                'role', 'placeholder' => 'Select a role']) !!}

                @if($errors->has('role_id'))
                <span class="text-danger">{{ $errors->first('role_id') }}</span>
                @endif
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control form-control-sm" id="password" name="password"
                  placeholder="Give password here">
                <span style="color: red;">{{ $errors->first('password') }}</span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control form-control-sm" id="confirm_password"
                  name="confirm_password" placeholder="Give confirm password here">
                <span style="color: red;">{{ $errors->first('confirm_password') }}</span>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="address">Address</label>
                {!! Form::textarea('address', null, ['rows' => '2','class' => 'form-control form-control-sm', 'rows' =>
                1, 'id' => 'address', 'placeholder' => 'Write user address here']) !!}
              </div>
            </div>
            @if(getRole() == 'super-admin')
            <div class="col-lg-4">
              <div class="form-group">
                <label for="dashboard_version">Dashboard Version</label>
                {!! Form::select('dashboard_version', $dashboard_versions, null, ['class' => 'form-control
                form-control-sm select2-input', 'id' =>
                'role', 'placeholder' => 'Dashboard Version']) !!}

                @if($errors->has('dashboard_version'))
                <span class="text-danger">{{ $errors->first('dashboard_version') }}</span>
                @endif
              </div>
            </div>
            @endif
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> {{ $user ? 'Update' :
                  'Create' }}</button>
                <button type="button" class="btn btn-sm btn-warning"><a href="{{ url('users') }}"><i
                      class="fa fa-remove"></i> Cancel</a>
                </button>
              </div>

            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
