@extends('skeleton::layout')
@section("title","Sub. Finishing Table")
@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>Subcontract Finishing Table</h2>
    </div>

    <div class="box-body">
      <div class="row">
        <div class="col-sm-3 col-sm-offset-9">
          <form action="{{ url('subcontract-finishing-table') }}" method="GET">
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
        <div class="col-sm-12 col-md-3">
          @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_finishing_table_add'))
          <form action="{{ url('subcontract-finishing-table') }}" method="post" id="form">
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
              <select name="subcontract_factory_profile_id" id="subcontract_factory_profile_id"
                class="form-control form-control-sm" required>
                @foreach($subcontract_factory_options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
              </select>
              @error('subcontract_factory_profile_id')
              <span class="small text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              @php
              $subcontract_floor_options = [];
              if(old('subcontract_finishing_floor_id')) {
              $subcontract_finishing_floor =
              SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFinishingFloor::find(old('subcontract_finishing_floor_id'));
              $subcontract_floor_options = [$subcontract_finishing_floor->id =>
              $subcontract_finishing_floor->floor_name.'['.$subcontract_finishing_floor->factory->factory_name.']'];
              }
              @endphp
              <label for="subcontract_finishing_floor_id">Floor Name</label>
              <select name="subcontract_finishing_floor_id" id="subcontract_finishing_floor_id"
                class="form-control form-control-sm" required>
                @foreach($subcontract_floor_options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
              </select>
              @error('subcontract_finishing_floor_id')
              <span class="small text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="table_name">Table Name</label>
              <input type="text" id="table_name" name="table_name" class="form-control form-control-sm" value="{{ old('table_name') }}"
                placeholder="Table Name">
              @error('table_name')
              <span class="small text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="sorting">Sorting</label>
              <input type="number" id="sorting" name="sorting" class="form-control form-control-sm" value="{{ old('sorting') }}"
                placeholder="Sorting">
              @error('sorting')
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
                <a href="{{ url('subcontract-finishing-table') }}" class="btn btn-sm btn-warning"><i
                    class="fa fa-refresh"></i> Refresh</a>
              </div>
            </div>
          </form>
          @endif
        </div>
        <div class="col-sm-12 col-md-9">
          <table class="table" style="font-size: 13px !important">
            <thead>
              <tr>
                <th>SL</th>
                <th>Sub. Factory</th>
                <th>Floor Name</th>
                <th>Table Name</th>
                <th>Sorting</th>
                <th>Res. Person</th>
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
                <td style="{!! $text_style !!}">{{ $item->subContractFinishingFloor->floor_name }}</td>
                <td style="{!! $text_style !!}">{{ $item->table_name }}</td>
                <td style="{!! $text_style !!}">{{ $item->sorting }}</td>
                <td style="{!! $text_style !!}">{{ $item->responsible_person }}</td>
                <td>{!! $statusDom !!}</td>
                <td>
                  @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_finishing_table_edit'))
                  <a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-xs btn-warning edit"><i
                      class="fa fa-edit"></i></a>
                  @endif
                  @if(getRole() == 'super-admin' || getRole() == 'admin' || Session::has('permission_of_finishing_table_delete'))
                  <button type="button" class="btn btn-xs {{ $buttonClass }} status-update-modal" data-toggle="modal"
                    data-target="#statusUpdateModal" ui-toggle-class="flip-x" ui-target="#animate"
                    data-url="{{ url('subcontract-finishing-table/'.$item->id."/status-update") }}"
                    data-alertMessage="{!! $alertMessage !!}" title="{{ $title }}">
                    {!! $iconDom !!}
                  </button>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" align="center">No Data Found</td>
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
          url: '{{ url('subcontract-finishing-table') }}/' + id + '/edit',
          success: function (result) {
              $('#form').attr('action', `subcontract-finishing-table/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
              $('#subcontract_factory_profile_id').val(result.subcontract_factory_profile_id).trigger('change');
              $('#subcontract_finishing_floor_id').val(result.subcontract_finishing_floor_id).trigger('change');
              $('#table_name').val(result.table_name);
              $('#sorting').val(result.sorting);
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
        search: params.term,
        operation_type: 4
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

  $('[name="subcontract_finishing_floor_id"]').select2( {
    ajax: {
      url: '/manual-production-search/finishing-floors',
      data: params => ({
        search_query: params.term,
        sub_factory_id: $('[name="subcontract_factory_profile_id"]').val() || 'null'
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
    placeholder: 'Select Finishing Floor',
    allowClear: true
  });
</script>
@endpush
