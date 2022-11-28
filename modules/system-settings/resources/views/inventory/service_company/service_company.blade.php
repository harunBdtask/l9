@extends('skeleton::layout')
@section("title","Service Company")

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
                <h2>Service Company</h2>
            </div>

            <div class="box-body b-t">
                <div class="row">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div
                                    class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 custom-form-section">
                        <div class="box">
                            <div class="box-header">
                                {!! Form::open([
                                'route' => $serviceCompany ? ['service-company.update', $serviceCompany->id] : 'service-company.store',
                                'method' => $serviceCompany ? 'PUT' : 'POST',
                                'id' => 'service-company-entry-form']) !!}

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Name</label>
                                    {!! Form::text('name', $serviceCompany->name ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Address</label>
                                    {!! Form::text('address', $serviceCompany->address ?? null , ['class' => 'form-control form-control-sm']) !!}
                                    @if($errors->has('address'))
                                        <span class="text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i>
                                        {{ $serviceCompany ? 'Update' : 'Create' }}
                                    </button>
                                    <a href="{{ route('service-company.index') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['route' => ['service-company.index'], 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('service-company.index') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                    {!! Form::text('name', request()->get('name'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>

                                <td>
                                    {!! Form::text('address', request()->get('address'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                                @forelse($serviceCompanies as $service)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ $service->address }}</td>
                                    <td >
                                        <a href="{{ route('service-company.edit',['service_company'=>$service->id]) }}" class="edit-btn btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                        <button type="button" class="btn btn-xs btn-danger show-modal"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ route('service-company.destroy',['service_company'=>$service->id])  }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" align="center">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                            @if($serviceCompanies->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        align="center">{{ $serviceCompanies->appends(request()->except('page'))->links() }}
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
