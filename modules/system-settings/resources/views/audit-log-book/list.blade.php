@extends('skeleton::layout')
@section("title","Audit Log Book")

@section('styles')
    <style>
        .custom-control-label {
            padding: 0.165rem 0;
        }

        .custom-form-section {
            border-radius: 6px;
            /*padding: 13px 0;*/
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
                <h2>Audit Log Book</h2>
            </div>

            <div class="box-body b-t">


                <div class="row">

                    <div class="col-md-12">
                        {!! Form::open(['route' => ['audit-log-book'], 'method' =>'GET']) !!}
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('audit-log-book') }}" type="submit" class="btn btn-xs btn-info">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                                <td>
                                    <span>Date Search</span>
                                    {!! Form::date('date_filter', request()->get('date_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>
                                <td>
                                    <span>User Search</span>
                                    {!! Form::select('user_filter', $users , request()->get('user_filter'), ['class' => 'form-control form-control-sm select2-input']) !!}
                                </td>

                                <td>
                                    <span>Month Search</span>
                                    {!! Form::month('month_filter', request()->get('month_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>

                                <td>
                                    <span>Module Search</span>
                                    {!! Form::text('module_filter', request()->get('module_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!}
                                </td>

                                {{-- <td>
                                    <span>Year Search</span>
                                    <input value="{{ date('Y') }}"name="year_filter" class="custom-field text-center" placeholder="Search here">
                                    {{-- {!! Form::date('Y') ('year_filter', request()->get('year_filter'), ['class' => 'custom-field text-center',
                                    'placeholder' => 'Search here']) !!} --}}
                                {{-- </td --}}

                                </th>
                                <th colspan="2">
                                    <button type="submit" class="btn btn-xs btn-info"><i class="fa fa-search"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="7">&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <th>SL</th>
                                <th>Date And Time</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Module</th>
                                <th>History</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                            @forelse ($audits as $key => $audit)
                                <tr>
                                    <td>{{ $key+ $audits->firstItem() }}</td>
                                    <td>{{ $audit->created_at }}</td>
                                    <td>{{ $audit->user->screen_name }}  </td>
                                    <td>{{ $audit->event }} </td>
                                    <td>{{ $audit->module }}
                                        <a href="{{ $audit->meta['path'] ?? '/' }}" target="_blank">
                                            <i class="fa fa-external-link" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    <td>{{ $audit->history }}</td>
                                    <td>
                                    {{-- <button type="button" data-id="{{ $audit->id }}" data-toggle="modal" id=""
                                       data-target=".bd-example-modal-lg"
                                        class="btn btn-xs btn-success show-modal oldAndNewValue">
                                     <i class="fa fa-eye"></i>
                                         </button> --}}
                                        <a href="{{ $audit->meta['path'] ?? '/' }}" target="_blank">
                                            <i class="fa fa-external-link" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            @if($audits->total() > 15)
                                <tr>
                                    <td colspan="7"
                                        align="center">{{ $audits->appends(request()->except('page'))->links() }}
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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="card" style="width: 100%;">
                                    <div class="card-header" style="text-align: center;">
                                        New Values
                                    </div>
                                    <ul class="list-group list-group-flush new_value">

                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card" style="width: 100%;">
                                    <div class="card-header" style="text-align: center;">
                                        Old Values
                                    </div>
                                    <ul class="list-group list-group-flush old_value">

                                    </ul>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push("script-head")
    <script>
        $(document).on('click', '.oldAndNewValue', function (event) {
            event.preventDefault();
            let oldAndNewValue = $(this).data('id');
            console.log(oldAndNewValue);
            $.ajax({
                method: 'GET',
                url: `audit-value`,
                data: {
                    oldAndNewValue
                },
                success: function (response) {
                    $('.bd-example-modal-lg').modal("show");
                    $('.old_value').empty();
                    if (response.meta.old_values) {
                        $.each(response.meta.old_values, function (key, value) {
                            $('.old_value').append(`
                                        <li class="list-group-item">${key}:  ${value}</li>
                                    `);
                        })
                    }

                },

            })

            $.ajax({
                method: 'GET',
                url: `audit-value`,
                data: {
                    oldAndNewValue
                },
                success: function (response) {
                    $('.bd-example-modal-lg').modal("show");
                    $('.new_value').empty();
                    if (response.meta.new_values) {
                        $.each(response.meta.new_values, function (key, value) {
                            $('.new_value').append(`
                                <li class="list-group-item">${key}:  ${value}</li>
                            `);
                        })
                    }

                },
            })


        });
    </script>
@endpush
