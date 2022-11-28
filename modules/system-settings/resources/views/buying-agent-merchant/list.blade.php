@extends('skeleton::layout')
@section("title","Buying Agent Merchant")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Buying Agent Merchant List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('buying-agent-merchant-search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">
                    @if(Session::has('permission_of_buying_agent_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <div class="col-sm-12 col-md-4">
                            <div class="box form-colors">
                                <div class="box-header">
                                    <form action="{{ url('buying-agent-merchant') }}" method="post" id="form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="buying_agent_id">Buying Agent</label>
                                            <div>
                                                <select class="form-control form-control-sm select2-input" name="buying_agent_id" id="buying_agent_id">
                                                    @foreach($buyingAgents as $key=>$buyingAgent)
                                                        <option value="{{$buyingAgent->id}}">{{ $buyingAgent->buying_agent_name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('buying_agent_id')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="buying_agent_merchant_name">Merchant Name</label>
                                            <input type="text" id="buying_agent_merchant_name" name="buying_agent_merchant_name"
                                                   class="form-control form-control-sm" value="{{ old('buying_agent_merchant_name') }}"
                                                   placeholder="Buying Agent Merchant">
                                            @error('buying_agent_merchant_name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile">Mobile</label>
                                            {!! Form::number('mobile', null, ['class' => 'form-control form-control-sm', 'id' => 'mobile', 'placeholder' => 'Mobile']) !!}
                                            @if($errors->has('mobile'))
                                                <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            {!! Form::email('email', null, ['class' => 'form-control form-control-sm', 'id' => 'email', 'placeholder' => 'Email']) !!}
                                            @if($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save </button>
                                            <a href="{{ url('buying-agent-merchant') }}" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-12 col-md-8">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Merchant</th>
                                <th>Buying Agent</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($buyingAgentMerchants as $buyingAgentMerchant)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $buyingAgentMerchant->buying_agent_merchant_name }}</td>
                                    <td>{{ $buyingAgentMerchant->buyingAgent->buying_agent_name }}</td>
                                    <td>{{ $buyingAgentMerchant->mobile }}</td>
                                    <td>{{ $buyingAgentMerchant->email }}</td>
                                    <td>{{ $buyingAgentMerchant->address }}</td>
                                    <td>
                                        @if(Session::has('permission_of_buying_agent_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-id="{{ $buyingAgentMerchant->id }}"
                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_buying_agent_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('buying-agent-merchant/'.$buyingAgentMerchant->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $buyingAgentMerchants->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('buying-agent-merchant') }}/' + id,
                success: function (result) {
                    $('#form').attr('action', `buying-agent-merchant/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#buying_agent_id').select2('val',result.buying_agent_id);
                    $('#buying_agent_merchant_name').val(result.buying_agent_merchant_name);
                    $('#mobile').val(result.mobile);
                    $('#email').val(result.email);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
