@extends('skeleton::layout')
@section('title','BTB Margin Lc')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    BTB Margin Lc Pad Preview
                    <i class="fa fa-file-pdf-o btn" style="float:right;cursor: pointer" id="getPdf"
                       data-id="{{ $b2BMarginLC->id }}"></i>
                </h2>
            </div>
            <div class="box-body">
                <div>
                    <p>
                        <b>DATE: {{ isset($b2BMarginLC->application_date) ? \Carbon\Carbon::make($b2BMarginLC->application_date)->format('d.m.Y') : null }}</b>
                    </p>
                    <p><b>TO</b></p>
                    <p><b>{{ $b2BMarginLC->lienBank->contact_person }}</b></p>
                    <p>{{ $b2BMarginLC->lienBank->name }}<br>{!! $b2BMarginLC->lienBank->address !!}</p>
                    <p><b>SUBJECT: <u>APPLICATION FOR OPENING OF BACK TO BACK LC FOR</u>
                            <span
                                style="float: right"><u>${{ number_format($b2BMarginLC->lc_value, 2) }}</u></span></b>
                    </p>
                    <p><b>DEAR SIR</b></p>
                    <p><b>WE SHALL BE HIGHLY PLEASE IF YOU KINDLY OPEN A BACK TO BACK LC FOR US
                            <span
                            style="float: right;margin-right: 20%;"><u>${{ number_format($b2BMarginLC->lc_value, 2) }}</u></span></b>
                    </p>
                    <p><b>THE DETAILS PARTICULARS OF THE LC'S AS GIVEN BELOW:</b></p>
                    <table>
                        <tbody>
                        <tr>
                            <td><b>APPLICANT</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td>
                                <p><b>{{ $b2BMarginLC->factory->factory_name }}
                                        <br>{{ $b2BMarginLC->factory->factory_address }}</b></p>
                            </td>
                        </tr>
                        <tr>
                            <td><b>BENEFICIARY</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td>
                                <p><b>{{ $b2BMarginLC->supplier->name }}<br>{{ $b2BMarginLC->supplier->address_1 }}</b>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td><b>ADVISING BANK</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td><b></b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>SWIFT CODE</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td><b>{{ $b2BMarginLC->lienBank->swift_code ?? '' }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>CREDIT AMOUNT</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td><b>${{ number_format($b2BMarginLC->lc_value, 2) }}</b></td>
                        </tr>
                        <tr>
                            <td><b>TENNOR</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td>
                                <b>
                                    AT {{ $b2BMarginLC->tenor }} DAYS FROM THE DATE OF
                                    {{ $tennorStatus }}
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><b>COMMODITY</b></td>
                            <td style="vertical-align: top;"><b>&nbsp;:&nbsp;</b></td>
                            <td style="vertical-align: top;"><b>{{ strtoupper($b2BMarginLC->item->item_name) }}
                                    FOR {{ $b2BMarginLC->garments_qty }} {{ $b2BMarginLC->unitOfMeasurement->unit_of_measurement }}
                                    EXPORT ORIENTED READYMADE GARMENTS INDUSTRIES AS
                                    PER PROFORMA INVOICE NO: {{ $b2BMarginLC->proformaInvoice }}
                                    OF
                                    THE BENEFICIARY</b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>SHIPMENT DATE</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td>
                                <b>{{ isset($b2BMarginLC->last_shipment_date) ? \Carbon\Carbon::make($b2BMarginLC->last_shipment_date)->format('d.m.Y') : null }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>EXPIRY DATE</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td>
                                <b>{{ isset($b2BMarginLC->lc_expiry_date) ? \Carbon\Carbon::make($b2BMarginLC->lc_expiry_date)->format('d.m.Y') : null }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>PARTIAL SHIPMENT</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td><b>ALLOWED</b></td>
                        </tr>
                        <tr>
                            <td><b>TRANS SHIPMENT</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td><b>ALLOWED</b></td>
                        </tr>
                        <tr>
                            <td><b>SHIPMENT FROM</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td><b>BENEFICIARY'S FACTORY</b></td>
                        </tr>
                        <tr>
                            <td><b>FOR TRANSPORTATION TO</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td><b>APPLICANTS FACTORY</b></td>
                        </tr>
                        <tr>
                            <td><b>TERMS AND CONDITIONS</b></td>
                            <td><b>&nbsp;:&nbsp;</b></td>
                            <td><b>(01) {{ $exportLc->pluck('lc_no_date')->implode(', ') }} (2) MATURITY
                                    DATE SHOULD BE COUNTED {{ $b2BMarginLC->tenor }} DAYS FROM THE DATE OF DELIVERY AND
                                    PAYMENT IN US DOLLAR BY BANGLADESH BANK FDD.</b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>BANK FILE NO: </b></td>
                            <td><b>:</b></td>
                            <td><b>{{ $exportLc->pluck('bank_file_no')->implode(', ') }}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <br/>
                <br/>
                <br/>
                <br/>
                <b>THANKING YOU</b>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("#getPdf").click(function () {
            const id = $(this).data('id');
            const url = '/commercial/btb-margin-lc/' + id + '/pad-preview/pdf';
            location.assign(url);
        });
    </script>
@endsection
