@extends('skeleton::layout')
@section("title","Product Departments")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2 class="pull-left">Product Department List</h2>
                <a href="{{url('product-department/pdf')}}" class="btn btn-xs btn-default pull-right"><i
                        class="fa fa-file-pdf-o"></i> Pdf</a>
                <div class="clearfix"></div>
            </div>
            <div class="box-body b-t">
                <div class="col-md-6">
                    @if(Session::has('permission_of_product_department_add') || getRole() == 'super-admin' || getRole() == 'admin')
                        <a class="btn btn-sm white m-b" href="{{ url('product-department/create') }}">
                            <i class="glyphicon glyphicon-plus"></i>New Product Department
                        </a>
                    @endif
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    {!! Form::open(['url' => 'product-department', 'method' => 'GET']) !!}
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" name="q"
                               value="{{ request('q') ?? '' }}" placeholder="Search">
                        <span class="input-group-btn">
                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                        </span>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="reportTable">
                        <thead>
                        <tr>
                            <th style="width:20%">SL</th>
                            <th style="width:30%">Product Department</th>
                            <th style="width:30%">Company</th>
                            <th style="width:30%">Status</th>
                            <th style="width:20%">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$product_departments->getCollection()->isEmpty())
                            @foreach($product_departments->getCollection() as $product_department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product_department->product_department }}</td>
                                    <td style="background: #0F733B;color: #fff;font-weight: bold;letter-spacing: 1px">
                                        {{ $product_department->factory ? $product_department->factory->factory_name : '' }}
                                    </td>
                                    <td>
                                        {{ $product_department->status == 1? "Active" : ($product_department->status == 2 ? "In Active": "Cancelled") }}
                                    </td>
                                    <td>
                                        @if(Session::has('permission_of_product_department_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url('product-department/'.$product_department->id.'/edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(Session::has('permission_of_product_department_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('product-department/'.$product_department->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" align="center">No Data
                                <td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $product_departments->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
@endsection
