@extends('skeleton::layout')
@section("title","Commercial Mailing Variable Settings")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Commercial Mailing Variable Settings</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                         <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>


                    <form action="{{ url('/commercial-mailing-variable-settings') }}" method="post" id="form">
                        @csrf
                        <input type="hidden" name="id" value="1">
                        <div class="form-group row">
                            <label for="mailing" class="col-sm-3  form-control-sm-label  text-right"><b>Mailing</b></label>
                            <div class="col-sm-4">
                                <select name="mailing" id="mailing" class="form-control select2-input">
                                    <option value="">Select</option>
                                    @foreach($status as $key=>$item)
                                    <option value="{{ $item['id'] }}" {{ @$settings->mailing==$item['id']?'selected':null }}>{{ $item['text'] }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('mailing'))
                                    <span class="text-danger">{{ $errors->first('mailing') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="teamleader_id" class="col-sm-3 form-control-sm-label  text-right"><b> Team Leader</b></label>
                            <div class="col-sm-4">
                                <select name="teamleader_id" id="teamleader_id" class="form-control select2-input">
                                    <option value="">Select</option>
                                    @foreach($teamleaders as $key=>$item)
                                    <option value="{{ $item->user->id }}"  {{ @$settings->teamleader_id==$item->user->id ?'selected':null }}>{{ $item->user->screen_name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('teamleader_id'))
                                    <span class="text-danger">{{ $errors->first('teamleader_id') }}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <a href="{{ url('/teams') }}" class="btn btn-success"><i class="fa fa-plus"></i> Add Team Leader</a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3"></label>
                            <div class="col-sm-4">
                            <button type="submit" class="btn btn-success"> Save</button>
                            </div>
                            
                        </div>

                    </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
