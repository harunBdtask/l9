@extends('skeleton::layout')
@section("title","Sub. Cutting Floor")
@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>Subcontract Cutting Floor</h2>
    </div>

    <div class="box-body">
      <div class="row">
        <div class="col-sm-3 col-sm-offset-9">
          <form action="{{ url('subcontract-cutting-floor') }}" method="GET">
            <div class="input-group">
              <input type="text" class="form-control form-control-sm" name="search" value="{{ $search ?? '' }}" placeholder="Search">
              <span class="input-group-btn">
                <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
              </span>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          @include('partials.response-message')
        </div>
      </div>
      <div class="row m-t">
        <div class="col-sm-12 col-md-5">
          @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_cutting_floor_add'))
          <form action="{{ url('subcontract-cutting-floor') }}" method="post" id="form">
            @csrf
            <div class="form-group">
              @php
              $subcontract_factory_options = [];
              if(old('subcontract_factory_profile_id')) {
              $subcontract_factory =
              SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile::find(old('subcontract_factory_profile_id'));
              $subcontract_factory_options = [$subcontract_factory->id =>
              $subcontract_factory->name.'['.$subcontract_factory->factory->factory_name.']'];
              }
              @endphp
              <label for="subcontract_factory_profile_id">Sub Contract Factory</label>
              <select name="subcontract_factory_profile_id" id="subcontract_factory_profile_id" class="form-control form-control-sm">
                @foreach($subcontract_factory_options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
              </select>
              @error('subcontract_factory_profile_id')
              <span class="small text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="floor_name">Floor Name</label>
              <input type="text" id="floor_name" name="floor_name" class="form-control form-control-sm" value="{{ old('floor_name') }}"
                placeholder="Floor Name">
              @error('floor_name')
              <span class="small text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="responsible_person">Responsible Person</label>
              <input type="text" id="responsible_person" name="responsible_person" class="form-control form-control-sm"
                value="{{ old('responsible_person') }}" placeholder="Responsible Person">
              @error('responsible_person')
              <span class="small text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <div class="text-center">
                <button type="submit" id="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save
                </button>
                <a href="{{ url('subcontract-cutting-floor') }}" class="btn btn-sm btn-warning"><i
                    class="fa fa-refresh"></i> Refresh</a>
              </div>
            </div>
          </form>
          @endif
        </div>
        <div class="col-sm-12 col-md-7">
          <table class="table">
            <thead>
              <tr>
                <th>SL</th>
                <th>Sub. Factory</th>
                <th>Floor Name</th>
                <th>Responsible Person</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($data as $item)
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
                <td style="{!! $text_style !!}">{{ $item->subContractFactoryProfile->name }}</td>
                <td style="{!! $text_style !!}">{{ $item->floor_name }}</td>
                <td style="{!! $text_style !!}">{{ $item->responsible_person }}</td>
                <td>{!! $statusDom !!}</td>
                <td>
                  @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_cutting_floor_edit'))
                  <a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-xs btn-warning edit"><i
                      class="fa fa-edit"></i></a>
                  @endif
                  @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_cutting_floor_delete'))
                  <button type="button" class="btn btn-xs {{ $buttonClass }} status-update-modal" data-toggle="modal"
                    data-target="#statusUpdateModal" ui-toggle-class="flip-x" ui-target="#animate"
                    data-url="{{ url('subcontract-cutting-floor/'.$item->id."/status-update") }}"
                    data-alertMessage="{!! $alertMessage !!}" title="{{ $title }}">
                    {!! $iconDom !!}
                  </button>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" align="center">No Data Found</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          <div class="text-center">
            {{ $data->appends(request()->except('page'))->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('manual-production::partials.status_update_modal')
</div>
@endsection

@push('script-head')
<script src="{{ asset('modules/manual-production/js/statusUpdate.js')}}"></script>
<script>
  $(document).on('click', '.edit', function () {
      let id = $(this).data('id');
      $.ajax({
          method: 'get',
          url: `/subcontract-cutting-floor/${id}/edit`,
          success: function (result) {
              $('#form').attr('action', `subcontract-cutting-floor/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
              $('#subcontract_factory_profile_id').append(result.subcontract_factory_profile_option);
              $('#subcontract_factory_profile_id').val(result.subcontract_factory_profile_id).trigger('change');
              $('#floor_name').val(result.floor_name);
              $('#responsible_person').val(result.responsible_person);
              $('#submit').html(`<i class="fa fa-save"></i> Update`);
          },
          error: function (xhr) {
              console.log(xhr)
          }
      })
  });

  $('[name="subcontract_factory_profile_id"]').select2({
    ajax: {
      url: '/manual-production-search/subcontract-factories',
      data: params => ({
        search_query: params.term,
        operation_type: 1
      }),
      processResults: (data, params) => {
        let results;
        return {
          results: data.data,
          pagination: {
            more: false
          }
        }
      },
      delay: 250
    },
    placeholder: 'Select Factory',
    allowClear: true
  });
</script>
@endpush
