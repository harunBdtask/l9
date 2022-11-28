@extends('skeleton::layout')
@section("title","Fabric Composition")
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_fabric_compositions_view') || getRole() == 'super-admin' || getRole() == 'admin')
            <div class="box">
                <div class="box-header">
                    <h2>Fabric Compositions</h2>
                </div>
                <div class="box-body b-t">
                    <div class="col-md-6">
                        <div style="margin-bottom: 20px;">
                            @if(Session::has('permission_of_fabric_compositions_add') || getRole() == 'super-admin' || getRole() == 'admin')
                                <a href="{{ url('fabric-compositions/create') }}" class="btn btn-sm white m-b btn-sm">
                                    <i class="glyphicon glyphicon-plus"></i> New Fabric Composition
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3">
                        {!! Form::open(['url' => 'fabric-compositions', 'method' => 'GET']) !!}
                        <div class="pull-left input-group" style="margin-right: 10px;">
                            {!! Form::text('q', request('q') ?? null, ['class' => 'form-control form-control-sm']) !!}
                            <span class="input-group-btn">
                                    <input type="submit" class="btn btn-sm white" value="Search">
                                </span>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="col-md-12 flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                    <div class="table-responsive" style="margin-top: 20px;">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Fabric Nature</th>
                                <th>Fabric Code</th>
                                <th>Construction</th>
                                <th>GSM/Weight</th>
                                <th>Color Range</th>
                                <th>Stitch Length</th>
                                <th>Composition</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!$fabric_compositions->getCollection()->isEmpty())
                                @foreach($fabric_compositions->getCollection() as $fabric_composition)
                                    @php
                                        $composition = '';
                                        $last_key = $fabric_composition->newFabricCompositionDetails->keys()->last();
                                        $fabric_composition->newFabricCompositionDetails()->each(function($item, $key) use (&$composition, $last_key) {
                                            $composition .= $item->percentage.'% '.$item->yarnComposition->yarn_composition.' '.$item->yarnCount->yarn_count.' '.$item->compositionType->name;
                                            $composition .= ($key != $last_key) ? ', ' : '';
                                        })
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $fabric_composition->fabricNature->name }}</td>
                                        <td>{{ $fabric_composition->fabric_code }}</td>
                                        <td>{{ $fabric_composition->construction }}</td>
                                        <td>{{ $fabric_composition->gsm }}</td>
                                        <td>{{ $fabric_composition->colorRange->name }}</td>
                                        <td>{{ $fabric_composition->stitch_length }}</td>
                                        <td>{{ $composition }}</td>
                                        <td>{{ \SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition::STATUS[$fabric_composition->status] }}</td>
                                        <td>
                                            @if(Session::has('permission_of_fabric_compositions_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                                <a href="{{ url('fabric-compositions/'.$fabric_composition->id.'/edit')}}"
                                                   class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(Session::has('permission_of_fabric_compositions_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                                <button type="button" class="btn btn-xs btn-danger show-modal"
                                                        data-toggle="modal" data-target="#confirmationModal"
                                                        ui-toggle-class="flip-x" ui-target="#animate"
                                                        data-url="{{ url('fabric-compositions/'.$fabric_composition->id) }}">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" align="center">No Data</td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            @if($fabric_compositions->total() > 15)
                                <tr>
                                    <td colspan="9"
                                        align="center">{{ $fabric_compositions->appends(request()->except('page'))->links() }}</td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

