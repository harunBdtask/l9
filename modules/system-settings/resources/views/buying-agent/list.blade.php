@extends('skeleton::layout')
@section("title","Buying Agent")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Buying Agent List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('buying-agent-search') }}" method="GET">
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
                        <div class="col-sm-12 col-md-5">
                            <div class="box form-colors">
                                <div class="box-header">
                                    <form action="{{ url('buying-agent') }}" method="post" id="form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="buying_agent_name">Buying Agent</label>
                                            <input type="text" id="buying_agent_name" name="buying_agent_name"
                                                   class="form-control form-control-sm"
                                                   value="{{ old('buying_agent_name') }}"
                                                   placeholder="Buying Agent">
                                            @error('buying_agent_name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            {!! Form::textarea('address', null, [
                                                'class' => 'form-control form-control-sm',
                                                'id' => 'address',
                                                'placeholder' => 'Address',
                                                'rows' => 2
                                            ]) !!}
                                            @if($errors->has('address'))
                                                <span class="text-danger">{{ $errors->first('address') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="associate_with">Associate With</label>
                                            {!! Form::select('associate_with[]', $factories, $associateWith ?? [], [
                                                'class' => 'form-control form-control-sm select2-input c-select form-control form-control-sm-sm',
                                                'id' => 'associate_with',
                                                'multiple' => 'multiple'
                                            ]) !!}
                                            @if($errors->has('associate_with'))
                                                <span class="text-danger">{{ $errors->first('associate_with') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                            <a href="{{ url('buying-agent') }}" class="btn btn-sm btn-warning"><i
                                                    class="fa fa-refresh"></i> Refresh</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-12 col-md-7">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Buying Agent</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($buyingAgents as $buyingAgent)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $buyingAgent->buying_agent_name }}</td>
                                    <td>{{ $buyingAgent->address }}</td>
                                    <td>
                                        @if(Session::has('permission_of_buying_agent_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-id="{{ $buyingAgent->id }}"
                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_buying_agent_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('buying-agent/'.$buyingAgent->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $buyingAgents->appends(request()->except('page'))->links() }}
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
                url: '{{ url('buying-agent') }}/' + id,
                success: function (result) {
                    let buyingAgent = result.buyingAgent;
                    $('#form').attr('action', `buying-agent/${buyingAgent.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#buying_agent_name').val(buyingAgent.buying_agent_name);
                    $('#address').val(buyingAgent.address);
                    $('#associate_with').val(result.associateWith).select2();
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
