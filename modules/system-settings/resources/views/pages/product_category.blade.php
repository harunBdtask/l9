@extends('skeleton::layout')
@section("title","Product Category")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Product Category List</h2>
            </div>
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                    @endif
                @endforeach
            </div>
            <div class="box-body b-t">
                @if(Session::has('permission_of_product_category_add') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm white m-b" href="{{ url('product-category/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Product Category
                    </a>
                @endif

                <div class="pull-right">
                    <form action="{{ url('/search-product-category') }}" method="GET">
                        <div class="pull-left" style="margin-right: 10px;">
                            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm white" value="Search">
                        </div>
                    </form>
                </div>
                    <br>
                    <br>
                <table class="reportTable">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Product Category Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!$product_categorys->getCollection()->isEmpty())
                        @foreach($product_categorys->getCollection() as $product_category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product_category->category_name }}</td>
                                <td>
                                    @if(Session::has('permission_of_product_category_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                        <a class="btn btn-xs btn-success" href="{{ url('product-category/'.$product_category->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if(Session::has('permission_of_product_category_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                        <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal"
                                                ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('product-category/'.$product_category->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" align="center">No Data
                            <td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($product_categorys->total() > 15)
                        <tr>
                            <td colspan="6" align="center">{{ $product_categorys->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
@endsection
