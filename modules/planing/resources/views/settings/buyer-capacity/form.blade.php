@extends('skeleton::layout')
@section('title', 'Capacity')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box form-colors">
                    <div class="box-header">
                        <h2>{{ $buyerCapacity ? 'Update Buyer\'s Capacity' : 'New Buyer\'s Capacity' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($buyerCapacity, [
                            'url' => $buyerCapacity ? '/planning/settings/buyers-capacity/'.$buyerCapacity->id : '/planning/settings/buyers-capacity',
                            'method' => $buyerCapacity ? 'PUT' : 'POST'])
                        !!}
                        <div class="form-group">
                            <label for="name">Company</label>
                            {!! Form::select('factory_id', $factories ?? [], factoryId() ?? null, ['class' => 'form-control form-control-sm
                                    select2-input', 'id' => 'factory_id', 'placeholder' => "Select Factory"]) !!}

                            @if($errors->has('factory_id'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="name">Buyer's</label>
                            {!! Form::select('buyer_id', $buyers ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' =>
                                    'buyer_id', 'placeholder' => "Select Buyer"]) !!}
                            @if($errors->has('buyer_id'))
                                <span class="text-danger small">{{ $errors->first('buyer_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="name">Month</label>
                            {!! Form::select('month', $months ?? [], $buyerCapacity->month ??  null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'month']) !!}

                            @if($errors->has('month'))
                                <span class="text-danger">{{ $errors->first('month') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="name">Year</label>
                            {!! Form::select('year', $years ?? [], $buyerCapacity->year ??  null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'year']) !!}

                            @if($errors->has('year'))
                                <span class="text-danger">{{ $errors->first('year') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="name">Capacity</label>
                            {!! Form::text('capacity', null, ['class' => 'form-control form-control-sm', 'id' => 'name']) !!}

                            @if($errors->has('capacity'))
                                <span class="text-danger">{{ $errors->first('capacity') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success"><i
                                    class="fa fa-save"></i> {{ $buyerCapacity ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-sm btn-warning"
                               href="{{ route('planning.settings.buyer-capacity.index') }}"><i
                                    class="fa fa-remove"></i>
                                Cancel</a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">

        const services = {

            data: {
                factoryId: $('#factory_id').val(),
            },

            getBuyers: function () {
                $.ajax({
                    method: 'get',
                    url: `/api/v1/planning/factory-wise-buyers/${this.data.factoryId}`,
                    success: function (result) {
                        console.log(result)
                        $.each(result.data.data, function (key, value) {
                            let element = `<option value="${value.id}">${value.text}</option>`;
                            $('#buyer_id').append(element);
                        })
                        $('#buyer_id').select2();
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
            },
            generateYears: function () {
                const d = new Date();
                const currentYear = d.getFullYear();
                let year = 2020;
                let years = [];
                while (year <= currentYear + 3) {
                    years.push(year);
                    year++;
                }

                years.forEach((value) => {
                    let element = `<option value="${value}">${value}</option>`;
                    $('#year').append(element);
                })
                // $.each(year, function (key, value) {
                //     console.log(value);
                //     let element = `<option value="${value}">${value}</option>`;
                //     $('#year').append(element);
                // });
                $('#year').select2();

            },
            generateMonths: function () {
                const MONTHS = [

                    {
                        id: 0,
                        text: 'JAN'
                    },
                    {
                        id: 1,
                        text: 'FEB'
                    },
                    {
                        id: 2,
                        text: 'MAR'
                    },
                    {
                        id: 3,
                        text: 'APR'
                    },
                    {
                        id: 4,
                        text: 'MAY'
                    },
                    {
                        id: 5,
                        text: 'JUN'
                    },
                    {
                        id: 6,
                        text: 'JUL'
                    },
                    {
                        id: 7,
                        text: 'AUG'
                    },
                    {
                        id: 8,
                        text: 'SEP'
                    },
                    {
                        id: 9,
                        text: 'OCT'
                    },
                    {
                        id: 10,
                        text: 'NOV'
                    },
                    {
                        id: 11,
                        text: 'DEC'
                    }
                ];

                MONTHS.forEach((value) => {
                    let element = `<option value="${value.id}">${value.text}</option>`;
                    $('#month').append(element);
                })
                // $.each(MONTHS, function (key, value) {
                //     let element = `<option value="${value}">${value}</option>`;
                //     $('#month').append(element);
                // });
                $('#month').select2();
            }
        }


        $(document).on('change', '#factory_id', function () {
            services.getBuyers();
        });
    </script>
@endsection
