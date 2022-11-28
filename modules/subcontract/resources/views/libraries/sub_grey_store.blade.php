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
                <h2>Sub Grey Store</h2>
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
                                'route' => $greyStore ? ['sub-grey-store.update', $greyStore->id] : 'sub-grey-store.store',
                                'method' => $greyStore ? 'PUT' : 'POST',
                                'id' => 'sub-grey-store-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Factory</label>
                                    {!! Form::select('factory_id', $factories , $greyStore->factory_id ?? factoryId(), ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('factory_id') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Store</label>
                                    {!! Form::text('name', $greyStore->name ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $greyStore ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="{{ route('sub-grey-store.index') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['route' => ['sub-grey-store.index'], 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('sub-grey-store.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                {!! Form::select('factory_filter', $factories , request()->get('factory_filter'), ['class' => 'form-control form-control-sm']) !!}
                                </th>
                                <td>
                                    {!! Form::text('name_filter', request()->get('name_filter'), ['class' => 'custom-field text-center',
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
                                <th>Factory</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($subGreyStores as $greyStore)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $greyStore->factory->factory_name }}</td>
                                    <td>{{ $greyStore->name }}</td>
                                    <td style="display: inline-flex">
                                        <a href="{{ route('sub-grey-store.edit',['sub_grey_store'=>$greyStore->id]) }}"
                                            class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                         <button type="button" class="btn btn-xs btn-danger show-modal"
                                                 data-toggle="modal"
                                                 data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                 ui-target="#animate"
                                                 data-url="{{ route('sub-grey-store.destroy',['sub_grey_store'=>$greyStore->id])  }}">
                                             <i class="fa fa-times"></i>
                                         </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            @if($subGreyStores->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $subGreyStores->appends(request()->except('page'))->links() }}
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