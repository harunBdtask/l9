@extends('printembrdroplets::layout')
@section('title', ($type == 0) ? 'Qc Tag' : 'Delivery')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>{{ ($type == 0) ? 'Qc Tag' : 'Delivery' }} List</h2>
      </div>
      <div class="box-divider m-a-0"></div>
      <div class="box-body">
        @include('partials.response-message')
        <div class="pull-right" style="margin-bottom: 10px;">
          <form action="{{ url('/print-factory-delivery-list') }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm" placeholder="Challan No" name="q" value="{{ $q ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm white" value="Search">
            </div>
          </form>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table class="reportTable">
              <thead>
              <tr>
                <th>SL</th>
                <th>{{ ($type == 0) ? 'Tag' : 'Challan' }} No</th>
                @if ($type == 1)
                  <th>Table No</th>
                @endif
                <th>Created Date</th>
                <th>Created By</th>
                <th>View Bundles</th>
                <th width="18%">Actions</th>
              </tr>
              </thead>
              <tbody>
              @if(count($challanOrTags))
                @php
                  $currentPage = $challanOrTags->currentPage();
                  $perPage = $challanOrTags->perPage();
                  $firstItemSl = ($currentPage - 1) * $perPage;
                @endphp
                @foreach($challanOrTags as $challanOrTag)
                  <tr class="tr-height">
                    <td>{{ $firstItemSl + $loop->index + 1 }}</td>
                    <td>{{ $challanOrTag->challan_no }}</td>
                    @if ($type == 1)
                      <td>{{ $challanOrTag->delivery_factory->factory_name }}</td>
                    @endif
                    <td>{{ $challanOrTag->created_at }}</td>
                    <td>{{ $challanOrTag->createdBy->full_name ?? '' }}</td>
                    <td>
                      <a href="{{ url('delivery-challan-wise-bundle/' . $challanOrTag->challan_no) }}"
                         class="btn btn-xs btn-primary">
                        <i class="fa fa-eye"></i>
                      </a>
                    </td>
                    <td>
                      <a class="btn btn-xs btn-success"
                         href="{{ url('create-delivery-challan-from-tag?tagId='. $challanOrTag->id) }}">
                        Create Challan
                      </a>
                      <a class="btn btn-xs btn-primary"
                         href="{{ url('view-challan-or-tag?tag_or_challan='. $challanOrTag->challan_no) }}">
                        <i class="fa fa-eye"></i>
                      </a>
                      <button type="button"
                              class="btn btn-xs btn-danger show-modal"
                              data-toggle="modal"
                              data-target="#confirmationModal"
                              ui-toggle-class="flip-x" ui-target="#animate"
                              data-url="{{ url('delete-delivery-tag-challan/' . $challanOrTag->id) }}">
                        <i class="fa fa-times"></i>
                      </button>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="{{ ($type == 0) ? 7 : 6 }}">Data not found!</td>
                </tr>
              @endif
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col text-center">
            {{ $challanOrTags->render() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
