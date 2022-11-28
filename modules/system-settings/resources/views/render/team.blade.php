<form action="{{ url('/teams/update') }}/{{ $team->team_name }}" method="post">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="team_name">Team Name</label>
                <input type="text" name="team_name" id="team_name" value="{{ $team->team_name }}"
                       placeholder="Team Name"
                       class="form-control form-control-sm form-control form-control-sm-sm" required>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="short_name">Short Name</label>
                <input type="text" name="short_name" id="short_name" value="{{ $team->short_name }}"
                       placeholder="Short Name"
                       class="form-control form-control-sm form-control form-control-sm-sm" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="project_type">Project Type</label>
                <select name="project_type" id="project_type"
                        class="form-control form-control-sm form-control form-control-sm-sm" required>
                    <option value="">Project Type</option>
                    @foreach($projectTypes as $projectType)
                        <option
                            value="{{ $projectType }}" {{ $projectType == $team->project_type ? 'selected' : null }}>{{ $projectType }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control form-control-sm form-control form-control-sm-sm">
                    <option value="Active" {{ $team->status == 'Active' ? 'selected' : null }}>Active</option>
                    <option value="In Active" {{ $team->status == 'In Active' ? 'selected' : null }}>In Active</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="associate_with">Associate With</label>
                {!! Form::select('associate_with[]', $factories, $associateWith ?? [], [
                        'class' => 'form-control form-control-sm c-select form-control form-control-sm-sm',
                        'id' => 'associate_with',
                        'multiple' => 'multiple'
                ]) !!}
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
                    <th>Member</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>
                            <select name="member_id[]" id="member_id"
                                    class="form-control form-control-sm form-control form-control-sm-sm">
                                <option value="">Select Member</option>
                                @foreach($users as $user)
                                    <option
                                        value="{{ $user->id }}" {{ $user->id == $member['id'] ? 'selected' : null }}>{{ $user->first_name }} {{ $user->last_name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="role[]" id="role" class="form-control form-control-sm form-control form-control-sm-sm">
                                <option value="Leader" {{ $member['role'] == 'Leader' ? 'selected' : null }}>Leader
                                </option>
                                <option value="Member" {{ $member['role'] == 'Member' ? 'selected' : null }}>Member
                                </option>
                            </select>
                        </td>
                        <td>
                            <i style="cursor: pointer"
                               class="fa fa-plus element_add btn btn-sm btn-primary"></i>
                            <i style="cursor: pointer" class="fa fa-minus element_remove btn btn-sm btn-warning"></i>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-right">
                <a href="{{ url('/teams') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                <button type="submit" id="action_button" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        $("#associate_with").select2();
    });
</script>
