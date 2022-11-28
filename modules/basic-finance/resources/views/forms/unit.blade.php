@extends('finance::layout')

@section('title', ($unit ? 'Update Unit' : 'New Unit'))
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $unit ? 'Update Unit' : 'New Unit' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                {!! Form::model($unit, ['url' => $unit ? 'basic-finance/units/'.$unit->id : 'basic-finance/units', 'method' => $unit ? 'PUT' : 'POST', 'files' => true]) !!}
                                <div class="form-group">
                                    <label for="factory_id">Factory *</label>
                                    {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'factory_id']) !!}

                                    @if($errors->has('factory_id'))
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="bf_project_id">Project *</label>
{{--                                    {!! Form::select('bf_project_id', $projects, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'bf_project_id']) !!}--}}
                                    {!! Form::select('bf_project_id', $projects ?? [], $unit->bf_project_id ?? null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'bf_project_id', 'placeholder' => 'Select a Project']) !!}

                                    @if($errors->has('bf_project_id'))
                                        <span class="text-danger">{{ $errors->first('bf_project_id') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">Users *</label>
                                    {!! Form::select('user_ids[]', $users, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'user_ids', 'multiple']) !!}

                                    @if($errors->has('user_ids'))
                                        <span class="text-danger">{{ $errors->first('user_ids') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="unit">Unit *</label>
                                    {!! Form::text('unit', null, ['class' => 'form-control form-control-sm', 'id' => 'unit', 'placeholder' => 'Write unit name here']) !!}

                                    @if($errors->has('unit'))
                                        <span class="text-danger">{{ $errors->first('unit') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="unit_head_name">Name of Unit Head</label>
                                    {!! Form::text('unit_head_name', null, ['class' => 'form-control form-control-sm', 'id' => 'unit_head_name', 'placeholder' => 'Write name of unit head here']) !!}

                                    @if($errors->has('unit_head_name'))
                                        <span class="text-danger">{{ $errors->first('unit_head_name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="phone_no">Phone No</label>
                                    {!! Form::number('phone_no', null, ['class' => 'form-control form-control-sm', 'id' => 'phone_no', 'placeholder' => 'Write Phone no here']) !!}

                                    @if($errors->has('phone_no'))
                                        <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    {!! Form::email('email', null, ['class' => 'form-control form-control-sm', 'id' => 'email', 'placeholder' => 'Write Email here']) !!}

                                    @if($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group m-t-md">
                                    <button type="submit" class="btn btn-success">{{ $unit ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-danger" href="{{ url('basic-finance/units') }}">Cancel</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function(){
            $('#factory_id').on('change', function(){
                let companyId = $(this).val()
                fetchProjects(companyId);
            })
            function fetchProjects(companyId) {
                axios.get(`/basic-finance/api/v1/fetch-company-wise-projects/${companyId}`).then((response) => {
                    let projects = response.data;
                    $(`#bf_project_id`).find('option').not(':first').remove();
                    let options = [];
                    projects.forEach((project) => {
                        options.push([
                            `<option value="${project.id}" data-id="${project.id}" data-name="${project.text}">${project.text}</option>`
                        ].join(''));
                    });
                    $('#bf_project_id').append(options);
                });
            }
            $('#bf_project_id').on('change', function(){
                let projectId = $(this).val()
                let companyId = $(`#factory_id`).val()
                fetchUsers(companyId, projectId);
            })

            const userElement = $(`#user_ids`);
            function fetchUsers(companyId, projectId) {
                axios.get(`/basic-finance/api/v1/fetch-project-wise-users/${companyId}/${projectId}`)
                .then((response) => {
                    userElement.find('option').remove();
                    let users = response.data;
                    let options = [];
                    users.forEach((user) => {
                        options.push([
                            `<option value="${user.id}" data-id="${user.id}" data-name="${user.text}">${user.text}</option>`
                        ].join(''));
                    });
                    userElement.append(options);
                    userElement.select2('val', []);
                })
                .catch((error) => {
                    userElement.empty();
                })
            }
        });

    </script>
    @endsection
