@extends('skeleton::layout')
@section('title', 'Department List')

@push('style')
    <style>
        .search-form{
            margin-top: -10px;
        }
        .data-table{
            margin-top: -35px;
        }
        .search-div{
            margin-top: -35px;
        }
    </style>
@endpush


@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>Department List</h2>
    </div>
      <div class="row padding">
          <div class="col-sm-12 col-md-3">
              <div class="box form-colors">
                  <div class="box-header">
                      @if(Session::has('permission_of_departments_add') || getRole() == 'super-admin' || getRole() == 'admin')

                          {!! Form::model($department, ['url' => 'departments', 'method' => 'POST', 'id'=>'form']) !!}

                          <div class="form-group">
                              <label for="department_name" ><b>Name</b></label>
                              {!! Form::text('department_name', null, ['class' => 'form-control form-control-sm', 'id' => 'department_name', 'placeholder' => 'Write department\'s name here']) !!}

                              @if($errors->has('department_name'))
                                  <span class="text-danger">{{ $errors->first('department_name') }}</span>
                              @endif
                          </div>
                          <div class="form-group">
                              <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Create </button>
                              <button type="button" onclick="cancel()" class="btn btn-sm btn-warning"><a href="javascript:void(0)"><i class="fa fa-remove"></i> Cancel</a></button>
                          </div>
                          {!! Form::close() !!}
                      @endif
                  </div>
              </div>
          </div>
          <div class="col-sm-12 col-md-9">

              <div class="row padding search-div">
                  <div class="col-md-6"></div>
                  <div class="col-md-6">
                      <form action="{{ url('/department-search') }}" method="GET">
                          <div class="input-group">
                              <input type="text" class="form-control form-control-sm" name="search"
                                     value="{{ $search ?? '' }}" placeholder="Search">
                              <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                          </div>
                      </form>
                  </div>
              </div>
              @include('partials.response-message')
              <br>
              <table class="reportTable data-table">
                  <thead>
                  <tr>
                      <th width="20%">SL</th>
                      <th width="30%">Department Name</th>
                      <th width="20%">Actions</th>
                  </tr>
                  </thead>
                  <tbody id="table">
                  @if(!$departments->getCollection()->isEmpty())
                      @foreach($departments->getCollection() as $department)
                          <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $department->department_name }}</td>
                              <td>
                                  @if(Session::has('permission_of_departments_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                      <a href="javascript:void(0)" data-id="{{ $department->id }}" class="btn btn-sm white edit"><i class="fa fa-edit"></i></a>
                                  @endif
                                  @if(Session::has('permission_of_departments_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                      <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('departments/'.$department->id) }}">
                                          <i class="fa fa-times"></i>
                                      </button>
                                  @endif
                              </td>
                          </tr>
                      @endforeach
                  @else
                      <tr>
                          <td colspan="4" align="center">No Parts<td>
                      </tr>
                  @endif
                  </tbody>
                  <tfoot>
                  @if($departments->total() > 10)
                      <tr>
                          <td colspan="4" align="center">{{ $departments->appends(request()->except('page'))->links() }}</td>
                      </tr>
                  @endif
                  </tfoot>
              </table>
          </div>
      </div>
{{--    <div class="box-body b-t">--}}
{{--      @if(Session::has('permission_of_departments_add') || getRole() == 'super-admin' || getRole() == 'admin')--}}
{{--        <a class="btn btn-sm white m-b" href="{{ url('departments/create') }}">--}}
{{--          <i class="glyphicon glyphicon-plus"></i>New Department--}}
{{--        </a>--}}
{{--      @endif--}}
{{--      @include('partials.response-message')--}}

{{--    </div>--}}
{{--    <table class="reportTable">--}}
{{--      <thead>--}}
{{--        <tr>--}}
{{--          <th width="20%">SL</th>--}}
{{--          <th width="30%">Department Name</th>--}}
{{--          <th width="20%">Actions</th>--}}
{{--        </tr>--}}
{{--      </thead>--}}
{{--      <tbody>--}}
{{--        @if(!$departments->getCollection()->isEmpty())--}}
{{--          @foreach($departments->getCollection() as $department)--}}
{{--            <tr>--}}
{{--              <td>{{ $loop->iteration }}</td>--}}
{{--              <td>{{ $department->department_name }}</td>--}}
{{--              <td>--}}
{{--              @if(Session::has('permission_of_departments_edit') || getRole() == 'super-admin' || getRole() == 'admin')--}}
{{--                <a class="btn btn-sm white" href="{{ url('departments/'.$department->id.'/edit') }}"><i class="fa fa-edit"></i></a>--}}
{{--              @endif--}}
{{--              @if(Session::has('permission_of_departments_delete') || getRole() == 'super-admin' || getRole() == 'admin')--}}
{{--                <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('departments/'.$department->id) }}">--}}
{{--                  <i class="fa fa-times"></i>--}}
{{--                </button>--}}
{{--              @endif--}}
{{--              </td>--}}
{{--            </tr>--}}
{{--          @endforeach--}}
{{--        @else--}}
{{--          <tr>--}}
{{--            <td colspan="4" align="center">No Parts<td>--}}
{{--          </tr>--}}
{{--        @endif--}}
{{--      </tbody>--}}
{{--      <tfoot>--}}
{{--        @if($departments->total() > 15)--}}
{{--          <tr>--}}
{{--            <td colspan="4" align="center">{{ $departments->appends(request()->except('page'))->links() }}</td>--}}
{{--          </tr>--}}
{{--        @endif--}}
{{--      </tfoot>--}}
{{--    </table>--}}
  </div>
</div>
@endsection

@push('script-head')
    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('departments') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `departments/${result.id}`).append(`<input type="hidden" id="_method" name="_method" value="PUT"/>`);
                    $('#department_name').val(result.department_name);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })

        function cancel(){
            $('#department_name').val('');
            $('#form').attr('action', '/departments').append(`<input type="hidden" id="_method" name="_method" value="POST"/>`);
            $('#submit').html(`<i class="fa fa-save"></i> Create`);
            $('.text-danger').hide();
        }

        $('#searchInput').on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $('#table tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
@endpush
