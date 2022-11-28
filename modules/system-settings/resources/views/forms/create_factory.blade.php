@extends('skeleton::layout')
@section('title', 'Create Factories')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>New Company</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form role="form" action="{{ url('/factories') }}" method="post" enctype="multipart/form-data">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label for="name">Group Name</label>
                                <input type="text" class="form-control form-control-sm" id="name" name="group_name"
                                       value="{{ old('group_name') }}" placeholder="Write group name here">
                                <span style="color: red;">{{ $errors->first('group_name') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="name">Company
                                    Name</label>
                                <input type="text" class="form-control form-control-sm" id="name"
                                       name="factory_name" value="{{ old('factory_name') }}"
                                       placeholder="Write factory name here">
                                <span style="color: red;">{{ $errors->first('factory_name') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="factory_name_bn">Company
                                    Name Bn</label>
                                <input type="text" class="form-control form-control-sm" id="factory_name_bn"
                                       name="factory_name_bn" value="{{ old('factory_name_bn') }}"
                                       placeholder="Write factory name in bangla">
                            </div>
                            <div class="form-group">
                                <label for="name">Company Short
                                    Name</label>
                                <input type="text" class="form-control form-control-sm" id="name"
                                       name="factory_short_name" value="{{ old('factory_short_name') }}"
                                       placeholder="Write short name here. eg: ccl max length: 10 digits">
                                <span style="color: red;">{{ $errors->first('factory_short_name') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="code">Company
                                    Address</label>
                                <textarea class="form-control form-control-sm" rows="3" name="factory_address"
                                          placeholder="Write factory address">{{ old('factory_address') }}</textarea>
                                <span style="color: red;">{{ $errors->first('factory_address') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="code">Company
                                    Address Bn</label>
                                <textarea class="form-control form-control-sm" rows="3" name="factory_address_bn"
                                          placeholder="Write factory address in bangla">
                                    {{ old('factory_address_bn') }}
                                </textarea>
                            </div>
                            <div class="form-group">
                                <label for="code">Responsible
                                    Person</label>
                                <input type="text" class="form-control form-control-sm" id="code"
                                       name="responsible_person" value="{{ old('responsible_person') }}"
                                       placeholder="Write responsible person here">
                            </div>
                            <div class="form-group">
                                <label for="code">Phone No.</label>
                                <input type="text" class="form-control form-control-sm" id="code" name="phone_no"
                                       value="{{ old('phone_no') }}" placeholder="Write phone no. here">
                            </div>
                            <div class="form-group">
                                <label for="lien_bank_id">Bank</label>
                                <select class="form-control select2-input form-control-sm" id="lien_bank_id" id="code"
                                        multiple="" multiple
                                        name="lien_bank_id[]">
                                    @foreach($lienBanks as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="factory_image">Factory Logo</label>
                                <input type="file" class="form-control form-control-sm" id="factory_image"
                                       name="factory_image">
                            </div>
                            <div class="form-group">
                                <label for="associate_factories">Associate Factories</label>
                                <select class="form-control select2-input form-control-sm" id="associate_factories"
                                        id="associate_factories"
                                        multiple="" multiple
                                        name="associate_factories[]">
                                    @foreach($associateFactories as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-success"><i
                                        class="fa fa-save"></i> Create
                                </button>
                                <a class="btn btn-sm btn-warning" href="{{ url('factories') }}"><i
                                        class="fa fa-remove"></i> Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
