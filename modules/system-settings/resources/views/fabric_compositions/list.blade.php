@extends('skeleton::layout')
@section("title","Fabric Composition")
@push('style')

@endpush
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_fabric_compositions_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="box knit-card">
                <div class="box-header">
                    <div class="row print-delete">
                        <div class="col-md-6">
                            <h2>Fabric Composition list</h2>
                        </div>
                        <a href="{{url('fabric-compositions/pdf')}}" class="btn btn-xs btn-default pull-right"><i class="fa fa-file-pdf-o"></i> Pdf</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t ">
                    <div class="col-md-6">
                        @if(Session::has('permission_of_fabric_compositions_add') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                            <a href="{{url('fabric-compositions/create')}}" class="btn btn-sm white m-b add-new-btn btn-sm print-delete">
                                <i class="glyphicon glyphicon-plus"></i> Add Fabric Composition
                            </a>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <form action="{{ url('search-fabric-composition') }}" method="GET">
                                <div class="pull-left" style="margin-right: 10px;">
                                    <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
                                </div>
                                <div class="pull-right">
                                    <input type="submit" class="btn btn-sm white" value="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="flash-message print-delete">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                            @endif
                        @endforeach
                    </div>

                    <div class="table-responsive">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Composition Name</th>
                                <th>Factories</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($fabric_compositions as $fabric_composition)
                                <tr>
                                    <td>{{$fabric_composition->yarn_composition}}</td>
                                    <td style="background: #0F733B;color: #fff;font-weight: bold;letter-spacing: 1px">{{$fabric_composition->factory->factory_name}}</td>
                                    <td>
                                        @if(Session::has('permission_of_fabric_compositions_edit') || Session::get('user_role') == 'super-admin' ||Session::get('user_role') == 'admin')
                                            <a class="btn btn-xs btn-success" href="{{url('fabric-compositions/edit?id='.$fabric_composition->id)}}"><i class="fa fa-edit"></i> </a>
                                        @endif
                                        @if(Session::has('permission_of_fabric_compositions_delete') || Session::get('user_role') == 'super-admin' ||Session::get('user_role') == 'admin')
{{--                                            <a class="btn btn-xs btn-danger" href="{{url('fabric-compositions/delete?id='.$fabric_composition->id)}}"><i class="fa fa-trash-o"></i> </a>--}}
                                                <button type="button" class="btn btn-xs danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('fabric-compositions/delete?id='.$fabric_composition->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">{{$fabric_compositions->appends($_GET)->links() }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>


@endsection

@push('script-head')
    <script>
        $(function () {
            $('body').on('click', '#print', function () {
                $('.print-delete').hide();
                $('#tableOrder').removeClass('table-responsive');
                window.print();
                $('.print-delete').show();
            });
        });
    </script>
@endpush
