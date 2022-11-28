@extends('skeleton::layout')
@section("title","Member")
@section('content')

<div class="padding">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box" >
                <div class="box-header">
                    <h2>{{ $member ? 'Update Member' : 'New Member' }}</h2>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            {!! Form::model($member, ['url' => $member ? 'team'. '/' . request()->segment(2) .'/members/' . $member->id : 'team'. '/' . request()->segment(2) .'/members', 'method' => $member ? 'PUT' : 'POST']) !!}
                            {!! Form::hidden('team_id', request()->segment(2)) !!}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="member_id">Member</label>
                                        {!! Form::select('member_id', $users, null, ['class' => 'form-control form-control-sm', 'id' => 'member_id', 'placeholder' => 'Select Member']) !!}
                                        @if($errors->has('member_id'))
                                            <span class="text-danger">{{ $errors->first('member_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        {!! Form::select('status', ['Active' => 'Active', 'In Active' => 'In Active'], null, ['class' => 'form-control form-control-sm', 'id' => 'status']) !!}
                                        @if($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="text-right">
                                            <button type="button" class="btn btn-danger"><a href="{{ url('members') }}"><i class="fa fa-remove"></i> Cancel</a></button>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> {{ $member ? 'Update' : 'Create' }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($members as $member)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $member->member->first_name }} {{ $member->member->last_name }}</td>
                                        <td>{{ $member->member->email }}</td>
                                        <td>{{ $member->member->designation }}</td>
                                        <td>
                                            <a class="btn btn-xs btn-warning" href="{{ url('/members/' . $member->id .'/edit') }}"><i class="fa fa-edit"></i></a>
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('members/'.$member->id) }}">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
