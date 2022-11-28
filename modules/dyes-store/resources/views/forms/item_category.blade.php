@extends('dyes-store::layout')
@section('content')
    {{--    @component('inv::pbox')--}}
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $category ? 'Update Category' : 'New Category' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>

                        {!! Form::model($category, ['url' => $category ? '/dyes-store/items-category/'.$category->id : '/dyes-store/items-category', 'method' => $category ? 'PUT' : 'POST']) !!}

                        <div class="row">
                            <div class="col-md-4 form-group">
                                {{ Form::label('name', 'Name*') }}
                                {{ Form::text('name', $category->name ?? null, ['class' => 'form-control', "required", "data-parsley-required-message" => "Name is required", 'id' => 'name', 'placeholder' => 'Category Name']) }}
                                @component('dyes-store::alert', ['name' => 'name']) @endcomponent
                            </div>

                            <div class="col-md-4 form-group">
                                {{ Form::label('code', 'Code*') }}
                                {{ Form::text('code', $category->code ?? null, ['class' => 'form-control', "required", "data-parsley-required-message" => "Code is required", 'id' => 'name', 'placeholder' => 'Category Code']) }}
                                @component('dyes-store::alert', ['name' => 'code']) @endcomponent
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-4 form-group">
                                @php
                                    $category_data = [];
                                @endphp
                                @foreach($items_category as $key=>$category)
                                    @php
                                        $category_data[$category->id] = $category->name;
                                    @endphp
                                @endforeach
                                {{ Form::label('parent', 'Parent') }}
                                {{ Form::select('parent_id', $category_data, $category->parent_id ?? null, ['class' => 'form-control','id' => 'parent_id', 'placeholder' => 'Select Parent','style'=>'height:38px']) }}
                                @component('dyes-store::alert', ['name' => 'parent_id']) @endcomponent
                            </div>
                            <div class="col-md-4 form-group">
                                {{ Form::label('description', 'Description') }}
                                {{ Form::textarea('description', $category->description ?? null, ['class' => 'form-control','rows'=>2, 'id' => 'description', 'placeholder' => 'Category Description']) }}
                                @component('dyes-store::alert', ['name' => 'description']) @endcomponent
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                <a href="{{ url('/dyes-store/items-category') }}" class="btn btn-danger btn-sm">Cancel</a>
                            </div>
                        </div>
                        {!! Form::close() !!}


                    </div>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>



@endsection
