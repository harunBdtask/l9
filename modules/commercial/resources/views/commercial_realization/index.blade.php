@extends('skeleton::layout')
@section('title','Commercial Realization List')
@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <h2>Commercial Realization List</h2>
    </div>

    <div class="box-body">
      <div class="row">
        <div class="col-sm-6">
          <a href="{{ url('commercial/realizations/create') }}" class="btn btn-sm btn-info m-b"><i
              class="fa fa-plus"></i> New Realization</a>
        </div>
        <div class="col-sm-4 col-sm-offset-2">
        </div>
      </div>
      @include('partials.response-message')
      <div class="row m-t">
        <div class="col-sm-12">
          <table class="reportTable">
            <thead>
              <tr>
                <th>Sl</th>
                <th>Realization Date</th>
                <th>FDBP/LDBP No.</th>
                <th>Buyer</th>
                <th>Factory</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($commercial_realizations as $commercial_realization)
              <tr>
                <th>{{ $loop->iteration }}</th>
                <td>{{ $commercial_realization->realization_date ? date('d M, Y', strtotime($commercial_realization->realization_date)) : '' }}</td>
                <td>{{ $commercial_realization->bank_ref_bill }}</td>
                <td>{{ $commercial_realization->buyer->name }}</td>
                <td>{{ $commercial_realization->factory->factory_name }}</td>
                <td style="padding: 2px">
                  <a href="{{ url('/commercial/realizations/'. $commercial_realization->id .'/edit') }}"
                    class="btn btn-xs btn-warning">
                    <i class="fa fa-edit"></i>
                  </a>

                  {{-- <a target="_blank" class="btn btn-xs btn-info"
                    href="{{ url('/commercial/realizations/'. $commercial_realization->id .'/show') }}">
                    <i class="fa fa-eye"></i>
                  </a> --}}

                  <button style="margin-left: 2px;" type="button" class="btn btn-xs btn-danger show-modal"
                    title="Delete document submission" data-toggle="modal" data-target="#confirmationModal"
                    ui-toggle-class="flip-x" ui-target="#animate"
                    data-url="{{ url('/commercial/realizations/'.$commercial_realization->id) }}">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
              @empty
              <tr>
                <th colspan="6">No Data Found</th>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="row m-t">
        <div class="col-sm-12">
          {{ $commercial_realizations->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection