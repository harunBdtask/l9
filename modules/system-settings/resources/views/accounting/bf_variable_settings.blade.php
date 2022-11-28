@extends('skeleton::layout')
@section("title","Accounting Variable Settings")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Accounting Variable Settings</h2>
            </div>

            <div class="box-body">

                <div class="row m-t">
                    @if(Session::has('permission_of_accounting_variable_add') || getRole() == 'super-admin' || getRole() == 'admin')

                        <div class="col-sm-12">

                            <form action="{{ url('accounting-variable-settings') }}" method="post" id="form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="departmental_approval">Departmental Approval</label>
                                            <select name="departmental_approval" id="departmental_approval" class="form-control select2-input">
                                                <option value="0" {{ (@$variable->departmental_approval =='0' ? 'selected':'') }}>No</option>
                                                <option value="1" {{ (@$variable->departmental_approval =='1' ? 'selected':'')  }}>Yes</option>
                                            </select>
                                            @error('departmental_approval')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="voucher_preview_signature">Voucher Preview Page Signature</label>
                                            <select name="voucher_preview_signature" id="voucher_preview_signature" class="form-control select2-input">
                                                <option value="0"  {{ (@$variable->voucher_preview_signature =='0' ? 'selected':'') }}>No</option>
                                                <option value="1" {{ (@$variable->voucher_preview_signature =='1' ? 'selected':'') }}>Yes</option>
                                            </select>
                                            @error('voucher_preview_signature')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="accounting_users">Accounting Users</label>
                                            <select name="accounting_users[]" id="accounting_users" class="form-control select2-input" multiple>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ (in_array($user->id, @$variable->accounting_users??[]) ? 'selected':'') }}>
                                                        {{ $user->first_name.' '.$user->last_name.' ('. $user->email.')'; }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('accounting_users')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                            class="fa fa-save"></i> Save
                                    </button>
                                    <a href="{{ url('/accounting-variable-settings') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i> Refresh</a>
                                </div>
                            </form>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

