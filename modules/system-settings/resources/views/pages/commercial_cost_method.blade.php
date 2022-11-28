@extends('skeleton::layout')
@section('title', 'Commercial Cost Method List')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2 class="pull-left">Commercial Cost Method List</h2>
                <div class="clearfix"></div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('commercial-cost-method-search') }}" method="GET">

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
                    @if(Session::has('permission_of_colors_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <div class="col-sm-12 col-md-4">
                            <div class="box form-colors" >
                                <div class="box-header">
                                    <form action="{{ url('commercial-cost-method') }}" method="post" id="form">
                                        @csrf

                                        <div class="form-group">
                                            <label for="factory_id">Company</label>
                                            <div>
                                                <select class="form-control form-control-sm select2-input" name="factory_id" id="factory_id">
                                                    @foreach($factories as $key=>$factory)
                                                        <option value="{{$factory->id}}">{{ $factory->factory_name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('factory_id')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleSelectGender">Method</label>
                                            <div>
                                                <select class="form-control form-control-sm select2-input" name="method" id="method">
                                                    @foreach($methods as $key=>$method)
                                                        <option value="{{$method}}">{{ $method}}</option>
                                                    @endforeach
                                                </select>
                                                @error('method')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="percentage">Price Quo. Percentage</label>
                                            <input type="text" id="percentage" name="percentage" class="form-control form-control-sm"
                                                   placeholder="write" value="{{ old('name') }}">
                                            @error('percentage')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="budget_percentage">Budget Percentage</label>
                                            <input type="text" id="budget_percentage" name="budget_percentage"
                                                   class="form-control form-control-sm"
                                                   placeholder="write" value="{{ old('budget_percentage') }}">
                                            @error('budget_percentage')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleSelectGender">Writeable </label>
                                            <div>
                                                <select class="form-control form-control-sm select2-input" name="writeable" id="writeable">
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                            <a href="{{ url('commercial-cost-method-in-pq') }}"
                                               class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
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
                                <th>Method</th>
                                <th>Price Quo. Percentage</th>
                                <th>Budget Percentage</th>
                                <th>Writeable</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($commercialCostMethods as $key=>$commercialCostMethod)
                                <tr>
                                    <td>{{ $key + 1  }}</td>
                                    <td>{{ $commercialCostMethod->method }}</td>
                                    <td>{{$commercialCostMethod->percentage}}</td>
                                    <td>{{$commercialCostMethod->budget_percentage}}</td>
                                    <td>{{$commercialCostMethod->writeable}}</td>
                                    <td></td>
                                    <td>
                                        @if(Session::has('permission_of_colors_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a href="javascript:void(0)" data-id="{{ $commercialCostMethod->id }}"
                                               class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_colors_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('commercial-cost-method/'.$commercialCostMethod->id) }}">
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
                            {{ $commercialCostMethods->appends(request()->except('page'))->links() }}
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
                url: '{{ url('commercial-cost-method') }}/' + id,
                success: function (result) {
                    console.log(result);
                    $('#form').attr('action', `commercial-cost-method/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#percentage').val(result.percentage);
                    $('#method').val(result.method);
                    $('#writeable').val(result.writeable);
                    $('#budget_percentage').val(result.budget_percentage);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
