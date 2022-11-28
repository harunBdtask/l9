@extends('skeleton::layout')
@section("title","Item Creations")
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-10 col-sm-12 col-md-offset-1">
        <div class="box" >
          <div class="box-header">
            <h2>{{ $itemCreation ? 'Update Item Creation' : 'New Item Creation' }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            {!! Form::model($itemCreation, ['url' => $itemCreation ? 'item-creations/'.$itemCreation->id : 'item-creations', 'method' => $itemCreation ? 'PUT' : 'POST']) !!}
              <div class="row">
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="factory_id">Company</label>
                          {!! Form::select('factory_id', $factories, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'factory_id', 'placeholder' => 'Select Company' , 'required' => 'required']) !!}
                      </div>
                  </div>
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="item_category">Item Category</label>
                          {!! Form::select('item_category', $itemCategories, null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'item_category', 'placeholder' => 'Select Category']) !!}
                      </div>
                  </div>
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="item_group_id">Item Group</label>
                          {!! Form::select('item_group_id', [], null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'item_group_id']) !!}
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="sub_group_code">Sub Group Code</label>
                          {!! Form::text('sub_group_code', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'sub_group_code', 'placeholder' => 'Sub Group Code']) !!}
                      </div>
                  </div>
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="sub_group_name">Sub Group Name</label>
                          {!! Form::text('sub_group_name', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'sub_group_name', 'placeholder' => 'Sub Group Name']) !!}
                      </div>
                  </div>
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="item_code">Item Code</label>
                          {!! Form::text('item_code', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'item_code', 'placeholder' => 'Item Code']) !!}
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="item_description">Item Description</label>
                          {!! Form::text('item_description', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'item_description', 'placeholder' => 'Item Description']) !!}
                      </div>
                  </div>
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="item_size">Item Size</label>
                          {!! Form::text('item_size', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'item_size', 'placeholder' => 'Item Size']) !!}
                      </div>
                  </div>
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="re_order_label">Re Order Label</label>
                          {!! Form::text('re_order_label', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 're_order_label', 'placeholder' => 'Re Order Label']) !!}
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="min_label">Min Label</label>
                          {!! Form::text('min_label', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'min_label', 'placeholder' => 'Min Label']) !!}
                      </div>
                  </div>
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="max_label">Max Label</label>
                          {!! Form::text('max_label', null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'max_label', 'placeholder' => 'Max Label']) !!}
                      </div>
                  </div>
                  <div class="col-sm-12 col-md-4">
                      <div class="form-group">
                          <label for="status">Status</label>
                          {!! Form::select('status', ['Active' => 'Active', 'In Active' => 'In Active'], null, ['class' => 'form-control form-control-sm form-control form-control-sm-sm', 'id' => 'status']) !!}
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-sm-12">
                      <div class="form-group">
                          <a class="btn btn-sm btn-dark" href="{{ url('item-creations') }}"><i class="fa fa-remove"></i> Cancel</a>
                          <button type="submit" class="btn btn-sm white"><i class="fa fa-save"></i> {{ $itemCreation ? 'Update' : 'Create' }}</button>
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

@push('script-head')
    <script>
        $(document).on('change', '#item_category', function () {
            let itemId = $(this).val();
            $('#item_group_id').empty();
            $.ajax({
                method: 'get',
                url: '{{ url('item-creations/get-groups') }}/' + itemId,
                success: function (result) {
                    $.each(result, function (key, value) {
                        let element = `<option value="${value.id}">${value.item_group}</option>`;
                        $('#item_group_id').append(element);
                    })
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
