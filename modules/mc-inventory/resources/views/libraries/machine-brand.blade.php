@extends('skeleton::layout')
@section("title","Machine Brand")

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
                <h2>Machine Brand</h2>
            </div>

            <div class="box-body b-t">
                <div class="row">
                    <div class="col-md-12">
                        @include('McInventory::partials.response-message')
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 custom-form-section">
                        <div class="box">
                            <div class="box-header">
                                {!! Form::open([
                                    'route' => $machineBrand ? ['machine-brand.update', $machineBrand->id] : 'machine-brand.store',
                                    'method' => $machineBrand ? 'PUT' : 'POST',
                                    'id' => 'machine-brand-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Name<span class="text-danger">*</span></label>
                                    {!! Form::text('name',$machineBrand->name ?? null,['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $machineBrand ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('machine-brand.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                    {!! Form::text('machine_name_filter', request()->get('machine_name_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Brand Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($machineBrands as $machineBrand)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$machineBrand->name}}</td>
                                    <td style="white-space: nowrap;">
                                        <a href="{{ route('machine-brand.edit',['machine_brand'=>$machineBrand->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ route('machine-brand.destroy',['machine_brand'=>$machineBrand->id])  }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            @if($machineBrands->total() > 15)
                                <tr>
                                    <td colspan="3"
                                        align="center">{{ $machineBrands->appends(request()->except('page'))->links() }}
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
