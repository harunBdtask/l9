@extends('printembrdroplets::layout')
@section('title', 'Security Status Update')
@section('content')
<div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Security Status Update</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
             @include('partials.response-message')
              <form method="POST" action="{{ url('/get-security-status-post')}}" accept-charset="UTF-8">
              @csrf
              <input type="hidden" name="id" value="{{ $challan->id }}">
              <div class="row form-group">
                <div class="col-sm-4 col-sm-offset-4">
                  <select name="security_staus" class="form-control form-control-sm">
                      @if(!$challan->security_staus)
        						<option selected="" value="">Select Action</option>
        					  @endif
        						<option @if($challan->security_staus == 'send') selected="selected" @endif  value="send">Sent</option>
                    <option @if($challan->security_staus == 'hold') selected="selected" @endif value="hold">Hold</option>
        						<option @if($challan->security_staus == 'cancel') selected="selected" @endif value="cancel">Cancel</option>
      					  </select>
                </div>
              </div>
              <div class="row form-group m-t-md">
                <div class="text-center">
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
