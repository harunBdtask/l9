@extends('skeleton::layout')
@section("title", "Notification Settings")
@section('styles')
    <style>
        .custom-control-label {
            padding: 0.165rem 0;
        }

        .custom-form-section {
            border-radius: 6px;
        }

        .custom-field {
            width: 90%;
            border: 1px solid #cecece;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Notification Settings</h2>
            </div>
            <div class="box-body b-t">
                <div class="row">
                    <div class="flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <div
                                    class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 custom-form-section">
                        <div class="box">
                            <div class="box-header">
                                {!! Form::open(['url' => '/notification-setting', 'method' => 'POST', 'id' => 'notification-setting-form']) !!}
                                <div class="form-group">
                                    <label for="name" class="custom-control-label">Notification Type</label>
                                    {!! Form::select('notification_type', $types ?? [],request('notification_type'), [
                                        'class' => 'form-control form-control-sm select2-input',
                                        'id' =>'notification_type',
                                        'placeholder'=>'Notification Type'
                                        ]) !!}
                                    @if($errors->has('notification_type'))
                                        <span class="text-danger">{{ $errors->first('notification_type') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="receiver_groups" class="custom-control-label">Groups</label>
                                    {!! Form::select('receiver_groups[]', $groups ?? [], null, [
                                        'class' => 'form-control select2-input form-control-sm',
                                        'id' => 'receiver_groups',
                                        'multiple']) !!}
                                    @if($errors->has('receiver_groups'))
                                        <span class="text-danger">{{ $errors->first('receiver_groups') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success" id="submit">
                                        <i class="fa fa-save"></i>
                                        Submit
                                    </button>
                                    <a href="{{ url('/notification-setting') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i>
                                        Reset</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        {!! Form::open(['url' => '/notification-setting', 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th></th>
                                <td>
                                    {!! Form::select('search_type', $types ?? [] ,request('search_type') ?? null, [
                                        'class' => 'custom-field text-center',
                                        'placeholder' => 'Search here'
                                        ]) !!}
                                </td>
                                <td>

                                </td>
                                <th>
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Type</th>
                                <th>Groups</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!$settings->getCollection()->isEmpty())
                                @foreach($settings->getCollection() as $setting)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $setting->notification_type_value }}</td>
                                        <td>{{ $setting->groups_name }}</td>
                                        <td>
                                            <button type="button" onclick="editGroup({{ $setting->id }})"
                                                    class="edit-btn btn btn-xs btn-success">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('notification-setting/'.$setting->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">No Data</td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            @if($settings->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        class="text-center">{{ $settings->appends(request()->except('page'))->links() }}
                                    </td>
                                </tr>
                            @endif
                            </tfoot>
                        </table>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function editGroup(id) {
            $.ajax({
                url: `/notification-setting/${id}`,
                type: "get",
                dataType: "JSON",
                success(response) {
                    const notificationSetting = response;
                    $("#notification_type").val(notificationSetting.notification_type).select2();
                    $("#receiver_groups").val(notificationSetting.receiver_groups).select2();
                    $("#notification-setting-form").attr('action', `/notification-setting/${notificationSetting.id}`);
                    $("#submit").html('<i class="fa fa-check"></i> Update');
                }
            });
        }
    </script>
@endsection
