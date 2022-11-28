@extends('skeleton::layout')
@section('title', 'Advising Banks')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Advising Banks</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body b-t">
                <div class="row align-content-center">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(session()->has('alert-' . $msg))
                                    <p class="text-center alert alert-{{ $msg }}">{{ session()->get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        <form action="{{ url('advising-bank') }}" method="post">
                            {{ @csrf_field() }}
                            <div class="form-group">
                                <label for="name">Advising Bank</label>
                                {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Bank Name']) !!}
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                {!! Form::textarea('address', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Address', 'cols' => 6]) !!}
                            </div>
                            <button type="submit" class="btn btn-sm white m-b btn-sm btn-block">Save</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive m-t-3">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @isset($banks)
                                    @foreach($banks as $bank)
                                        <tr>
                                            <td>{{ $bank->name }}</td>
                                            <td>{{ $bank->address }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
