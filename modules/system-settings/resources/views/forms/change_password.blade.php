@extends('skeleton::layout')
@section('title', 'Change Password')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Change Password</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">

                        @include('partials.response-message')

                        <form action="{{ url('/change-password-post') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{Auth::user()->id}}">
                            <div class="form-group">
                                <label for="current_password">Current
                                    Password</label>
                                <input type="password" name="current_password" class="form-control form-control-sm"
                                       placeholder="Enter your current password here" required>
                                @if($errors->has('current_password'))
                                    <span class="text-danger">{{ $errors->first('current_password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="new_password">New
                                    Password</label>
                                <input type="password" name="new_password" class="form-control form-control-sm"
                                       placeholder="Enter your new password here" required>
                                @if($errors->has('new_password'))
                                    <span class="text-danger">{{ $errors->first('new_password') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm
                                    Password</label>
                                <input type="password" name="confirm_password" class="form-control form-control-sm"
                                       placeholder="Enter your confirm password here" required>
                                @if($errors->has('confirm_password'))
                                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                @endif
                            </div>
                            <div class="form-group m-t-md">
                                <button type="submit" class="btn btn-sm white">Submit</button>
                                <button type="button" class="btn btn-sm btn-dark">
                                    <a href="{{ url('/') }}">Cancel</a>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
