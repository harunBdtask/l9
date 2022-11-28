@extends('skeleton::layout')
@section("title","Financial Parameters")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $parameter ? 'Update Financial Parameter' : 'New Financial Parameter' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($parameter, ['url' => $parameter ? 'financial-parameter-setups/'.$parameter->id : 'financial-parameter-setups', 'method' => $parameter ? 'PUT' : 'POST']) !!}
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="factory_id">Company</label>
                                    {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm c-select', 'id' => 'factory_id', 'placeholder' => 'Select Company', 'required' => 'required']) !!}
                                </div>
                                @error('factory_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="applying_period">Applying Period</label>
                                    {!! Form::text('applying_period', $parameter ? date_format(date_create($parameter->date_from), 'd/m/Y') . ' - ' . date_format(date_create($parameter->date_to), 'd/m/Y') : null, ['class' => 'form-control form-control-sm date-range', 'id' => 'applying_period', 'placeholder' => 'Applying Period']) !!}
                                </div>
                                @error('applying_period')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="working_day">Working Day</label>
                                    {!! Form::number('working_day', null, ['step' => '.1', 'class' => 'form-control form-control-sm cal', 'id' => 'working_day', 'placeholder' => 'Working Day']) !!}
                                </div>
                                @error('working_day')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="bep_cm">BEP CM %</label>
                                    {!! Form::number('bep_cm', null, ['step' => '.1', 'class' => 'form-control form-control-sm', 'id' => 'bep_cm', 'placeholder' => 'BEP CM']) !!}
                                </div>
                                @error('bep_cm')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="asking_profit">Asking Profit %</label>
                                    {!! Form::number('asking_profit', null, ['step' => '.1', 'class' => 'form-control form-control-sm', 'id' => 'asking_profit', 'placeholder' => 'Asking Profit']) !!}
                                </div>
                                @error('asking_profit')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="factory_machine">No. of Company Machine</label>
                                    {!! Form::number('factory_machine', null, ['step' => '.1', 'class' => 'form-control form-control-sm cal', 'id' => 'factory_machine', 'placeholder' => 'No. of Factory Machine']) !!}
                                </div>
                                @error('factory_machine')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="monthly_cm_expense">Monthly CM Expense</label>
                                    {!! Form::number('monthly_cm_expense', null, ['step' => '.1', 'class' => 'form-control form-control-sm cal', 'id' => 'monthly_cm_expense', 'placeholder' => 'Monthly CM Expense']) !!}
                                </div>
                                @error('monthly_cm_expense')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="working_hour">Working Hour</label>
                                    {!! Form::number('working_hour', null, ['step' => '.1', 'class' => 'form-control form-control-sm cal', 'id' => 'working_hour', 'placeholder' => 'Working Hour']) !!}
                                </div>
                                @error('working_hour')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="cost_per_minute">Cost Per Minute</label>
                                    {!! Form::number('cost_per_minute', null, ['step' => 'any', 'class' => 'form-control form-control-sm', 'id' => 'cost_per_minute', 'placeholder' => 'Cost Per Minute', 'required' => 'required']) !!}
                                </div>
                                @error('cost_per_minute')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="actual_cm">Actual CM</label>
                                    {!! Form::number('actual_cm', null, ['step' => '.1', 'class' => 'form-control form-control-sm', 'id' => 'actual_cm', 'placeholder' => 'Actual CM']) !!}
                                </div>
                                @error('actual_cm')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="asking_avg_rate">Asking AVG Rate</label>
                                    {!! Form::number('asking_avg_rate', null, ['step' => '.1', 'class' => 'form-control form-control-sm', 'id' => 'asking_avg_rate', 'placeholder' => 'Asking AVG Rate']) !!}
                                </div>
                                @error('asking_avg_rate')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="max_profit">Max Profit %</label>
                                    {!! Form::number('max_profit', null, ['step' => '.1', 'class' => 'form-control form-control-sm', 'id' => 'max_profit', 'placeholder' => 'Max Profit']) !!}
                                </div>
                                @error('max_profit')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="depreciation_amortization">Depreciation & Amortization %</label>
                                    {!! Form::text('depreciation_amortization', null, ['class' => 'form-control form-control-sm', 'id' => 'depreciation_amortization', 'placeholder' => 'Depreciation & Amortization']) !!}
                                </div>
                                @error('depreciation_amortization')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="interest_expenses">Interest Expenses %</label>
                                    {!! Form::number('interest_expenses', null, ['step' => '.1', 'class' => 'form-control form-control-sm', 'id' => 'interest_expenses', 'placeholder' => 'Interest Expenses']) !!}
                                </div>
                                @error('interest_expenses')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="income_tax">Income Tax %</label>
                                    {!! Form::number('income_tax', null, ['step' => '.1', 'class' => 'form-control form-control-sm', 'id' => 'income_tax', 'placeholder' => 'Income Tax']) !!}
                                </div>
                                @error('income_tax')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    {!! Form::select('status', ['Active' => 'Active', 'In Active' => 'In Active'], null, ['class' => 'form-control form-control-sm form-control form-control-sm-lg c-select', 'id' => 'status']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm white"><i
                                            class="fa fa-save"></i> {{ $parameter ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-sm btn-dark" href="{{ url('financial-parameter-setups') }}"><i class="fa fa-remove"></i>
                                        Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script>
        $(document).on('change', '#applying_period', function () {
            let range = $(this).val();
            $.ajax({
                method: 'get',
                data: {
                    range
                },
                url: '{{ url('financial-parameter-setups/working-day-count') }}',
                success: function (result) {
                    $('#working_day').val(result);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        $(document).on('input', '.cal', function () {
            let monthlyExpress = parseInt($('#monthly_cm_expense').val());
            let workingDay = parseInt($('#working_day').val());
            let machine = parseInt($('#factory_machine').val());
            let workingHour = parseInt($('#working_hour').val());
            let costPerMinute = monthlyExpress / (workingDay * machine * workingHour * 60);
            $('#cost_per_minute').val(costPerMinute.toFixed(2));
            console.log(costPerMinute);
        });
    </script>
@endpush
