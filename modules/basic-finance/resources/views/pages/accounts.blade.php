@extends('basic-finance::layout')
@section('title','Chart Of Accounts')
@section('styles')
<style type="text/css">
  .addon-btn-primary {
    padding: 0;
    margin: 0;
    background: #0275d8;
  }
  .addon-btn-primary:hover {
    background: #025aa5;
  }
  .reportTable th.text-left, .reportTable td.text-left {
      text-align: left;
      padding-left: 5px;
  }

  .reportTable th.text-right, .reportTable td.text-right {
      text-align: right;
      padding-right: 5px;
  }

  .reportTable th.text-center, .reportTable td.text-center {
      text-align: center;
  }
</style>
@endsection

@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <h2>Chart Of Accounts</h2>
    </div>
    <div class="box-body b-t">
      <div style="margin-bottom: 15px">
        <a class="btn btn-primary btn-sm" href="{{ url('basic-finance/accounts/create') }}">
          <i class="glyphicon glyphicon-plus"></i> New Account
        </a>
        <div class="pull-right" style="width: 40%">
          <form action="{{ url('basic-finance/accounts') }}" method="GET">
            <div class="input-group">
              <div class="input-group-addon">
                <select style="border: none; background: none;" name="key">
                  <option value="name" {{ request('key') == 'name' ? 'selected' : '' }}>Head of Account</option>
                  <option value="code" {{ request('key') == 'code' ? 'selected' : '' }}>AC Code</option>
                  <option value="fill_no" {{ request('key') == 'fill_no' ? 'selected' : '' }}>Fill No</option>
                  <option value="type" {{ request('key') == 'type' ? 'selected' : '' }}>AC Type</option>
                  <option value="parent_ac" {{ request('key') == 'parent_ac' ? 'selected' : '' }}>Parent AC</option>
                </select>
              </div>
              <input type="text" class="form-control" name="value" value="{{ request('value') }}">
              <div class="input-group-addon addon-btn-primary">
                <button class="btn-sm btn-primary">Search</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      @if(Session::has('success'))
        <div class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <small>{{ Session::get('success') }}</small>
        </div>
      @elseif(Session::has('failure'))
        <div class="col-md-6 col-md-offset-3 alert alert-danger alert-dismissible text-center">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <small>{{ Session::get('failure') }}</small>
        </div>
      @endif

      <table class="reportTable">
        <thead class="thead-light">
          <tr>
            <th class="text-center">A/C Code</th>
            <th class="text-left">Head Of Account</th>
            <!-- <th class="text-left">Particulars</th> -->
            <th class="text-left">Parent A/C</th>
            <th class="text-left">Child A/C</th>
            <th class="text-left">A/C Type</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($accounts as $account)
            <tr class="tr-height">
              <td class="text-center">{{ $account->code }}</td>
              <td class="text-left">{{ $account->name }}</td>
              <!-- <td class="text-left">{{ $account->particulars }}</td> -->
              <td class="text-left">
                {{ $account->parentAc ? $account->parentAc->name.' ('.$account->parentAc->code.')' : 'N/A' }}
              </td>
                <td class="text-left">
                {{ $account->childAcs->count() }}
              </td>
              <td class="text-left">{{ $account->type }}</td>
              <td>
                <a class="btn btn-xs btn-success" href="{{ url('basic-finance/accounts/'.$account->id.'/edit') }}"><i class="fa fa-edit"></i></a>
              </td>
            </tr>
          @empty
            <tr class="tr-height">
              <td colspan="6" class="text-center text-danger">No Account Found</td>
            </tr>
          @endforelse
        </tbody>
        <tfoot>
          @if($accounts->total() > 15)
            <tr>
              <td colspan="10" align="center">{{ $accounts->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection
