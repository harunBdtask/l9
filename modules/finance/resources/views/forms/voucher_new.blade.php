@extends('finance::layout')

@php
    $title = ucfirst(request()->get('voucher_type')) . ' Voucher';
@endphp

@section('title', $title)

@section('styles')
    <style type="text/css">
        select.c-select {
            min-height: 2.375rem;
        }

        input[type=date].form-control form-control-sm, input[type=time].form-control form-control-sm, input[type=datetime-local].form-control form-control-sm, input[type=month].form-control form-control-sm {
            line-height: 1rem;
        }

        .select2-selection {
            min-height: 2.375rem;
        }

        .select2-selection__rendered, .select2-selection__arrow {
            margin: 4px;
        }

        .invalid, .invalid + .select2 .select2-selection {
            border-color: red !important;
        }
    </style>
@endsection

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        @if(!isset($voucher))
                            <h4>Voucher Entry</h4>
                        @else
                            <h4>Voucher Update</h4>
                        @endif
                        <div class="btn-toolbar form-group form-inline form-buttons" role="toolbar"
                             aria-label="Toolbar with button groups"
                             style="display: block;float: right;margin-top: -26px;">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <a type="button" class="btn btn-success"
                                    href="/finance/vouchers">Un-approved Voucher
                                    </a>
                                </div>
                                <div class="btn-group mr-2" role="group" aria-label="Second group">
                                    <a type="button" class="btn btn-warning"
                                    href="/finance/vouchers">Approved Voucher</a>
                                </div>
                                <div class="btn-group" role="group" aria-label="Third group">
                                    <a type="button" class="btn btn-info"
                                    href="/finance/vouchers">All</a>
                                </div>
                        </div>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body" style="min-height: 600px">
                        @if (!isset($voucher))
                            @include('finance::forms.voucher_new_entry')
                        @else
                            @include('finance::forms.voucher_new_edit')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
