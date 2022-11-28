@extends('commercial::layout')
@section('title','Lien List')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2 style="font-weight: 400;">Lien List</h2>
            </div>
            <div class="box-body">
                @include('commercial::partials.flash')
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/commercial/lien/create')}}" class="btn btn-sm white">
                            <i class="fa fa-plus"></i> Lien Create
                        </a>
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <form action="{{ url('/commercial/lien') }}" method="GET">
                            <table class="reportTable">
                                <thead>
                                <tr style="background: aliceblue">
                                    <th> Company Name</th>
                                    <th> Lien No</th>
                                    <th> Application Date</th>
                                    <th> Advising Bank</th>
                                    <th> Action</th>
                                </tr>
                                <tr>
                                    <th>
                                        <select name="factory_id"
                                                class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($factories as $value)
                                                <option
                                                    value="{{ $value->id }}"
                                                    {{ request('factory_id',factoryId()) == $value->id?'selected':''}} >
                                                    {{ $value->factory_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <input
                                            type="text"
                                            name="lien_no"
                                            placeholder="Search"
                                            value="{{request('lien_no')??''}}"
                                            class="form-control form-control-sm search-field text-center">
                                    </th>
                                    <th>
                                        <input
                                            type="date"
                                            name="lien_date"
                                            placeholder="dd-mm-yyyy"
                                            value="{{request('lien_date')??''}}"
                                            class="form-control form-control-sm search-field text-center">
                                    </th>
                                    <th>
                                        <select
                                            name="bank_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($banks as $value)
                                                <option
                                                    value="{{ $value->id }}"
                                                    {{ request('bank_id') == $value->id?'selected':''}}>
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <button
                                            type="submit"
                                            class="btn btn-xs white">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{url('/commercial/lien')}}"
                                           class="btn btn-xs btn-warning">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($lienList as $data)
                                    <tr>
                                        <td>{{$data->factory->factory_name}}</td>
                                        <td>{{$data->lien_no}}</td>
                                        <td>{{$data->lien_date}}</td>
                                        <td>{{$data->bank->name}}</td>
                                        <td style="width: 100px; padding: 2px">
                                            <a class="btn btn-xs btn-info"
                                               href="{{url('commercial/lien/'.$data->id.'/view')}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ url('commercial/lien/'.$data->id.'/edit') }}"
                                               class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button
                                                type="button"
                                                data-toggle="modal"
                                                ui-target="#animate"
                                                ui-toggle-class="flip-x"
                                                style="margin-left: 2px;"
                                                title="Delete Yarn Receive"
                                                data-target="#confirmationModal"
                                                class="btn btn-xs btn-danger show-modal"
                                                data-url="{{ url('commercial/lien/'.$data->id. '/delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No Lien Found Here</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $lienList->appends(request()->query())->links()  }}
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-md-12 text-center">--}}
{{--                        @if(count($lienList))--}}
{{--                            {{ $lienList->render() }}--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush
