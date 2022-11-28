@extends('basic-finance::layout')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ isset($account) ? 'Update Account' : 'Create New Account' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        {!! Form::model($account ?? null, ['url' => isset($account) ? 'basic-finance/accounts/'.$account->id : 'basic-finance/accounts', 'method' => isset($account) ? 'PUT' : 'POST']) !!}
                        <div class="form-group row">
                            <label for="type" class="col-sm-3 form-control-label">AC Type *</label>
                            <div class="col-sm-9">
                                {!! Form::select('type_id', $ac_types, null, ['class' => 'form-control c-select select2-input', 'id' => 'type', 'placeholder' => 'Select account type']) !!}

                                @if($errors->has('type_id'))
                                    <span class="text-danger">{{ $errors->first('type_id') }}</span>
                                @endif
                                @if($errors->has('parent_ac'))
                                    <span class="text-danger">{{ $errors->first('parent_ac') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="parent_ac" class="col-sm-3 form-control-label">Parent Account *</label>
                            <div class="col-sm-9">
                                {!! Form::select('parent_ac', $accounts, null, ['class' => 'form-control c-select select2-input', 'id' => 'parent_ac', 'placeholder' => 'Select parent account if any']) !!}

                                @if($errors->has('parent_ac'))
                                    <span class="text-danger">{{ $errors->first('parent_ac') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 form-control-label">Name *</label>
                            <div class="col-sm-9">
                                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'placeholder' => 'Account name']) !!}

                                @if($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code" class="col-sm-3 form-control-label">AC Code *</label>
                            <div class="col-sm-9">
                                {!! Form::text('code', null, ['class' => 'form-control', 'id' => 'code', 'placeholder' => 'Account code']) !!}

                                @if($errors->has('code'))
                                    <span class="text-danger">{{ $errors->first('code') }}</span>
                                @endif
                            </div>
                        </div>
                    <!--  <div class="form-group row">
                <label for="particulars" class="col-sm-3 form-control-label">Particulars</label>
                <div class="col-sm-9">
                  {!! Form::text('particulars', null, ['class' => 'form-control', 'id' => 'particulars', 'placeholder' => 'Brief description']) !!}

                        @if($errors->has('particulars'))
                        <span class="text-danger">{{ $errors->first('particulars') }}</span>
                  @endif
                        </div>
                      </div> -->

                        <div class="form-group row m-t-md">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit"
                                        class="btn white">{{ isset($account) ? 'Update' : 'Create' }}</button>
                                <button type="button" class="btn white"><a
                                        href="{{ url('basic-finance/accounts') }}">Cancel</a></button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).on("change", "#type", function () {
            let type = $(this).val();
            console.log(type);
            $("#parent_ac").empty().append(`<option value="">Select Account</option>`).val('').trigger('change');
            $.ajax({
                method: "GET",
                url: "/basic-finance/fetch-accounts?type=" + type,
                success: function (data) {
                    $.each(data, function (key, value) {
                        let element = `<option value="${value.id}">${value.name}</option>`;
                        $('#parent_ac').append(element);
                    })
                },
                error: function (error) {
                    console.log(error)
                }
            })
        })
    </script>
@endsection

{{--@extends('skeleton::layout')--}}
{{--@section('title','Account create')--}}

{{--@section('content')--}}
{{--    <div class="padding">--}}
{{--        <div id="account-create"></div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@push('script-head')--}}
{{--    <script src="{{ mix('/js/basic-finance/account.js') }}"></script>--}}
{{--@endpush--}}

