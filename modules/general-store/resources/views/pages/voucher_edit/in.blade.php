@extends('inventory::layout')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Create Stock In Voucher General Store </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::open(['route' => 'inv.voucher.stock-in.update', 'method' => 'put', 'autocomplete' => 'off']) !!}
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="trn_with">Supplier <dfn>*</dfn></label>
                                    {!! Form::select('trn_with', $suppliers, old('trn_with') ?? $voucher->trn_with, ['class'=>'form-control select-option ', 'id' => 'trn_with', 'placeholder' => 'Search & Select']) !!}
                                    {!! Form::hidden('store', $store) !!}
                                    {!! Form::hidden('type', 'in') !!}
                                    {!! Form::hidden('id', $voucher->id) !!}
                                    @component('inv::alert', ['name' => 'trn_with']) @endcomponent
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="trn_date">Receive Date <dfn>*</dfn></label>
                                    {!! Form::text('trn_date', old('trn_date') ?? $voucher->trn_date,['class'=>'form-control select-option ', 'id' => 'trn_date', 'placeholder' => 'Receive Date']) !!}
                                    @component('inv::alert', ['name' => 'trn_date']) @endcomponent
                                </div>
                            </div>
                        </div>

                        <div class="row m-t-2" id="req_table">
                            <div class="col-sm-12 table-responsive">
                                <table class="reportTable stock_in_table">
                                    <thead>
                                    <tr>
                                        <th>Item <dfn>*</dfn></th>
                                        <th>Qty <dfn>*</dfn></th>
                                        <th>Rate <dfn>*</dfn></th>
                                        <th>UOM <dfn>*</dfn></th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    {{--                  @include('inv::vouchers.edit.breakdowns')--}}


                                    @php
                                        $pluckedItems = $items->pluck('name', 'id');
                                    @endphp

                                    @if(old('item_id'))
                                        @foreach(old('item_id') as $key => $val)
                                            <tr>
                                                <td style="width: 250px;">

                                                    {!! Form::select("item_id[$key]", $pluckedItems, null, ['class'=>'form-control ronly item', 'placeholder' => 'Search & Select', 'readonly']) !!}

                                                </td>
                                                <td>
                                                    {!! Form::text("qty[$key]", null, ['class'=>'form-control']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text("rate[$key]", null, ['class'=>'form-control rate', 'readonly']) !!}
                                                </td>
                                                <td style="width: 150px;">
                                                    {!! Form::select("uom[$key]", $uoms, null, ['class'=>'form-control ronly uom', 'readonly', 'placeholder' => '']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text("remarks[$key]", null, ['class'=>'form-control']) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach($voucher->details as $detail)
                                            <tr>
                                                <td style="width: 250px;">
                                                    {!! Form::select('item_id[]', $pluckedItems, $detail->item_id, ['class'=>'form-control ronly item', 'placeholder' => 'Search & Select', 'readonly']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text('qty[]', $detail->qty, ['class'=>'form-control', isset($detail->code) ? 'readonly' : '']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text('rate[]', $detail->rate, ['class'=>'form-control', 'readonly']) !!}
                                                </td>
                                                <td style="width: 150px;">
                                                    {!! Form::select('uom[]', $uoms, $detail->item_id, ['class'=>'form-control ronly uom', 'readonly', 'placeholder' => '']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text('remarks[]', $detail->remarks, ['class'=>'form-control']) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="row m-t-2">
                            <div class="form-group col-sm-6 ">

                                <button type="submit" class="btn btn-sm btn-info">
                                    Save
                                </button>

                                <a href="{{ route('vouchers') }}" class="btn btn-sm btn-danger">
                                    Cancel
                                </a>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

    <script>

        const body = $('body');

        $(document).ready(function () {

            $('#trn_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                title: 'Delivery Date'
            });

            $('#trn_with').select2();
            $('#requisition_id').select2();
            // $('.item').select2();


            body.on('change', '.item', function () {

                $(this).parents('tr').find('.uom').val(
                    $(this).val()
                );
            });

            body.on('click', '.add', function () {
                $(this).parents('tr').find('select').each(function (index) {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                });

                let last_row_data = $(this).parents('tr').clone();

                $('.stock_in_table')
                    .find('tbody:last')
                    .append(last_row_data);

                $('.item').select2();
            });

            body.on('click', '.remove', function () {
                let children = $('tbody tr').length;
                if (children > 1) {
                    $(this).closest('tr').remove();
                }
            });
        })
    </script>
@endpush
