@extends('skeleton::layout')
@section("title","Group Wise Fields")

@section('styles')
    <style>
        .custom-control-label {
            padding: 0.165rem 0;
        }

        .custom-form-section {
            border-radius: 6px;
            /*padding: 13px 0;*/
        }

        .custom-field {
            width: 90%;
            border: 1px solid #cecece;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Group wise Fields</h2>
            </div>

            <div class="box-body b-t">
                <div class="row">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div
                                    class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 custom-form-section">
                        <div class="box">
                            <div class="box-header">
                                {!! Form::open([
                                'url' => isset($groupWiseFieldData) ? '/group-wise-fields/'.$groupWiseFieldData->id : '/group-wise-fields',
                                'method' => isset($groupWiseFieldData) ? 'PUT' : 'POST'
                                ]) !!}

                                <div class="form-group">
                                    <label for="group_name" class="custom-control-label">Group</label>
                                    {!! Form::select('group_name', $itemGroups ?? [], $groupWiseFieldData->group_name ?? null,
                                        ['class' => 'form-control form-control-sm select2-input'])
                                    !!}
                                    @if($errors->has('group_name'))
                                        <span class="text-danger">{{ $errors->first('group_name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="fields" class="custom-control-label">Fields</label>
                                    {!! Form::select('fields[]', $fields, $groupWiseFieldData->fields ?? null,['class'=>'form-control form-control-sm select2-input', 'multiple'=>'multiple']) !!}
                                    @if($errors->has('fields'))
                                        <span
                                            class="text-danger">{{ $errors->first('fields') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ isset($groupWiseFieldData) ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="/group-wise-fields"
                                       class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['url' => ['/group-wise-fields'], 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="/group-wise-fields" type="submit"
                                       class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                    {!! Form::text('group_name', request('group_name'), ['class' => 'form-control form-control-sm']) !!}
                                </td>
                                <td>
                                    @php
                                        $fields = collect($fields)->prepend('SELECT', -1);
                                    @endphp
                                    {!! Form::select('field', $fields, request('field'), ['class' => 'form-control form-control-sm select2-input']) !!}
                                </td>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="8">&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Group</th>
                                <th>Fields</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse ($groupWiseFields as $groupWiseField)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $groupWiseField->item->item_group }}</td>
                                    <td>{{ collect($groupWiseField->fields_value)->implode(', ') }}</td>
                                    <td>
                                        <a href="/group-wise-fields/{{$groupWiseField->id}}"
                                           class="edit-btn btn btn-xs btn-success">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="/group-wise-fields/{{$groupWiseField->id}}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" align="center">No Data</td>
                                </tr>
                            @endforelse

                            </tbody>
                            <tfoot>
                            @if($groupWiseFields->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $groupWiseFields->appends(request()->except('page'))->links() }}
                                    </td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                        {!! Form::close() !!}
                    </div>
                </div>


            </div>

        </div>
    </div>

@endsection
