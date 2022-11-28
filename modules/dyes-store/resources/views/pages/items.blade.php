@extends('dyes-store::layout')
@section('title','Items')
@section('content')
    <style>
        .box-body {
            padding: 0 1rem !important;
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>ITEMS</h2>
                <span class="pull-right" style="margin-top: -2%;">
                    <a id="pdf" type="button" data-toggle="tooltip" data-placement="top" title="PDF">
                       <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                    </a>|
                    <a id="excel" type="button" data-toggle="tooltip" data-placement="top" title="EXCEL">
                       <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                    </a>
                </span>
            </div>
            <div class="box-body table-responsive b-t">
                {{-- <div class="row m-b-2"> --}}
                <form action={{ url("/dyes-store/items") }} method="GET" id="itemForm">
                    <div class="col-md-3">
                        <button class="btn btn-sm btn-primary" style="display: none" type="submit">
                            Submit
                        </button>
                    </div>
                </form>
                {{-- </div> --}}
                <br>
                <div class="row">
                    <div class="col-sm-9">
                        <a href="{{ url('/dyes-store/items/create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Create Items
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <form action="{{ url('/dyes-store/items') }}" method="GET">
                            <div class="input-group">
                                <input id="searchInput" type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request()->query('search') ?? '' }}"
                                       placeholder="Search here">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-primary" type="submit">Search</button>
                                 </span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>UoM</th>
                                <th>Store</th>
                                <th>Prefix</th>
                                <td>Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{  str_pad($loop->iteration + $items->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category->name ?? '' }}</td>
                                    <td>{{ $item->brand->name ?? '' }}</td>
                                    <td>{{ $item->uomDetails->name }}</td>
                                    <td>{{ $item->store_details->name }}</td>
                                    <td>{{ $item->prefix }}</td>
                                    <td>
                                        <a href="{{ url('/dyes-store/items/edit', ['item' => $item->id]) }}">
                                            <i class="fa fa-edit text-blue"></i>
                                        </a>
                                        <a href="{{ url('/dyes-store/items/'.$item->id.'/delete') }}"
                                           onclick="return confirm('Are You Sure?');">
                                            <i class="fa fa-close text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            @if($items->total() > 15)
                                <tr>
                                    <td colspan="8"
                                        align="center">{{ $items->appends(request()->except('page'))->links() }}</td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script defer>
        $(document).ready(function () {
            $('#first_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#last_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#pdf').on('click', function () {
                let page = ` <input type="hidden" name="page" value="{{request()->query("page") ?? 1}}"> `;
                $("#itemForm").append(page);
                let pdf = ` <input type="hidden" name="type" value="pdf"> `;
                $('#itemForm').append(pdf).submit();
            });

            $('#excel').on('click', function () {
                let page = ` <input type="hidden" name="page" value="{{request()->query("page") ?? 1}}"> `;
                $("#itemForm").append(page);
                let excel = ` <input type="hidden" name="type" value="excel"> `;
                $('#itemForm').append(excel).submit();
            });
        });
    </script>
@endpush
