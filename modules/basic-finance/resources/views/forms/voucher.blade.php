@extends('basic-finance::layout')

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
    .invalid, .invalid+.select2 .select2-selection {
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
                        @if($voucher->type_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::DEBIT_VOUCHER)
                            <h2>Edit Debit Voucher</h2>
                        @elseif($voucher->type_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::CREDIT_VOUCHER)
                            <h2>Edit Credit Voucher</h2>
                        @else
                            <h2>Edit Journal Voucher</h2>
                        @endif
                    @endif
                </div>
                <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    @if(!isset($voucher))
                        @if(request()->get('voucher_type') == 'debit')
                            @include('basic-finance::forms.debit_voucher')
                        @elseif(request()->get('voucher_type') == 'credit')
                            @include('basic-finance::forms.credit_voucher')
                        @elseif(request()->get('voucher_type') == 'journal')
                            @include('basic-finance::forms.journal_voucher')
                        @else
                            @include('basic-finance::forms.contra_voucher')
                        @endif
                    @else
                        @if($voucher->type_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::DEBIT_VOUCHER)
                            @include('basic-finance::forms.debit_voucher')
                        @elseif($voucher->type_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::CREDIT_VOUCHER)
                            @include('basic-finance::forms.credit_voucher')
                        @elseif($voucher->type_id == \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::JOURNAL_VOUCHER)
                            @include('basic-finance::forms.journal_voucher')
                        @else
                            @include('basic-finance::forms.contra_voucher')
                        @endif
                    @endif
                </div>
            </div>
        </div>
        {{-- DATE	VOUCHER NO	VOUCHER TYPE	ACCOUNTS HEAD	FC DEBIT AMOUNT	FC CREDIT AMOUNT	LOCAL DEBIT AMOUNT	LOCAL CREDIT AMOUNT	NARRATION	ACTION --}}
        <div class="col-md-12">
            <div class="box">
                <div class="box-header"> <b>Your Vouchers Today</b></div>
                <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabular-form">
                            <tr>
                                <th>DATE</th>
                                <th>VOUCHER NO</th>
                                <th>VOUCHER TYPE</th>
                                <th>ACCOUNTS HEAD</th>
                                <th>FC DEBIT AMOUNT</th>
                                <th>FC CREDIT AMOUNT</th>
                                <th>LOCAL DEBIT AMOUNT</th>
                                <th>LOCAL CREDIT AMOUNT</th>
                                <th>NARRATION</th>
                                <th>ACTION</th>
                            </tr>

                            @php $total_debit_fc = $total_credit_fc = $total_debit = $total_credit = 0; @endphp

                            @forelse ($vouchers as $voucher)
                                @php
                                    $details = $voucher->details;
                                    $rowspan = collect($voucher->details->items)->count() + (in_array($voucher->type_id, [1,2])?1:0);
                                @endphp
                                @foreach ($details->items as $item)
                                    @php
                                        $total_debit_fc += ($item->dr_fc??0);
                                        $total_credit_fc += ($item->cr_fc??0);
                                        $total_debit += ($item->dr_bd??0);
                                        $total_credit += ($item->cr_bd??0);
                                    @endphp
                                    <tr>

                                        @if($loop->first)
                                            <td rowspan="{{ $rowspan }}">{{ $voucher->trn_date->toFormattedDateString() }}</td>
                                            <td rowspan="{{ $rowspan }}">{{ $voucher->voucher_no }}</td>
                                            <td rowspan="{{ $rowspan }}">{{ $voucherTypeList[$voucher->type_id]??'' }}</td>
                                        @endif

                                        <td>{{ $item->account_name }}</td>
                                        <td>{{ !empty($item->dr_fc)?$item->dr_fc:'' }}</td>
                                        <td>{{ !empty($item->cr_fc)?$item->cr_fc:'' }}</td>
                                        <td>{{ !empty($item->dr_bd)?$item->dr_bd:'' }}</td>
                                        <td>{{ !empty($item->cr_bd)?$item->cr_bd:'' }}</td>
                                        <td>{{ $item->narration??'' }}</td>
                                        @if($loop->first)
                                            <td rowspan="{{ $rowspan }}">
                                                <a class="btn btn-primary btn-sm" href="{{ url('/basic-finance/vouchers/'.$voucher->id) }}" target="_blank"><i class="fa fa-eye"></i></a>
                                                @if($voucher->status_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::CREATED || $voucher->status_id == \SkylarkSoft\GoRMG\Finance\Models\Voucher::AMEND)
                                                    @permission('permission_of_vouchers_list_edit')
                                                        <a class="btn btn-warning btn-sm" href="{{ url('basic-finance/vouchers/'.$voucher->id.'/edit') }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endpermission
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach

                                {{-- Debit/Credit Voucher --}}
                                @if(in_array($voucher->type_id, [1,2]) )
                                    <tr>

                                        @if($voucher->type_id == 1)

                                        @php
                                            $total_credit_fc += ($details->total_debit_fc??0);
                                            $total_credit += ($details->total_debit??0);
                                        @endphp

                                            <td>{{ $details->credit_account_name??'' }}</td>
                                            <td></td>
                                            <td>{{ !empty($details->total_debit_fc)?$details->total_debit_fc:'' }}</td>
                                            <td></td>
                                            <td>{{ !empty($details->total_debit)?$details->total_debit:'' }}</td>

                                        @elseif($voucher->type_id == 2)

                                        @php
                                            $total_debit_fc += ($details->total_credit_fc??0);
                                            $total_debit += ($details->total_credit??0);
                                        @endphp

                                            <td>{{ $details->debit_account_name??'' }}</td>
                                            <td>{{ !empty($details->total_credit_fc)?$details->total_credit_fc:'' }}</td>
                                            <td></td>
                                            <td>{{ !empty($details->total_credit)?$details->total_credit:'' }}</td>
                                            <td></td>

                                        @endif

                                        <td>{{ collect($details->items)->first()->narration??'' }}</td>

                                    </tr>

                                @endif

                            @empty
                                <tr>
                                    <td colspan="10">No item found</td>
                                </tr>
                            @endforelse

                            @if(!empty($vouchers))
                                <tr>
                                    <th colspan="4" >Total</th>
                                    <th>{{ !empty($total_debit_fc)?$total_debit_fc:'' }}</th>
                                    <th>{{ !empty($total_credit_fc)?$total_credit_fc:'' }}</th>
                                    <th>{{ !empty($total_debit)?$total_debit:'' }}</th>
                                    <th>{{ !empty($total_credit)?$total_credit:'' }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            @section('scripts')
                <script type="text/javascript">
                    console.log('mY test ');
                </script>
            @endsection
        </div>
    </div>
</div>
@endsection
