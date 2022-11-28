@extends('skeleton::layout')
@section('title', 'Brands')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>BRANDS</h2>
            </div>
            <div class="box-body b-t">
                <div class="col-md-6">
                    @if(Session::has('permission_of_brands_add') || Session::get('user_role') == 'super-admin')
                        <a href="{{url('brands/create')}}" class="btn btn-sm white m-b btn-sm">
                            <i class="glyphicon glyphicon-plus"></i> New Brand
                        </a>
                    @endif
                </div>
                <div class="col-md-6 pull-right">
                    <div class="form-group">
                        <div class="row m-b">
                            {!! Form::open(['url' => '/brands', 'method' => 'GET']) !!}
                            <div class="col-sm-offset-5 col-sm-5">
                                {!! Form::text('q', $q ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Brand Name']) !!}
                            </div>
                            <div class="col-sm-2">
                                <input type="submit" class="btn btn-sm white" value="Search">
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
                <div class="table-responsive">
                    <table class="reportTable" style="margin-top: 20px;">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Brand Name</th>
                            <th>Brand Type</th>
                            <th>Company</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody class="brands-list">
                        @if(!$brands->getCollection()->isEmpty())
                            @foreach($brands->getCollection() as $brand)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $brand->brand_name }}</td>
                                    <td>{{ BRANDTYPES[$brand->brand_type] }}</td>
                                    <td>{{ $brand->factory->factory_name }}</td>
                                    <td>
                                        @if(Session::has('permission_of_brands_edit') || Session::get('user_role') == 'super-admin')
                                            <a href="{{ url('brands/'.$brand->id.'/edit') }}" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Session::has('permission_of_brands_delete') || Session::get('user_role') == 'super-admin')
                                            <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('brands/'.$brand->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" align="center">No Data
                                </td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                        @if($brands->total() > 15)
                            <tr>
                                <td colspan="5" align="center">{{ $brands->appends(request()->except('page'))->links() }}</td>
                            </tr>
                        @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
