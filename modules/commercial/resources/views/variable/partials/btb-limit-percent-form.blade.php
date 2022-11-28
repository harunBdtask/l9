<fieldset class="scheduler-border">
    <legend class="scheduler-border">BTB Limit %</legend>

    <div class="row form-group table-responsive">
        <div class="col-md-10">
            <table class="reportTable mainForm">

                <tbody>
                {!! Form::hidden('id', $variable->id ?? null) !!}

                <tr>
                    <th style="min-width: 150px"><label for="value">BTB Limit %<span class="text-danger req">*</span></label></th>
                    <td style="min-width: 200px">{!! Form::text('value', $variable->value ?? null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'data-parsley-required', 'id' => 'value']) !!}</td>

                </tr>

                <tr>
                    <th></th>
                    <td>
                        <button class="btn btn-primary btn-sm btn-block" id="submit-btn" value="{{ $variable ? 'UPDATE' : 'SAVE' }}">
                            <i class="glyphicon glyphicon-floppy-open" ></i>
                            {{ $variable ? 'UPDATE' : 'SAVE' }}
                        </button>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

    </div>
</fieldset>
