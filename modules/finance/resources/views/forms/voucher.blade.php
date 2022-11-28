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
                            <h4>{{ $title }}</h4>
                        @else
                            @if($voucher->type_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::DEBIT_VOUCHER)
                                <h2>Edit Debit Voucher</h2>
                            @elseif($voucher->type_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::CREDIT_VOUCHER)
                                <h2>Edit Credit Voucher</h2>
                            @else
                                <h2>Edit Journal Voucher</h2>
                            @endif
                        @endif
                        <div class="btn-toolbar form-group form-inline form-buttons" role="toolbar"
                             aria-label="Toolbar with button groups"
                             style="display: block;float: right;margin-top: -26px;">
                             @if(request()->get('voucher_type') == 'all')
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <a type="button" class="btn btn-success"
                                    href="/finance/vouchers/create?voucher_type=debit">Un-approved Voucher
                                    </a>
                                </div>
                                <div class="btn-group mr-2" role="group" aria-label="Second group">
                                    <a type="button" class="btn btn-warning"
                                    href="/finance/vouchers/create?voucher_type=credit">Approved Voucher</a>
                                </div>
                                <div class="btn-group" role="group" aria-label="Third group">
                                    <a type="button" class="btn btn-info"
                                    href="/finance/vouchers/create?voucher_type=journal">All</a>
                                </div>
                             @else
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <a type="button" class="btn btn-success"
                                    href="/finance/vouchers/create?voucher_type=debit">Debit Voucher
                                    </a>
                                </div>
                                <div class="btn-group mr-2" role="group" aria-label="Second group">
                                    <a type="button" class="btn btn-warning"
                                    href="/finance/vouchers/create?voucher_type=credit">Credit Voucher</a>
                                </div>
                                <div class="btn-group" role="group" aria-label="Third group">
                                    <a type="button" class="btn btn-info"
                                    href="/finance/vouchers/create?voucher_type=journal">Journal Voucher</a>
                                </div>
                                <div class="btn-group" role="group" aria-label="Third group">
                                    <a type="button" class="btn btn-primary"
                                    href="/finance/vouchers/create?voucher_type=contra">Contra Voucher</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body" style="height: 600px">
                        @if(!isset($voucher))
                            @if(request()->get('voucher_type') == 'all')
                                @include('finance::forms.voucher_new_entry')
                            @elseif(request()->get('voucher_type') == 'debit')
                                @include('finance::forms.debit_voucher')
                            @elseif(request()->get('voucher_type') == 'credit')
                                @include('finance::forms.credit_voucher')
                            @elseif(request()->get('voucher_type') == 'journal')
                                @include('finance::forms.journal_voucher')
                            @else
                                @include('finance::forms.contra_voucher')
                            @endif
                        @else
                            @if($voucher->type_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::DEBIT_VOUCHER)
                                @include('finance::forms.debit_voucher')
                            @elseif($voucher->type_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::CREDIT_VOUCHER)
                                @include('finance::forms.credit_voucher')
                            @elseif($voucher->type_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::JOURNAL_VOUCHER)
                                @include('finance::forms.journal_voucher')
                            @else
                                @include('finance::forms.contra_voucher')
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
