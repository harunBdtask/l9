@extends('printembrdroplets::layout')
@section('title', 'Factory Recevied '.(($type == 0) ? 'Challan' : 'Tag').' List')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Factory Recevied {{ ($type == 0) ? 'Challan' : 'Tag' }} List</h2>
      </div>
      <div class="box-divider m-a-0"></div>
      <div class="box-body">
        @include('partials.response-message')
        <div class="pull-right" style="margin-bottom: 10px;">
          <form action="{{ Request::path() }}" method="GET">
            <div class="pull-left" style="margin-right: 10px;">
              <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" placeholder="Challan No" name="q"
                     value="{{ $q ?? '' }}">
            </div>
            <div class="pull-right">
              <input type="submit" class="btn btn-sm white m-b" value="Search">
            </div>
          </form>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table class="reportTable">
              <thead>
              <tr>
                <th>SL</th>
                <th>Challan No</th>
                @if ($type == 0)
                  <th>Operation</th>
                  <th>Table</th>
                @endif
                <th>Date</th>
                <th>Created By</th>
                <th>View Bundles</th>
                <th width="15%">Actions</th>
              </tr>
              </thead>
              <tbody>
              @if(count($challans))
                @php
                  $currentPage = $challans->currentPage();
                  $perPage = $challans->perPage();
                  $firstItemSl = ($currentPage - 1) * $perPage;
                @endphp
                @foreach($challans as $challan)
                  <tr class="tr-height">
                    <td>{{ $firstItemSl + $loop->index + 1 }}</td>
                    <td>{{ $challan->challan_no }}</td>
                    @if ($challan->type == 0)
                      <td>{{ OPERATION[$challan->operation_name] }}</td>
                      <td>{{ $challan->print_table->name }}</td>
                    @endif
                    <td>{{ $challan->created_at }}</td>
                    <td>{{ $challan->createdBy->full_name ?? '' }}</td>
                    <td>
                      <a href="{{ url('receive-challan-wise-bundle/' . $challan->challan_no) }}"
                         class="btn btn-xs btn-success">
                        <i class="fa fa-eye"></i>
                      </a>
                    </td>
                    <td>
                      @if ($type == 0)
                        <a class="btn btn-xs btn-primary"
                           href="{{ url('receive-challan-tag/' . $challan->challan_no . '/view') }}">
                          <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-xs btn-success"
                           href="{{ url('receive-challan/' . $challan->challan_no . '/edit') }}">
                          <i class="fa fa-pencil"></i>
                        </a>
                      @else
                        <a class="btn btn-xs btn-success"
                           href="{{ url('create-received-challan-form-tag?tagId=' . $challan->id) }}">
                          Create Challan
                        </a>
                      @endif
                      <button type="button"
                              class="btn btn-xs btn-danger show-modal"
                              data-toggle="modal"
                              data-target="#confirmationModal"
                              ui-toggle-class="flip-x" ui-target="#animate"
                              data-url="{{ url('receive-challan/' . $challan->challan_no) }}">
                        <i class="fa fa-times"></i>
                      </button>
                    </td>
                  </tr>
                @endforeach

              @else
                <tr class="tr-height text-center">
                  <td colspan="{{ ($type == 0) ? 8 : 6 }}">Data not found!</td>
                </tr>
              @endif
            </table>
          </div>
        </div>

        <div class="row">
          <div class="col text-center">
            {{ $challans->render() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
