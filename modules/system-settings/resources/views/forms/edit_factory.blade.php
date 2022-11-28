@extends('skeleton::layout')
@section('title','Update Factories')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Update Company</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form role="form" action="{{ url('/factories/'.$factory->id) }}" method="post"
                              enctype="multipart/form-data">
                            {!! csrf_field() !!} {{ method_field('PUT') }}

                            <div class="form-group">
                                <label for="name">Group Name</label>
                                <input type="text" class="form-control form-control-sm" id="name" name="group_name"
                                       value="{{ $factory->group_name }}" placeholder="Write group name here">
                                <span style="color: red;">{{ $errors->first('group_name') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="name">Company Name</label>
                                <input type="text" class="form-control form-control-sm" id="name"
                                       name="factory_name" value="{{ $factory->factory_name }}"
                                       placeholder="Write factory name here">
                                <span style="color: red;">{{ $errors->first('factory_name') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="factory_name_bn">Company
                                    Name Bn</label>
                                <input type="text" class="form-control form-control-sm" id="factory_name_bn"
                                       name="factory_name_bn" value="{{ $factory->factory_name_bn }}"
                                       placeholder="Write factory name in bangla">
                            </div>
                            <div class="form-group">
                                <label for="name">Company Short Name</label>
                                <input type="text" class="form-control form-control-sm" id="name"
                                       name="factory_short_name" value="{{ $factory->factory_short_name }}"
                                       placeholder="Write factory short name here">
                                <span style="color: red;">{{ $errors->first('factory_short_name') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="code">Company Address</label>
                                <textarea class="form-control form-control-sm" rows="3" name="factory_address"
                                          placeholder="Write factory address">{{ $factory->factory_address }}</textarea>
                                <span style="color: red;">{{ $errors->first('factory_address') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="code">Company
                                    Address Bn</label>
                                <textarea class="form-control form-control-sm" rows="3" name="factory_address_bn"
                                          placeholder="Write factory address in bangla">{{ $factory->factory_address_bn }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="code">Resposible Person</label>
                                <input type="text" class="form-control form-control-sm" id="code"
                                       name="responsible_person" value="{{ $factory->responsible_person }}"
                                       placeholder="Write responsible person here">
                                <span style="color: red;">{{ $errors->first('responsible_person') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="code">Phone No.</label>
                                <input type="text" class="form-control form-control-sm" id="code" name="phone_no"
                                       value="{{ $factory->phone_no }}" placeholder="Write phone no. here">
                            </div>
                            <div class="form-group">
                                <label for="lien_bank_id">Bank</label>
                                <select id="lien_bank_id" multiple="" name="lien_bank_id[]"
                                        class="form-control select2-input form-control-sm"
                                        multiple>
                                    @foreach($lienBanks as $key => $value)
                                        <option
                                            value="{{ $key }}" {{ in_array($key, $lienBankId->toArray()) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="factory_image">Factory Logo</label>
                                <input type="file" class="form-control form-control-sm" id="code" name="factory_image">
                            </div>
                            <div class="form-group">
                                <label for="associate_factories">Associate Factory</label>
                                <select class="form-control select2-input form-control-sm" id="associate_factories"
                                        multiple=""
                                        multiple
                                        name="associate_factories[]">
                                    @foreach($associateFactories as $key => $value)
                                        <option
                                            value="{{ $key }}" {{ in_array($key, $factory->associate_factories ?? []) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-sm white">Update</button>
                                <a class="btn btn-sm btn-dark" href="{{ url('factories') }}">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
