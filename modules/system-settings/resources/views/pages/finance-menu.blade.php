@extends('skeleton::layout')
@section("title","Process")
@section('content')
    <div class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>Finance Menu</h2>
            </div>

            <div class="box-body" >
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12 col-md-5 form-colors" >
                        @if(getRole() == 'super-admin')
                            <form action="{{ url('finance-menu') }}" method="post" id="form">
                                @csrf
                                <div class="form-group">
                                    <label for="factory_id">Factory</label>
                                    {!! Form::select('factory_id', $factories, isset($menu) ? $menu->factory_id: null, ['class' => 'form-control c-select select2-input']) !!}
                                    @error('factory_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="menu">Finance Option</label>
                                    {!! Form::select('menu', [1 => 'Basic Finance', 2 => 'Integrated Finance'], isset($menu) ? $menu->menu: null, ['class' => 'form-control c-select select2-input']) !!}
                                    @error('menu')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="text-right">
                                        <a href="{{ url('finance-menu') }}" class="btn btn-sm btn-warning"><i
                                                    class="fa fa-refresh"></i> Refresh</a>
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                    class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Factory</th>
                                <th>Menu</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $sl = 0;
                            @endphp
                            @forelse($menus as $item)
                                <tr>
                                    <td>{{ ++$sl }}</td>
                                    <td>{{ $item->factory->group_name }}</td>
                                    <td>
                                        @if($item->menu == 1)
                                            Basic Finance
                                        @else
                                            Integrated Finance
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-icon btn-sm" href="{{ url("finance-menu/$item->id") }}" style="width: 100%">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
