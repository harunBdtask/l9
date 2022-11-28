@extends('inputdroplets::layout')
@section('title', $title ?? 'Tag/Challan')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h2>{{ $title }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="pull-right" style="margin-bottom: 10px;">
              <form action="{{ url('/search-challan-or-tag') }}" method="GET">
                <div class="pull-left" style="margin-right: 10px;">
                  <input type="hidden" name="type" value="{{ $title == "Challan List" ? 'challan' : 'tag' }}">
                  <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
                </div>
                <div class="pull-right">
                  <input type="submit" class="btn btn-sm white" value="Search">
                </div>
              </form>
            </div>
            <table class="table table-striped">
              <thead>
              <tr>
                <th>SL</th>
                @if(isset($challan_list))
                  <th>Cahallan No.</th>
                  <th>Floor No.</th>
                  <th>Line No.</th>
                @else
                  <th>Tag No.</th>
                @endif
                <th>Date Time</th>
                <th>Created By</th>
                <th>View Bundles</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @if(isset($challan_list) & !empty($challan_list))
                @if(!$challan_list->getCollection()->isEmpty())
                  @foreach($challan_list->getCollection() as $challan)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $challan->challan_no }}</td>
                      <td>{{ isset($challan->floor_no) ? $challan->floor_no :'' }}</td>
                      <td>{{ isset($challan->line_no) ? $challan->line_no :'' }}</td>
                      <td>{{ $challan->created_at }}</td>
                      <td>{{ $challan->first_name ." ".$challan->last_name }}</td>
                      <td>
                        <a href="{{ url('view-challan-wise-input-bundles/'.$challan->challan_no) }}"
                           class="btn btn-sm white "><i class="fa fa-eye"></i></a>
                      </td>
                      <td>
                        <a href="{{ url('view-challan/'.$challan->id) }}" class="btn btn-sm white "><i
                              class="fa fa-eye"></i></a> &nbsp;&nbsp; | &nbsp;&nbsp;
                        <a href="{{ url('edit-challan/'.$challan->id) }}" class="btn btn-sm white "><i
                              class="fa fa-edit"></i></a>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="8" align="center">Not found
                    <td>
                  </tr>
                @endif
              @elseif(isset($tag_list) & !empty($tag_list))
                @if(!$tag_list->getCollection()->isEmpty())
                  @foreach($tag_list->getCollection() as $tag)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $tag->challan_no }}</td>
                      <td>{{ $tag->created_at }}</td>
                      <td>{{ $tag->user->first_name ." ".$tag->user->last_name }}</td>
                      <td>
                        <a href="{{ url('view-tag-wise-bundles/'.$tag->challan_no) }}" class="btn btn-sm white "><i
                              class="fa fa-eye"></i></a>
                      </td>
                      <td>
                        <a href="{{ url('view-tag/'.$tag->challan_no) }}"><i class="fa fa-eye"></i></a> &nbsp;&nbsp; |
                        &nbsp;&nbsp;
                        <a href="{{ url('create-challan-for-sewing/'.$tag->id) }}">Create Challan</a>
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="5" align="center">Not found
                    <td>
                  </tr>
                @endif
              @endif
              </tbody>
              <tfoot>
              @if(isset($challan_list))
                @if($challan_list->total() > 15)
                  <tr>
                    <td colspan="8" align="center">{{ $challan_list->appends(request()->except('page'))->links() }}</td>
                  </tr>
                @endif
              @endif
              @if(isset($tag_list))
                @if($tag_list->total() > 15)
                  <tr>
                    <td colspan="5" align="center">{{ $tag_list->appends(request()->except('page'))->links() }}</td>
                  </tr>
                @endif
              @endif
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
