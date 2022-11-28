@extends('manual-production::layout')
@section('title','Factory Profile')
@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>
        Subcontract Factory Profile
      </h2>
    </div>

    <div class="box-body">
      <div class="row">
        <div class="col-sm-6">
          @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_factory_profile_add'))
          <a href="{{ url('/subcontract-factory-profile/create') }}" class="btn btn-sm btn-info m-b"><i class="fa fa-plus"></i>
            New</a>
          @endif
        </div>
        <div class="col-sm-4 col-sm-offset-2">
          <form action="{{ url('/subcontract-factory-profile') }}" method="GET">
            <div class="input-group">
              <input type="text" class="form-control form-control-sm" name="search" value="{{ $search ?? '' }}" placeholder="Search">
              <span class="input-group-btn">
                <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
              </span>
            </div>
          </form>
        </div>
      </div>
      @include('partials.response-message')
      <div class="row m-t">
        <div class="col-sm-12">
          <table class="reportTable">
            <thead>
              <tr>
                <th>SL</th>
                <th>Type</th>
                <th>Name</th>
                <th>Short Name</th>
                <th>Address</th>
                <th>Res. Person</th>
                <th>Email</th>
                <th>Contract</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($data as $i => $item)
              @php
              $status = $item->status;
              $statusDom = $status ? "<span class=\"text-success\">Active</span>" : "<span
                class=\"text-danger\">InActive</span>";
              $buttonClass = $status ? 'btn-danger' : 'btn-success';
              $iconDom = $status ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>';
              $alertMessage = $status ? 'Do you want to make this item inactive?' : 'Do you want to make this item
              active?';
              $title = $status ? 'Make inactive' : 'Make active';
              $text_style = !$status ? 'text-decoration: line-through;' : '';
              @endphp
              <tr>
                <td style="{!! $text_style !!}">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                <td style="{!! $text_style !!}">{{ $types[$item->operation_type] }}</td>
                <td style="{!! $text_style !!}">{{ $item->name }}</td>
                <td style="{!! $text_style !!}">{{ $item->short_name }}</td>
                <td style="{!! $text_style !!}">{{ $item->address  }}</td>
                <td style="{!! $text_style !!}">{{ $item->responsible_person }}</td>
                <td style="{!! $text_style !!}">{{ $item->email }}</td>
                <td style="{!! $text_style !!}">{{ $item->contact_no }}</td>
                <td style="{!! $text_style !!}">{{ $item->remarks }}</td>
                <td>{!! $statusDom !!}</td>
                <td style="padding: 2px">
                  @if(getRole() == 'super-admin' || getRole() == 'admin' ||
                  Session::has('permission_of_factory_profile_edit'))
                  <a class="btn btn-xs btn-warning"
                    href="{{ url('subcontract-factory-profile/'.$item->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                  @endif
                  @if(getRole() == 'super-admin' || getRole() == 'admin' ||
                  Session::has('permission_of_factory_profile_delete'))
                  <button type="button" class="btn btn-xs {{ $buttonClass }} status-update-modal" data-toggle="modal"
                    data-target="#statusUpdateModal" ui-toggle-class="flip-x" ui-target="#animate"
                    data-url="{{ url('subcontract-factory-profile/'.$item->id.'/status-update') }}"
                    data-alertMessage="{!! $alertMessage !!}" title="{{ $title }}">
                    {!! $iconDom !!}
                  </button>
                  @endif
                </td>
              </tr>
              @empty
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="row m-t">
        <div class="col-sm-12">
          {{ $data->links() }}
        </div>
      </div>
    </div>
  </div>
  @include('manual-production::partials.status_update_modal')
</div>
@endsection

@push('script-head')
<script src="{{ asset('modules/manual-production/js/statusUpdate.js')}}"></script>
@endpush
