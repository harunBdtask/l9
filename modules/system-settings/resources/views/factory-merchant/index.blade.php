@extends('skeleton::layout')
@section("title","Factory Merchant")
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_factory_merchant_add') || getRole() == 'super-admin' || getRole() == 'admin')
            <div class="box" style="min-height: 610px">
                <div class="box-header btn-info">
                    <h2>Factory Merchant</h2>
                </div>
                <div class="box-body b-t">
                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-9">
                            <form action="" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search"
                                           value="{{ $data ?? '' }}" placeholder="Search">
                                    <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Search</button>
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
                        @if(Session::has('permission_of_factory_merchant_add') || getRole() == 'super-admin' || getRole() == 'admin')
                            <div class="col-sm-12 col-md-5">
                                <form action="{{ url('/factory-merchants') }}" method="post" id="form">
                                    @csrf
                                    <div class="d-flex flex-md-wrap">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="merchant_name">Merchant Name</label>
                                                <input type="text" name="merchant_name"  id="merchant_name" class="form-control"
                                                       value="{{ old('merchant_name') }}" placeholder="Merchant Name">
                                                @error('merchant_name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="factory_address">Factory Address</label>
                                                <input type="text" name="factory_address"  id="factory_address" class="form-control"
                                                       value="{{ old('factory_address') }}" placeholder="Factory Address">
                                                @error('factory_address')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="text-right">
                                                    <a href="{{ url('factory-merchants') }}" class="btn btn-sm btn-warning"><i
                                                            class="fa fa-refresh"></i> Refresh</a>
                                                    <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                            class="fa fa-save"></i> Create
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                        <div class="col-sm-12 col-md-7">
                            <table class="reportTable display compact cell-border" id="item_list_table">
                                <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Merchant Name</th>
                                    <th>Factory Address</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $item->merchant_name ?? 'N/A' }}</td>
                                        <td>{{ $item->factory_address }}</td>
                                        <td style="padding: 2px">
                                            @if(Session::has('permission_of_factory_merchant_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                                <a class="btn btn-xs btn-success edit" data-id="{{ $item->id }}"
                                                   href="javascript:void(0)"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(Session::has('permission_of_factory_merchant_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                                <button type="button" class="btn btn-xs btn-danger show-modal"
                                                        data-toggle="modal" data-target="#confirmationModal"
                                                        ui-toggle-class="flip-x" ui-target="#animate"
                                                        data-url="{{ url('factory-merchants/'.$item->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No Data Found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="text-center">
                                {{ $items->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('script-head')
    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('factory-merchants') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `factory-merchants/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#merchant_name').val(result.merchant_name);
                    $('#factory_address').val(result.factory_address);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
