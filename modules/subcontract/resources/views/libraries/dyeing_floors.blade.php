@extends('skeleton::layout')
@section("title","Sub Grey Store")

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
                <h2>Dyeing Floor</h2>
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
                                'route' => $dyeingFloor ? ['dyeing-floor.update', $dyeingFloor->id] : 'dyeing-floor.store',
                                'method' => $dyeingFloor ? 'PUT' : 'POST',
                                'id' => 'dyeing-floor-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Floor Type</label>
                                    {!! Form::select('type', $floorTypes ,$dyeingFloor->type ?? null, ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('type'))
                                        <span class="text-danger">{{ $errors->first('type') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Name</label>
                                    {!! Form::text('name', $dyeingFloor->name ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Attention</label>
                                    {!! Form::text('attention', $dyeingFloor->attention ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('attention'))
                                        <span class="text-danger">{{ $errors->first('attention') }}</span>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $dyeingFloor ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="{{ route('dyeing-floor.index') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['route' => ['dyeing-floor.index'], 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('dyeing-floor.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                {!! Form::select('floor_type_filter', $floorTypes , request()->get('floor_type_filter'), ['class' => 'form-control form-control-sm']) !!}
                                </th>
                                <td>
                                    {!! Form::text('name_filter', request()->get('name_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>

                                <td>
                                    {!! Form::text('attention_filter', request()->get('attention_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                
                                </th>
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
                                <th>Type</th>
                                <th>Name</th>
                                <th>Attention</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                                @forelse ($dyeingFloors as $floor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $floor->floor_type_value }}</td>
                                    <td>{{ $floor->name }}</td>
                                    <td>{{ $floor->attention }}</td>
                                    <td style="display: inline-flex">
                                        <a href="{{ route('dyeing-floor.edit',['dyeing_floor'=>$floor->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                         <button type="button" class="btn btn-xs btn-danger show-modal"
                                                 data-toggle="modal"
                                                 data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                 ui-target="#animate"
                                                 data-url="{{ route('dyeing-floor.destroy',['dyeing_floor'=>$floor->id])  }}">
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
                            @if($dyeingFloors->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $dyeingFloors->appends(request()->except('page'))->links() }}
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