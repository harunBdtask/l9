<div class="box-body b-t">
    <div class="row">
        <div class="col-md-11" style="color: red; font-size: 28px;">
            <span><u><b>UNAPPROVE VOUCHERS</b></u></span>
        </div>

        <div class="col-md-1">
            <button class="form-control form-control-sm btn btn-primary" id="posts">Posts</button>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table reportTable" id="tabular-form">
        <thead class="thead-light" style="background-color: deepskyblue;">
        <tr>
            <th>DATE</th>
            <th>VOUCHER NO</th>
            <th>VOUCHER TYPE</th>
            <th>FC CURRENCY</th>
            <th>HOME CURRENCY</th>
            <th>COMPANY</th>
            <th>PROJECT</th>
            <th>UNIT</th>
            <th>USER</th>
            <th>SELECT</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
            @php
                $totalFcCurrency = 0;
                $totalHomeCurrency = 0;
            @endphp
        @foreach($unapproveVouchers as $unapproveVoucher)
            @php
                $fcCurrency = $unapproveVoucher->details->total_debit_fc ?? $unapproveVoucher->details->total_credit_fc;
                $totalFcCurrency += $fcCurrency;
                $homeCurrency = $unapproveVoucher->details->total_debit ?? $unapproveVoucher->details->total_credit;
                $totalHomeCurrency += $homeCurrency;
            @endphp
            <tr>
                <td>{{ $unapproveVoucher->trn_date }}</td>
                <td>{{ $unapproveVoucher->voucher_no }}</td>
                <td>{{ ucfirst(\SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::VOUCHER_TYPE[$unapproveVoucher->type_id]) }}</td>
                <td>{{ number_format($fcCurrency, 2) }}</td>
                <td>{{ number_format($homeCurrency, 2) }}</td>
                <td>{{ $unapproveVoucher->factory->factory_name }}</td>
                <td>{{ $unapproveVoucher->project->project }}</td>
                <td>{{ $unapproveVoucher->unit->unit }}</td>
                <td>{{ $unapproveVoucher->createdUser->first_name . ' ' . $unapproveVoucher->createdUser->last_name }}</td>
                <td>
                    {!! Form::checkbox('voucher_id', $unapproveVoucher->id, false, ['id' => 'voucher_id']) !!}
                </td>
                <td>
                    <a
                        class="btn btn-xs btn-primary"
                        href="{{ url('finance/vouchers/'.$unapproveVoucher->id) }}"
                        onclick="window.open(this.href,'_blank'); return false;"
                    ><i class="fa fa-eye"></i></a>
                    <button
                        type="button" class="btn btn-xs btn-info" id="cancel" data-toggle="modal"
                        data-target="#voucher-cancel" data="{{ $unapproveVoucher->id }}"
                    ><i class="fa fa-arrow-left"></i></button>
                    <button
                        type="button" class="btn btn-xs btn-success" id="post" data-toggle="modal"
                        data-target="#voucher-post" data="{{ $unapproveVoucher->id }}"
                    ><i class="fa fa-check-square"></i></button>
                </td>
            </tr>
        @endforeach
            <tr style="font-weight: bold">
                <td style="text-align: right;" colspan="3">Total</td>
                <td>{{ number_format($totalFcCurrency, 2) }}</td>
                <td>{{ number_format($totalHomeCurrency, 2) }}</td>
                <td colspan="6"></td>
            </tr>
        </tbody>
    </table>

    <div id="voucher-post" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => '', 'method' => 'put']) !!}
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <p>Do you really want to make transaction?</p>
                    <input type="hidden" name="status_id" value="3">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn danger p-x-md" data-dismiss="modal">No</button>
                    <button type="submit" class="btn success p-x-md"
                            onClick="this.disabled=true; this.value='Sending…'; this.form.submit();">Yes
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div id="voucher-cancel" class="modal fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => '', 'method' => 'put']) !!}
                <div class="modal-header">
                    <h5 class="modal-title">Explanation</h5>
                </div>
                <div class="modal-body text-center p-lg">
                    <input type="hidden" name="status_id" value="5">
                    <textarea class="form-control" name='message' rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn danger p-x-md" data-dismiss="modal">No</button>
                    <button type="submit" class="btn success p-x-md"
                            onClick="this.disabled=true; this.value='Sending…'; this.form.submit();">Yes
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
