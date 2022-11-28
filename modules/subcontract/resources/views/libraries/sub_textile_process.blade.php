@extends('subcontract::layout')
@section("title","Sub Textile Process")
@section('content')
    <style>
        .custom-field {
            width: 90%;
            border: 1px solid #cecece;
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2 class="pull-left">Sub Textile Process</h2>
                <div class="clearfix"></div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12 col-md-4">
                        <div class="box">
                            <div class="box-header">
                                <form action="{{ url('subcontract/process') }}" method="post" id="form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="factory_id">Company</label>
                                        <select id="factory_id" name="factory_id"
                                                class="form-control form-control-sm select2-input"
                                                value="{{ old('factory_id') ?? factoryId() }}">
                                            <option selected disabled hidden>-- Select Company --</option>
                                            @foreach($factories as $factory)
                                                <option value="{{ $factory->id }}">{{ $factory->factory_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('factory_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="sub_textile_operation_id">Operation</label>
                                        <select id="sub_textile_operation_id" name="sub_textile_operation_id"
                                                class="form-control form-control-sm select2-input"
                                                value="{{ old('sub_textile_operation_id') }}">
                                            <option selected disabled hidden>-- Select Operation --</option>
                                            @foreach($operations as $operation)
                                                <option value="{{ $operation->id }}">{{ $operation->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('sub_textile_operation_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name"
                                               class="form-control form-control-sm"
                                               value="{{ old('name') }}">
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="text" id="price" name="price"
                                               class="form-control form-control-sm"
                                               value="{{ old('price') }}">
                                        @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <a href="{{ url('subcontract/process') }}" class="btn btn-sm btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12 col-md-8">
                        <form action="/subcontract/process" method="get">
                            <table class="reportTable">
                                <thead>
                                <tr>

                                    <td></td>
                                    <td>
                                        <input type="text" name="operation" placeholder="Search"
                                               class="custom-field text-center" value="{{ request('operation') }}">
                                    </td>
                                    <td>
                                        <input type="text" name="name" placeholder="Search"
                                               class="custom-field text-center" value="{{ request('name') }}">
                                    </td>
                                    <td>
                                        <input type="text" name="price" placeholder="Search"
                                               class="custom-field text-center" value="{{ request('price') }}">
                                    </td>
                                    <td>
                                        <select name="company"
                                                class="custom-field text-center select2-input"
                                                value="{{ request('company') }}">
                                            <option selected disabled hidden>-- Select Company --</option>
                                            @foreach($factories as $factory)
                                                <option value="{{ $factory->id }}">{{ $factory->factory_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-sm btn-success">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7">&nbsp;</td>
                                </tr>
                                <tr>
                                    <th>SL</th>
                                    <th>Operation</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Company</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($processes as $process)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $process->textileOperation->name }}</td>
                                        <td>{{ $process->name }}</td>
                                        <td>{{ $process->price }}</td>
                                        <td>{{ $process->factory->factory_name }}</td>
                                        <td>
                                            @if($process->status == 1)
                                                <span class="label success">Active</span>
                                            @else
                                                <span class="label yellow">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/subcontract/process/{{ $process->id }}/status"
                                               class="btn btn-xs {{ $process->status == 1 ? 'btn-success' : 'yellow' }}">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                            <a href="javascript:void(0)" data-id="{{ $process->id }}"
                                               class="btn btn-xs btn-info edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="7">
                                            <b>No Data Found</b>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </form>

                        <div class="text-center">
                            {{ $processes->appends(request()->except('page'))->links() }}
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
                url: '/subcontract/process/' + id,
                method: 'get',
                success: function (response) {
                    $('#form').attr('action', '/subcontract/process?id=' + response.id);
                    $('#factory_id').val(response.factory_id).select2();
                    $('#sub_textile_operation_id').val(response.sub_textile_operation_id).select2();
                    $('#name').val(response.name);
                    $('#price').val(response.price);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
