@extends('skeleton::layout')
@section('title', 'Sewing Line')
@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>Line List</h2>
    </div>
    <div class="box-body b-t">
      @include('partials.response-message')
      @if(Session::has('permission_of_sewing_lines_add') || getRole() == 'super-admin' || getRole() == 'admin')
        <a class="btn btn-sm white m-b" href="{{ url('lines/create') }}">
          <i class="glyphicon glyphicon-plus"></i> New Line
        </a>
      @endif
      <div class="pull-right">
        <form action="{{ url('/search-lines') }}" method="GET">
          <div class="pull-left m-b-1" style="margin-right: 10px;">
            <input type="text" class="form-control form-control-sm" name="q" value="{{ request('q') ?? '' }}">
          </div>
          <div class="pull-right">
            <input type="submit" class="btn btn-sm white" value="Search">
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
                  <a class="btn btn-xs btn-success" href="{{ url('lines/'.$line->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                @endif
                @if(Session::has('permission_of_sewing_lines_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                  <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('lines/'.$line->id) }}">
                    <i class="fa fa-times"></i>
                  </button>
                @endif
                @if(getRole() == 'super-admin')
                  <button type="button" class="btn btn-xs btn-warning sewing-line-taget-action-btn" data-id="{{ $line->id }}" data-toggle="tooltip" data-placement="top" title="Add Line To Target Entry">
                    <i class="fa fa-check"></i>
                  </button>
                @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr class="tr-height">
              <td colspan="5" class="text-danger" align="center">No Lines<td>
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
@endsection
@section('scripts')
  <script>
    $(document).on('click', '.sewing-line-taget-action-btn', function (e) {
      e.preventDefault();
      var id = $(this).attr('data-id');
      var _token = $('meta[name="csrf-token"]').attr('content');
      if(id) {
        loadNow(5)
        $.ajax({
          type : 'POST',
          url : `/add-line-to-todays-sewing-taget`,
          data: {
            id: id,
            _token: _token
          }
        }).done(function(response) {
          if (response.status == 200) {
            alert(response.message)
          }
        }).fail(function(response) {
          console.log(response)
        });
      }
    })
  </script>
@endsection
