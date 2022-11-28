{!! Form::model($variable, ['url' => !$variable ? 'commercial/commercial-variable' : 'commercial/commercial-variable/' . $variable->id, 'method' => !$variable ? 'post' : 'put', 'id' => 'form' ]) !!}

<div class="row mainForm">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="selectFactory">Company Name</label><span class="text-danger req">*</span>
                    {{ Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'selectFactory']) }}   </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="selectVariable">Variable List</label>
                    {{ Form::select('variable_name', \SkylarkSoft\GoRMG\Commercial\Options::VARIABLE_NAME, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'selectVariable']) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" >
    <div class="col-md-10 col-md-offset-1" id="variable-details">
    </div>
</div>

{!! Form::close() !!}
