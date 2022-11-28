@extends('skeleton::layout')
@section('title', 'Print Table')
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Print Table List</h2>
            </div>
            @if(Session::has('permission_of_print_factory_tables_add') || getRole() == 'super-admin')
                <div class="box-body b-t">
                    <a class="btn btn-sm white m-b" href="{{ url('print-factory-tables/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Print Factory Table
                    </a>
                    <div class="pull-right">
                        <form action="{{ url('/print-factory-tables') }}" method="GET">
                            <div class="pull-left" style="margin-right: 10px;">
                                <input type="text" placeholder="Search By Name" class="form-control form-control-sm" name="q" value="{{ old('q') }}">
                            </div>
                            <div class="pull-right">
                                <input type="submit" class="btn btn-sm white" value="Search">
                            </div>
                        </form>
                    </div>
                </div>
            @endif

          <div class="box-body b-t">
            <div class="row">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                    @endif
                @endforeach

               <div class="col-md-12">
                   <table class="reportTable">
                       <thead>
                       <tr>
                           <th width="5%">SL</th>
                           <th>Table</th>
                           <th width="12%">Actions</th>
                       </tr>
                       </thead>
                       <tbody>
                       @if(count($tables))
                          @php
                           $currentPage = $tables->currentPage();
                           $perPage = $tables->perPage();
                           $firstItemSl = ($currentPage - 1) * $perPage;
                          @endphp
                           @foreach($tables as $table)
                            <tr class="tr-height">
                               <td>{{ $firstItemSl + $loop->index + 1 }}</td>
                               <td>{{ $table->name }}</td>
                               <td>
                                  @if(Session::has('permission_of_print_factory_tables_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                    <a class="btn btn-xs btn-success" href="{{ url('print-factory-tables/'. $table->id) }}"><i class="fa fa-edit"></i></a>
                                  @endif
                                  @if(Session::has('permission_of_print_factory_tables_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x"
                                               ui-target="#animate" data-url="{{ url('print-factory-tables/'. $table->id . '/delete') }}">
                                           <i class="fa fa-times"></i>
                                    </button>
                                   @endif
                               </td>
                             </tr>
                          @endforeach
                         @else
                           <tr>
                              <td colspan="3" align="center">No Tables</td>
                           </tr>
                         @endif
                       </tbody>
                   </table>
               </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    {{ $tables->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
