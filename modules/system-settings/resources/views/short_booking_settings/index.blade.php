@extends('skeleton::layout')
@section("title","Short Booking Settings")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Short Booking Settings</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        <form
                            action="{{ url('/short-bookings-settings/1')}}"
                            method="post" id="form">
                            @method('PUT')
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="fabric_percentage">Fabric : Allow Minimum Percentage</label>
                                    <input type="text" id="fabric_percentage" name="fabric_percentage"
                                           class="form-control form-control-sm"
                                           value="{{ old('fabric_percentage') ?? $short_booking_settings->fabric_percentage ?? '' }} "
                                           placeholder="Fabric : Allow Minimum Percentage">
                                    @error('fabric_percentage')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="trims_percentage">Trims : Allow Minimum Percentage</label>
                                    <input type="text" id="trims_percentage" name="trims_percentage"
                                           class="form-control form-control-sm"
                                           value="{{ old('trims_percentage')  ?? $short_booking_settings->trims_percentage ?? '' }}"
                                           placeholder="Trims : Allow Minimum Percentage">
                                    @error('trims_percentage')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <button style="margin-top: 26%;" type="submit" id="submit"
                                            class="btn btn-sm white"><i
                                            class="fa fa-save"></i> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
