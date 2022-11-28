@extends('skeleton::layout')
@section('title', 'Sample Sewing Line')

@push('style')
    <style>
        .table > thead > tr > th,
        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th {
            padding: 3px;
            text-align: center;
        }

        .change-color {
            background: #00a65a;
        }


    </style>
@endpush
@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>Sample Line List</h2>
    </div>
    <div class="row padding">
        <div class="col-sm-6">
            @if(Session::has('permission_of_sewing_lines_add') || getRole() == 'super-admin' || getRole() == 'admin')
            <div class="box-body b-t">
                {{ Form::open(array('id'=> 'form', 'url' => 'sample-line', 'method' => 'POST')) }}
                    <div class="form-group">
                        <label for="floor">Floor</label>
                        {!! Form::select('floor_id', $floors, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'floor', 'placeholder' => 'Select a floor']) !!}
                        @if($errors->has('floor_id'))
                            <span class="text-danger">{{ $errors->first('floor_id') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="line_no">Line No</label>
                        {!! Form::text('line_no', null, ['class' => 'form-control form-control-sm', 'id' => 'line_no', 'placeholder' => 'Give line no']) !!}
                        @if($errors->has('line_no'))
                            <span class="text-danger">{{ $errors->first('line_no') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="sort">Line Sequence</label>
                        {!! Form::text('sort', null, ['class' => 'form-control form-control-sm', 'id' => 'sort', 'placeholder' => 'Sequence No']) !!}
                        @if($errors->has('sort'))
                            <span class="text-danger">{{ $errors->first('sort') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" id="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i> Create</button>
                        <a href="javascript:void(0)" onclick="cancel()" class="btn btn-sm btn-warning">
                            <i class="fa fa-remove"></i> Cancel</a>
                    </div>
                {{ Form::close() }}
            </div>
            @endif
        </div>
        <div class="col-sm-6">
            <div class="box-body b-t">
              @include('partials.response-message')
              <div class="pull-right">
                <form action="" method="GET">
                  <div class="pull-left m-b-1" style="margin-right: 10px;">
                    <input type="text" class="form-control form-control-sm" name="q" value="{{ request('q') ?? '' }}">
                  </div>
                  <div class="pull-right">
                    <input type="submit" class="btn btn-sm white" value="Search">
                    <a href="sample-line" class="btn btn-sm white">Cancel</a>
                  </div>
                </form>
              </div>

              <table class="reportTable">
                <thead>
                  <tr>
                    <th>SL</th>
                    <th>Floor No.</th>
                    <th>Line No.</th>
                    <th>Line Sequence</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @if(!$lines->getCollection()->isEmpty())
                    @foreach($lines->getCollection() as $line)
                      <tr class="tr-height">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $line->floor->floor_no ?? '' }}</td>
                        <td>{{ $line->line_no }}</td>
                        <td>{{ $line->sort }}</td>
                        <td>
                        @if(Session::has('permission_of_sewing_lines_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                        <a href="javascript:void(0)" data-id="{{ $line->id }}" class="btn btn-xs btn-success edit"><i class="fa fa-edit"></i></a>
                        @endif
                        @if(Session::has('permission_of_sewing_lines_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                          <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('sample-line/'.$line->id) }}">
                            <i class="fa fa-times"></i>
                          </button>
                        @endif
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr class="tr-height">
                      <td colspan="4" class="text-danger" align="center">No Data Found<td>
                    </tr>
                  @endif
                </tbody>
                <tfoot>
                  @if($lines->total() > 15)
                    <tr>
                      <td colspan="5" align="center">{{ $lines->appends(request()->except('page'))->links() }}</td>
                    </tr>
                  @endif
                </tfoot>
              </table>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
  <script>
    $(document).on('click', '.edit', function () {
        let id = $(this).data('id');
        $.ajax({
            method: 'get',
            url: `{{ url('sample-line/${id}') }}`,
            success: function (result) {
                $('#box-header').addClass('change-color')
                $('#floor').val(result.floor_id).change();
                $('#line_no').val(result.line_no);
                $('#sort').val(result.sort);
                $('#form').attr('action', `sample-line/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                $('#submit').html(`<i class="fa fa-save"></i> Update`);
            },
            error: function (xhr) {
                console.log(xhr)
            }
        })
    })

    function cancel() {
        $('#box-header').removeClass('change-color')
        $('#floor').val('').trigger('change');
        $('#line_no').val('');
        $('#sort').val('');
        $('#form').attr('action', 'sample-line').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);
        $('#submit').html(`<i class="fa fa-save"></i> Create`);
        $('.text-danger').hide();
    }
  </script>
@endsection
