@extends('skeleton::layout')
@section('title', 'Other Factories')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Print/Wash/Knitting Factory List</h2>
      </div>
      <div class="box-body">
        @if(Session::has('permission_of_others_factories_add') || Session::has('permission_of_print_factories_add') || Session::has('permission_of_wash_factories_add') || Session::has('permission_of_knitting_factories_add') || getRole() == 'super-admin' || getDept() == 'print-send')
          <a href="{{url('others-factories/create')}}" class="btn btn-sm white m-b add-new-btn btn-sm print-delete">
            <i class="glyphicon glyphicon-plus"></i>New Print/Wash/Knitting Factory
          </a>
        @endif
        <div class="pull-right print-delete" style="padding-right: 0px!important;">
          <form action="{{url('others-factories')}}" method="GET">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-5">
                  {!! Form::select('column', $columns, null, ['class' => 'form-control form-control-sm search-select']) !!}
                  {!! Form::hidden('search', null) !!}
                </div>

                <div class="col-sm-4" class="my-input">
                  {!! Form::text('q', null, ['class' => 'form-control form-control-sm', 'placeholder'=>'Factory Short Name' ]) !!}
                </div>

                <div class="col-sm-3">
                  {!! Form::submit('Search', ['class' => 'btn btn-md btn-primary button-class']) !!}
                </div>
              </div>
            </div>
          </form>
        </div>
        <table class="reportTable">
          <thead>
          <tr>
            <th>SL</th>
            <th>Factory Type</th>
            <th>Factory Name</th>
            <th>Factory Short Name</th>
            <th>Factory Address</th>
            <th>Resposible Person</th>
            <th>Phone No.</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @if(!$print_factories->getCollection()->isEmpty())
            @foreach($print_factories as $factory)
              <tr class="tr-height">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $factory->factory_type }}</td>
                <td>{{ $factory->factory_name }}</td>
                <td>{{ $factory->factory_short_name }}</td>
                <td>{{ $factory->factory_address }}</td>
                <td>{{ $factory->responsible_person }}</td>
                <td>{{ $factory->phone_no }}</td>
                <td>
                  @if(Session::has('permission_of_others_factories_edit')
                    || Session::has('permission_of_print_factories_edit')
                    || Session::has('permission_of_wash_factories_edit')
                    || Session::has('permission_of_knitting_factories_edit')
                    || getRole() == 'super-admin' || getDept() == 'print-send'
                    || getRole() == 'admin')
                    <a class="btn btn-xs btn-success" href="{{ url('others-factories/'.$factory->id.'/edit') }}"><i
                          class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_others_factories_delete')
                    || Session::has('permission_of_print_factories_delete')
                    || Session::has('permission_of_wash_factories_delete')
                    || Session::has('permission_of_knitting_factories_delete')
                    || getRole() == 'super-admin'
                    || getRole() == 'admin')
                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                            data-url="{{ url('others-factories/'.$factory->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="8" align="center">No Data</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($print_factories->total() > 15)
            <tr>
              <td colspan="8" align="center">{{ $print_factories->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
