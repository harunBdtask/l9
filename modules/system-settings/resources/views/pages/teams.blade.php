@extends('skeleton::layout')
@section("title","Teams")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Teams</h2>
            </div>
            <div class="box-body">
                <div class="sr-only">
                    <table>
                        <tbody id="target_data">
                        <tr>
                            <td>
                                <select name="member_id[]" id="member_id"
                                        class="form-control form-control-sm">
                                    <option value="">Select Member</option>
                                    @foreach($users as $user)
                                        <option
                                            value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="role[]" id="role" class="form-control form-control-sm">
                                    <option value="Leader">Leader</option>
                                    <option value="Member">Member</option>
                                </select>
                            </td>
                            <td>
                                <i style="cursor: pointer"
                                   class="fa fa-plus element_add btn btn-sm btn-primary"></i>
                                <i style="cursor: pointer"
                                   class="fa fa-minus element_remove btn btn-sm btn-warning"></i>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5" id="team_form">
                        <form action="{{ url('/teams') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="team_name">Team Name <span class="text-warning">*</span> </label>
                                        <input type="text" name="team_name" id="team_name" placeholder="Team Name"
                                               class="form-control form-control-sm" value="{{old('team_name')}}" required>
                                        @if($errors->has('team_name'))
                                            <span class="text-danger">{{ $errors->first('team_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="short_name">Short Name <span class="text-warning">*</span> </label>
                                        <input type="text" name="short_name" id="short_name" placeholder="Short Name"
                                               class="form-control form-control-sm" value="{{old('short_name')}}" required>
                                        @if($errors->has('team_name'))
                                            <span class="text-danger">{{ $errors->first('team_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="project_type">Project Type <span class="text-warning">*</span></label>
                                        <select name="project_type" id="project_type"
                                                class="form-control form-control-sm" required>
                                            <option value="">Project Type</option>
                                            @foreach($projectTypes as $projectType)
                                                <option value="{{ $projectType }}">{{ $projectType }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('project_type'))
                                            <span class="text-danger">{{ $errors->first('project_type') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-warning">*</span></label>
                                        <select name="status" id="status" class="form-control form-control-sm">
                                            <option value="Active">Active</option>
                                            <option value="In Active">In Active</option>
                                        </select>
                                        @if($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="associate_with">Associate With</label>
                                        {!! Form::select('associate_with[]', $factories, $associateWith ?? [], ['class' => 'form-control form-control-sm select2-input c-select form-control form-control-sm-sm', 'id' => 'associate_with', 'multiple' => 'multiple']) !!}
                                        @if($errors->has('associate_with'))
                                            <span class="text-danger">{{ $errors->first('associate_with') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered team-form">
                                        <thead>
                                        <tr>
                                            <th>Member<span class="text-warning">*</span></th>
                                            <th>Role<span class="text-warning">*</span></th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="team_member_add">

                                        @if (Session::has('_old_input'))
                                            @for ($i=0; $i<count(Session::get('_old_input.member_id')); $i++)
                                                <tr>
                                                    <td>
                                                        <select name="member_id[]" id="member_id"
                                                                class="form-control form-control-sm">
                                                            <option value="">Select Member</option>
                                                            @foreach($users as $user)
                                                                <option
                                                                    value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if($errors->any() && Session::get('errors')->has('member_id.' . $i))
                                                            <p class="text-danger">{{Session::get('errors')->first('member_id.' . $i)}}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <select name="role[]" id="role"
                                                                class="form-control form-control-sm">
                                                            <option value="Leader">Leader</option>
                                                            <option value="Member">Member</option>
                                                        </select>
                                                        @if($errors->any() && Session::get('errors')->has('role.' . $i))
                                                            <p class="text-danger">{{Session::get('errors')->first('role.' . $i)}}</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <i style="cursor: pointer"
                                                           class="fa fa-plus element_add btn btn-sm btn-primary"></i>
                                                        <i style="cursor: pointer"
                                                           class="fa fa-minus element_remove btn btn-sm btn-warning"></i>
                                                    </td>
                                                </tr>
                                            @endfor
                                        @else
                                            <tr>
                                                <td>
                                                    <select name="member_id[]" id="member_id"
                                                            class="form-control form-control-sm">
                                                        <option value="">Select Member</option>
                                                        @foreach($users as $user)
                                                            <option
                                                                value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="role[]" id="role"
                                                            class="form-control form-control-sm">
                                                        <option value="Leader">Leader</option>
                                                        <option value="Member">Member</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <i style="cursor: pointer"
                                                       class="fa fa-plus element_add btn btn-sm btn-primary"></i>
                                                    <i style="cursor: pointer"
                                                       class="fa fa-minus element_remove btn btn-sm btn-warning"></i>
                                                </td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>
                                    <div class="form-group">
                                        <button type="submit" id="action_button" class="btn btn-sm btn-success"><i
                                                class="fa fa-save"></i>
                                            Save
                                        </button>
                                        <a href="{{ url('/teams') }}" class="btn btn-sm white"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            {!! Form::open(['url' => 'teams', 'method' => 'GET']) !!}
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="q"
                                       value="{{ request('q') ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm white m-b" type="submit">Search</button>
                                </span>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Team Name</th>
                                <th>Short Name</th>
                                <th>Project Type</th>
                                <th>Member</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($teams as $team)
                                @foreach($team->members as $member)
                                    <tr>
                                        @if($loop->first)
                                            <td rowspan="{{ count($team->members) }}">{{ $team->team_name }}</td>
                                            <td rowspan="{{ count($team->members) }}">{{ $team->short_name }}</td>
                                            <td rowspan="{{ count($team->members) }}">{{ $team->project_type }}</td>
                                        @endif
                                        <td>{{ $member['name'] }}</td>
                                        <td>{{ $member['role'] }}</td>
                                        @if($loop->first)
                                            <td align="center" rowspan="{{ count($team->members) }}">
                                                <i style="cursor: pointer" data-name="{{ $team->team_name }}"
                                                   class="fa fa-edit team_edit"></i>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td align="center" colspan="4">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{--                        <div class="text-center">--}}
                        {{--                            {{ $teams->appends(request()->except('page'))->links() }}--}}
                        {{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        $(document).on('click', '.element_add', function () {
            $('.team-form').find('tbody:last').append($('#target_data').html());
        });

        $(document).on('click', '.element_remove', function () {
            let length = $('.team-form tr').length;
            if (length < 3) {
                alert('Last row can`t be deleted');
                return false;
            }
            $(this).closest('tr').remove();
        });

        $(document).on('click', '.team_edit', function () {
            let teamName = $(this).data('name');
            $.ajax({
                method: 'get',
                url: '{{ url('teams') }}/' + teamName + '/edit',
                success: function (result) {
                    $('#team_form').html('').html(result);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        });
    </script>
@endpush
