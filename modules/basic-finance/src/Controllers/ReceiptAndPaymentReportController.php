<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;
use SkylarkSoft\GoRMG\BasicFinance\Exports\Export;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;

class ReceiptAndPaymentReportController extends Controller
{
    const DEBIT = 'dr', CREDIT = 'cr';

    public function reportData(Request $request)
    {
        $reportData['start_date'] = $startDate = $this->startDate ?? Carbon::now()->startOfMonth();
        $reportData['end_date'] = $endDate = $this->endDate ?? Carbon::now();
        $id = Account::query()->factoryFilter()->where('code', '120100000')->first()['id'] ?? null;
        $accounts = $this->accounts($startDate, $endDate, $id);

        $reportData['balances'] = $this->openingTransaction($accounts, $startDate, $endDate);

        $debitVouchers = $this->typeWiseVouchersId($accounts, self::DEBIT);
        $reportData['received'] = $this->receiveTransaction($debitVouchers);

        $creditVouchers = $this->typeWiseVouchersId($accounts, self::CREDIT);
        $reportData['payments'] = $this->paymentTransaction($creditVouchers);

        if ($request->get('type') == 'print') {
            return view(PackageConst::VIEW_NAMESPACE . '::print.receipt_payment_statement', $reportData);
        }

        if ($request->get('type') == 'excel') {
            $title = 'Receipt and Payment Statement';
            $viewFile = PackageConst::PACKAGE_NAME . '::tables.receipt_payment_statement_table';

            return Excel::download((new Export($reportData, $title, $viewFile)), 'receipt_payment_statement.xlsx');
        }

        return view(PackageConst::VIEW_NAMESPACE . '::reports.receipt_and_payment_statement', $reportData);
    }

    public function accounts($startDate, $endDate, $id)
    {
        return Account::query()->factoryFilter()->with([
            'journalEntries' => function ($query) use ($startDate, $endDate) {
                return $query->where('trn_date', '>=', $startDate)
                    ->where('trn_date', '<', $endDate);
            }
        ])->where('parent_ac', $id)->get();
    }

    public function typeWiseVouchersId($accounts, $type)
    {
        return $accounts->map(function ($account) use ($type) {
            return collect($account->journalEntries)->filter(function ($data) use ($type) {
                return $data['trn_type'] == $type;
            });
        })->flatten()->pluck('voucher_id');
    }

    public function openingTransaction($accounts, $startDate, $endDate)
    {
        return $accounts->map(function ($account) use ($startDate, $endDate) {
            $openingBalance = $account->openingBalance($startDate);
            $closingBalance = $account->openingBalance($endDate);

            return [
                "name" => $account->name,
                "opening_balance" => $openingBalance,
                "closing_balance" => $closingBalance,
            ];
        });
    }

    public function receiveTransaction($debitVouchers)
    {
        return Voucher::query()->factoryFilter()->whereIn('id', $debitVouchers)->get()->flatMap(function ($voucher) {
            $voucherDetails = collect($voucher->details);
            $voucherItems = collect($voucherDetails['items'])->filter(function ($item) {
                return isset($item->credit) && $item->credit > 0;
            });

            if (count($voucherItems)) {
                return collect($voucherDetails['items'])->map(function ($item) {
                    return [
                        // 'name' => $voucherDetails['credit_account_name'] ?? ($item->credit > 0 ? $item->account_name : null),
                        'name' => $item->credit > 0 ? $item->account_name : null,
                        'amount' => $item->credit,
                    ];
                })->whereNotNull('name');
            } else {
                return [
                    [
                        'name' => $voucherDetails['credit_account_name'],
                        'amount' => $voucherDetails['total_credit'],
                    ]
                ];
            }
        })->groupBy('name');
    }

    public function paymentTransaction($creditVouchers)
    {
        return Voucher::query()->factoryFilter()->whereIn('id', $creditVouchers)->get()->flatMap(function ($voucher) {
            $voucherDetails = collect($voucher->details);
            $voucherItems = collect($voucherDetails['items'])->filter(function ($item) {
                return isset($item->debit) && $item->debit > 0;
            });

            if (count($voucherItems)) {
                return collect($voucherItems)->map(function ($item) {
                    return [
                        // 'name' => $voucherDetails['debit_account_name'] ?? ($item->debit > 0 ? $item->account_name : null),
                        'name' => $item->debit > 0 ? $item->account_name : null,
                        'amount' => $item->debit,
                    ];
                })->whereNotNull('name');
            } else {
                return [
                    [
                        'name' => $voucherDetails['debit_account_name'],
                        'amount' => $voucherDetails['total_debit'],
                    ]
                ];
            }
        })->groupBy('name');
    }
}
