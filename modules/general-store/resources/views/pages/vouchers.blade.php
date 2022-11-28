@extends('general-store::layout')
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>VOUCHERS </h2>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row m-b-2">
                    <div class="col-md-2">
                        {{Form::label("type","Voucher Type")}}
                        {{ Form::select('type', ['in' => 'In', 'out' => 'Out'], old('type') ?? null, ['class' => 'form-control select-option', 'id' => 'type', 'placeholder' => 'Select Voucher Type']) }}
                    </div>
                    <div class="col-md-2">
                        {{Form::label("start_date","From Date")}}
                        {{ Form::date('start_date', old('start_date') ?? today()->firstOfMonth(), ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-2">
                        {{Form::label("to_date","To Date")}}
                        {{ Form::date('end_date', old('end_date') ?? today()->lastOfMonth(), ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-2">
                        {{Form::label("voucher_no", "Voucher No")}}
                        {!! Form::text('voucher_no', old('voucher_no') ?? null, ['class' => 'form-control', 'id'=>'voucher_no', 'placeholder' => 'Voucher No...']) !!}
                    </div>

                    {{-- {{Form::label("action","Action")}} --}}
                    <div class="col-md-4 m-t-10" style="margin-top: 30px;">
                        <div class="btn-group">
                            <button id="filter-button" class="btn btn-sm btn-outline text-dark b-info">Filter</button>
                            <button id="clear-button" class="btn btn-sm btn-outline text-dark b-info">Clear</button>
                            <button id="create-button" class="btn btn-sm btn-outline text-dark b-info">Create</button>
                        </div>
                    </div>
                </div>
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                        @endif
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr style="background: ghostwhite;">
                                <th style="text-align:left; padding-left: 1em;">Type</th>
                                <th style="text-align:left; padding-left: 1em;">Voucher No</th>
                                <th style="text-align:left; padding-left: 1em;">Store</th>
                                <th style="text-align:left; padding-left: 1em;">Create Date</th>
                                <th style="text-align:left; padding-left: 1em;">TRN Date</th>
                                <th style="width: 170px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($vouchers as $voucher)
                                <tr>
                                    <td
                                        class="{{ $voucher->type == 'in' ? 'indigo': 'primary' }}"
                                        style="font-weight: bold;"
                                    >
                                        {{ strtoupper($voucher->type) }}
                                    </td>

                                    <td style="text-align:left; padding-left: 1em;">
                                        {{ $voucher->voucher_no }}
                                    </td>
                                    <td style="text-align:left; padding-left: 1em;">
                                        {{ get_store_name($voucher->store) }}
                                    </td>
                                    <td style="text-align:left; padding-left: 1em;">{{ \Carbon\Carbon::parse($voucher->create_at)->toFormattedDateString() }}</td>
                                    <td style="text-align:left; padding-left: 1em;">{{ \Carbon\Carbon::parse($voucher->trn_date)->toFormattedDateString() }}</td>
                                    <td>
                                        @if(! $voucher->readonly)
                                            <a class="btn btn-xs text-info"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               href="{{ url('/general-store/vouchers/' . $voucher->store . '/' . $voucher->id . '/' . $voucher->type) }}"
                                               title="Edita">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a class="btn btn-xs text-success"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               href="{{ url('/general-store/vouchers_transaction/'.$storeId . "/".$voucher->id . '/make_transaction') }}"
                                               onclick="return confirm('Are You Sure?');"
                                               title="Make Transaction">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>

                                            <a class="btn btn-xs text-danger"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               href="{{ url('/general-store/vouchers/' . $voucher->id . '/delete') }}"
                                               onclick="return confirm('Are You Sure?');"
                                               title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-xs"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               href="{{ url('/general-store/vouchers/' . $voucher->id . '/download-barcode') }}"
                                               title="Barcode">
                                                <i class="fa fa-barcode"></i>
                                            </a>
                                        @endif

                                        <a class="btn btn-xs text-dark"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           href="{{ url('/general-store/vouchers/' . $voucher->id . '/view') }}"
                                           title="View">
                                            <i class="fa  fa-eye"></i>
                                        </a>

                                        <a class="btn btn-xs white print"
                                           href="{{ url('/general-store/vouchers/' . $voucher->id . '/print') }}">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="6">Not Found!</td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="text-center print-delete"> {{ $vouchers->render() }}</div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>

        $(document).ready(function () {

            const currentStore = {{ $storeId }};

            $('#store').select2();

            $('#type').select2();

            const value = (selector) => $(selector).val();

            $('#create-button').click(() => gotoURL());

            $('#filter-button').click(() => filterVouchers());

            $('#clear-button').click(() => window.location = `/general-store/vouchers/${currentStore}`);

            function filterVouchers() {

                const store = {{ $storeId }};
                const type = value('#type');
                const startDate = value('input[name=start_date]');
                const endDate = value('input[name=end_date]');
                const voucherNo = value('input[name=voucher_no]');

                let URL = `/general-store/vouchers/${store}?`;
                URL += `type=${type}&start_date=${startDate}&end_date=${endDate}&voucher_no=${voucherNo}`;
                window.location = URL;
            }

            function gotoURL() {

                // if (!value('#store')) {
                //     toastr.warning('Please Select Store!');
                //     return;
                // }

                if (!value('#type')) {
                    toastr.warning('Please Select Type!');
                    return;
                }
                const store = {{ $storeId }};
                window.location = `/general-store/stores/${store}/${value('#type')}`
            }

            $('.print').click(function (e) {
                e.preventDefault();

                var url = $(this).attr('href');

                printPage(url);
            });

            function closePrint() {
                document.body.removeChild(this.__container__);
            }

            function setPrint() {
                this.contentWindow.__container__ = this;
                this.contentWindow.onbeforeunload = closePrint;
                this.contentWindow.onafterprint = closePrint;
                this.contentWindow.focus(); // Required for IE
                this.contentWindow.print();
            }

            function printPage(sURL) {
                var oHiddFrame = document.createElement("iframe");
                oHiddFrame.onload = setPrint;
                oHiddFrame.style.visibility = "hidden";
                oHiddFrame.style.position = "fixed";
                oHiddFrame.style.right = "0";
                oHiddFrame.style.bottom = "0";
                oHiddFrame.src = sURL;
                document.body.appendChild(oHiddFrame);
            }
        });


    </script>
@endpush
