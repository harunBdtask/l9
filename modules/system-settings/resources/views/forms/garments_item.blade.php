@extends('skeleton::layout')
@section("title","Garments Items")
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box" >
                    <div class="box-header">
                        <h2>{{ $garmentsItem ? 'Update Garments Item' : 'New Garments Item' }}</h2>
                    </div>
                    <div class="box-body">
                        {!! Form::model($garmentsItem, ['url' => $garmentsItem ? 'garments-items/'.$garmentsItem->id : 'garments-items', 'method' => $garmentsItem ? 'PUT' : 'POST']) !!}

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Garments Item</label>

                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'name', 'placeholder' => 'Garments Item']) !!}

                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Commercial Name</label>

                                    {!! Form::text('commercial_name', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'commercial_name', 'placeholder' => 'Commercial Name']) !!}

                                    @if($errors->has('commercial_name'))
                                        <span class="text-danger">{{ $errors->first('commercial_name') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="product_category_id">Product Category</label>
                                    {!! Form::select('product_category_id', $productCategories, null, ['class' => 'form-control form-control-sm', 'id' => 'product_category_id', 'placeholder' => 'Product Category']) !!}

                                    @if($errors->has('product_category_id'))
                                        <span class="text-danger">{{ $errors->first('product_category_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="product_type">Product Type</label>
                                    {!! Form::select('product_type', ['Top' => 'Top', 'Bottom' => 'Bottom'], null, ['class' => 'form-control form-control-sm', 'id' => 'product_type', 'placeholder' => 'Product Type']) !!}

                                    @if($errors->has('product_type'))
                                        <span class="text-danger">{{ $errors->first('product_type') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="standard_smv">Standard SMV</label>

                                    {!! Form::text('standard_smv', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'standard_smv', 'placeholder' => 'Standard SMV']) !!}

                                    @if($errors->has('standard_smv'))
                                        <span class="text-danger">{{ $errors->first('standard_smv') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="efficiency">Efficiency %</label>

                                    {!! Form::number('efficiency', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'efficiency', 'placeholder' => 'Efficiency %', 'step' => '.1']) !!}

                                    @if($errors->has('efficiency'))
                                        <span class="text-danger">{{ $errors->first('efficiency') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    {!! Form::select('status', ['Active' => 'Active', 'In Active' => 'In Active'], null, ['class' => 'form-control form-control-sm', 'id' => 'status']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm white"><i class="fa fa-save"></i> {{ $garmentsItem ? 'Update' : 'Create' }}</button>
                                    <a class="btn btn-sm btn-dark" href="{{ url('garments-items') }}"><i class="fa fa-remove"></i> Cancel</a>
                                </div>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
