<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Finance\Models\AcUnit;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\Journal;
use SkylarkSoft\GoRMG\Finance\Models\Voucher;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\AcCompany;
use SkylarkSoft\GoRMG\Finance\Models\CostCenter;
use SkylarkSoft\GoRMG\Finance\Models\Department;
use SkylarkSoft\GoRMG\Finance\Models\BankAccount;
use SkylarkSoft\GoRMG\Finance\Models\ReceiveBank;
use SkylarkSoft\GoRMG\Finance\Models\AcDepartment;
use SkylarkSoft\GoRMG\Finance\Models\VoucherComment;
use SkylarkSoft\GoRMG\SystemSettings\Models\Company;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Finance\Jobs\ChequeNoUpdateJob;
use SkylarkSoft\GoRMG\Finance\Services\VoucherService;
use SkylarkSoft\GoRMG\Finance\Services\CurrencyService;
use SkylarkSoft\GoRMG\Finance\Services\ChequeBookDetailUpdateService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\Finance\Models\Project;

class VouchersController extends Controller
{
    public function index(Request $request)
    {
        $orderType = $request->get('order_type') ?? 'DESC';
        $vouchers = Voucher::query()->when($request->voucher_type, function (Builder $q) use ($request) {
            return $q->where('type_id', $request->voucher_type);
        })->when($request->file_no, function (Builder $q) use ($request) {
            return $q->where('file_no', 'like', '%' . $request->file_no . '%');
        })->when($request->voucher_no, function (Builder $q) use ($request) {
            return $q->where('voucher_no', 'like', '%' . $request->voucher_no . '%');
        })->orderBy('id', $orderType)->paginate();

        return view('finance::pages.vouchers', [
            'voucherTypes' => Voucher::$types,
            'voucherStatuses' => Voucher::$statuses,
            'vouchers' => $vouchers
        ]);
    }

    public function create(Request $request)
    {
        $voucherNo = Voucher::generateVoucherNo($request->get('voucher_type'));
        $voucherType = $request->get('voucher_type');
        $createdDate = Carbon::now();
        $todayDate = $createdDate->format('Y-m-d');

        $currencies = collect(CurrencyService::currencies())->pluck('name', 'id');
        $companies = Factory::query()->pluck('factory_name as name', 'id');
        $departments = Department::all();
        $costCenters = CostCenter::all();
        $receiveBanks = ReceiveBank::query()->pluck('name', 'id');
//        $accounts = Account::query()->has('parentAc')
//            ->with('parentAc')
//            ->get();
        $accounts = Account::query()
            ->has('accountInfo.parentAccount')
            ->has('accountInfo.groupAccount')
            ->has('accountInfo.controlAccount')
            ->with('accountInfo.ledgerAccount')
            ->get()->filter(function ($item) {
                return $item->children()->isEmpty();
            });

        return view('finance::forms.voucher', [
            'accounts' => $accounts,
            'voucherNo' => $voucherNo,
            'voucherType' => $voucherType,
            'currencies' => $currencies,
            'departments' => $departments,
            'companies' => $companies,
            'cost_centers' => $costCenters,
            'receiveBanks' => $receiveBanks,
            'today_date' => $todayDate,
            'created_date' => $createdDate,
        ]);
    }

    public function entry(Request $request)
    {
        $voucherNo = Voucher::generateVoucherNo($request->get('voucher_type'));
        $voucherType = $request->get('voucher_type');
        $createdDate = Carbon::now();
        $todayDate = $createdDate->format('Y-m-d');

        $currencies = collect(CurrencyService::currencies())->pluck('name', 'id');
        $group_companies = Company::query()->pluck('company_name as name', 'id');
        // return collect($group_companies)->keys();
        $companies = Factory::query()->pluck('factory_name as name', 'id');
        // $departments = Department::all();
        $costCenters = CostCenter::all();
        $receiveBanks = ReceiveBank::query()->pluck('name', 'id');
        $bankAccounts = BankAccount::query()->pluck('account_number as name', 'id')->prepend('Select','');
        $voucherTypeList = collect(VoucherService::getTypeList())->pluck('name','id');
        $payModeList = collect(VoucherService::getPayModeList())->pluck('name','id');

        $accounts = Account::query()
            ->where('account_type',3)
            ->has('accountInfo.parentAccount')
            ->has('accountInfo.groupAccount')
            // ->with('accountInfo.controlAccount')
            ->with('accountInfo.ledgerAccount')
            ->get();

            // ->filter(function ($item) {
            //     return $item->children()->isEmpty();
            // });

        return view('finance::forms.voucher_new', [
            'accounts' => $accounts,
            'voucherNo' => $voucherNo,
            'voucherType' => $voucherType,
            'currencies' => $currencies,
            // 'departments' => $departments,
            'group_companies' => $group_companies,
            'companies' => $companies,
            'cost_centers' => $costCenters,
            'receiveBanks' => $receiveBanks,
            'today_date' => $todayDate,
            'created_date' => $createdDate,
            'voucherTypeList' => $voucherTypeList,
            'payModeList' => $payModeList,
            'bankAccounts' => $bankAccounts,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request, Voucher $voucher)
    {
        DB::beginTransaction();
        $voucher->fill($request->all())->save();
        // ChequeNoUpdateJob::dispatchSync($request);
        DB::commit();

        return url('finance/vouchers/' . $voucher->id);
    }
    //Entry for voucher all
    public function store_all(Request $request, Voucher $voucher)
    {
        DB::beginTransaction();
        $voucher->fill($request->all())->save();
        DB::commit();

        return url('finance/vouchers/' . $voucher->id);
    }
    public function storeAndCopy(Request $request, Voucher $voucher)
    {
        DB::beginTransaction();
        $voucher->fill($request->all())->save();
        DB::commit();

        return url('finance/vouchers/' . $voucher->id);
    }

    public function show($id)
    {
        $voucher = Voucher::query()->with('company')->findOrFail($id);
        $comment = $voucher->comments()->orderBy('id', 'desc')->first();
        $voucherType = collect(VoucherService::getTypeList())->where('type_id', $voucher->type_id)->first();
        $signature = ReportSignatureService::getSignatures("FINANCE VOUCHER REPORT");
        $dateTime = Carbon::make($voucher->created_at)->toFormattedDateString();
        return view('finance::pages.voucher_details', [
            'voucher' => $voucher,
            'comment' => $comment,
            'voucher_name' => $voucherType['name'],
            'signature' => $signature,
            'date_time' => $dateTime,
            'voucherStatuses' => Voucher::$statuses,
        ]);
        // if ($voucher->type_id == Voucher::DEBIT_VOUCHER) {
        //     return view('finance::pages.debit_voucher', [
        //         'voucher' => $voucher,
        //         'comment' => $comment
        //     ]);
        // } elseif ($voucher->type_id == Voucher::CREDIT_VOUCHER) {
        //     return view('finance::pages.credit_voucher', [
        //         'voucher' => $voucher,
        //         'comment' => $comment,
        //     ]);
        // }

        // return view('finance::pages.journal_voucher', [
        //     'voucher' => $voucher,
        //     'comment' => $comment
        // ]);
    }

    public function editOld($id)
    {
        $voucher = Voucher::findOrFail($id);
        $accounts = Account::all();
        $companies = AcCompany::query()->pluck('name', 'id')->all();
        $currencies = CurrencyService::currencies();
        $units = $voucher->company_id ? $this->units($voucher->company_id) : [];
        $departments = $voucher->company_id ? $this->getDepartments($voucher->company_id) : [];

        return view('finance::forms.voucher', [
            'units' => $units,
            'departments' => $departments,
            'voucher' => $voucher,
            'accounts' => $accounts,
            'companies' => $companies,
            'currencies' => $currencies,
            'voucherNo' => $voucher->voucher_no,
        ]);
    }

    public function edit(Request $request, $id)
    {
        //edit part
        $voucher = Voucher::findOrFail($id);
        //set Voucher Type
        if ($voucher->type_id == 1) {
            $typeId = 'debit';
        } elseif ($voucher->type_id == 2) {
            $typeId = 'credit';
        } elseif ($voucher->type_id == 3) {
            $typeId = 'journal';
        }
        //set total
        $total_debit = $voucher->details->total_debit?$voucher->details->total_debit:'0.00';
        $total_debit_fc = $voucher->details->total_debit_fc?$voucher->details->total_debit_fc:'0.00';
        $total_credit = $voucher->details->total_credit?$voucher->details->total_credit:'0.00';
        $total_credit_fc = $voucher->details->total_credit_fc?$voucher->details->total_credit_fc:'0.00';
        //
        $voucherNo = Voucher::generateVoucherNo($request->get('voucher_type'));
        $voucherType = $request->get('voucher_type');
        $createdDate = Carbon::now();
        $todayDate = $createdDate->format('Y-m-d');

        $currencies = collect(CurrencyService::currencies())->pluck('name', 'id');
        $group_companies = Company::query()->pluck('company_name as name', 'id');
        // return collect($group_companies)->keys();
        $companies = Factory::query()->pluck('factory_name as name', 'id');
        // $departments = Department::all();
        $costCenters = CostCenter::all();
        $receiveBanks = ReceiveBank::query()->pluck('name', 'id');
        $bankAccounts = BankAccount::query()->pluck('account_number as name', 'id')->prepend('Select','');
        $voucherTypeList = collect(VoucherService::getTypeList())->pluck('name','id');
        $payModeList = collect(VoucherService::getPayModeList())->pluck('name','id');

        $projects = Project::query()->where('factory_id', $voucher->factory_id)->pluck('project as name','id');

        $accounts = Account::query()
            ->where('account_type',3)
            ->has('accountInfo.parentAccount')
            ->has('accountInfo.groupAccount')
            // ->with('accountInfo.controlAccount')
            ->with('accountInfo.ledgerAccount')
            ->get();

            // ->filter(function ($item) {
            //     return $item->children()->isEmpty();
            // });

        return view('finance::forms.voucher_new', [
            'accounts' => $accounts,
            'voucherNo' => $voucherNo,
            'voucherType' => $voucherType,
            'currencies' => $currencies,
            // 'departments' => $departments,
            'group_companies' => $group_companies,
            'companies' => $companies,
            'cost_centers' => $costCenters,
            'receiveBanks' => $receiveBanks,
            'today_date' => $todayDate,
            'created_date' => $createdDate,
            'voucherTypeList' => $voucherTypeList,
            'payModeList' => $payModeList,
            'bankAccounts' => $bankAccounts,
            'voucher' => $voucher,
            'typeId' => $typeId,
            'projects' => $projects,
            'total_debit' => $total_debit,
            'total_debit_fc' => $total_debit_fc,
            'total_credit' => $total_credit,
            'total_credit_fc' => $total_credit_fc,
        ]);
    }
    /**
     * @throws Throwable
     */
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        $voucher = Voucher::query()->findOrFail($id);
        $voucher->fill($request->all())->save();
        // ChequeNoUpdateJob::dispatchSync($request);
        DB::commit();

        if ($voucher->status_id == Voucher::AMEND) {
            VoucherComment::create([
                'voucher_id' => $voucher->id,
                'status_id' => Voucher::CREATED,
                'comment' => 'Amendments incorporated.',
                'commented_by' => \Auth::id()
            ]);
        }
        $voucher->status_id = Voucher::CREATED;
        $voucher->created_by = \Auth::id();
        $voucher->updated_by = \Auth::id();
        $voucher->save();

        return url('finance/vouchers/' . $voucher->id);
    }

    public function approval($id, Request $request)
    {
        $voucher = \DB::transaction(function () use ($id, $request) {
            $voucher = Voucher::findOrFail($id);
            $voucher->status_id = $request->status_id;
            $voucher->save();

            switch ($voucher->status_id) {
                case Voucher::CHECKED:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => 'Every this seems good.',
                        'commented_by' => \Auth::id()
                    ]);
                    break;

                case Voucher::AMEND:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => $request->message,
                        'commented_by' => \Auth::id()
                    ]);
                    break;

                case Voucher::AUTHORIZED:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => 'Authorized for transaction.',
                        'commented_by' => \Auth::id()
                    ]);
                    break;

                case Voucher::POSTED:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => 'Voucher has been posted.',
                        'commented_by' => \Auth::id()
                    ]);

                    $voucher->cheque->update([
                        'status' => 3
                    ]);
                    // $this->journalPosting($voucher);
                    $this->newVoucherPosting($voucher);

                    break;

                case Voucher::CANCELED:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => $request->message,
                        'commented_by' => \Auth::id()
                    ]);
                    break;
            }

            return $voucher;
        });

        return redirect('finance/vouchers/' . $id);
    }

    // Voucher approval posting
    private function newVoucherPosting(Voucher $voucher)
    {
        $entries = [];
        $now = Carbon::now();

        foreach ($voucher->details->items as $item) {
            $entries[] = [
                'trn_date' => $voucher->trn_date,
                'account_id' => $item->ledger_id??$item->account_id,
                'trn_type' => $item->item_type == 'debit' ? 'dr' : 'cr',
                'unit_id' => @$voucher->unit_id??null,
                'cost_center_id' => @$item->const_center??null,
                'currency_id' => $item->currency_id,
                'conversion_rate' => $item->conversion_rate,
                'fc' => $item->item_type == 'debit' ? $item->dr_fc : $item->cr_fc,
                'trn_amount' => $item->item_type == 'debit' ? $item->dr_bd : $item->cr_bd,
                'particulars' => $item->narration ?? null,
                'voucher_id' => $voucher->id,
                'posted_by' => \Auth::id(),
                'factory_id' => $voucher->factory_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $entries = array_filter($entries, function ($item) {
            return $item['trn_amount'];
        });

        Journal::insert($entries);
    }

    private function journalPosting(Voucher $voucher)
    {
        if ($voucher->type_id == Voucher::DEBIT_VOUCHER) {
            $this->debitVoucherPosting($voucher);
        } elseif ($voucher->type_id == Voucher::CREDIT_VOUCHER) {
            $this->creditVoucherPosting($voucher);
        } elseif ($voucher->type_id == Voucher::JOURNAL_VOUCHER) {
            $this->journalVoucherPosting($voucher);
        } elseif ($voucher->type_id == Voucher::CONTRA_VOUCHER) {
            $this->contraVoucherPosting($voucher);
        }
    }

    private function journalVoucherPosting(Voucher $voucher)
    {
        $entries = [];
        $now = Carbon::now();

        foreach ($voucher->details->items as $item) {
            $entries[] = [
                'trn_date' => $voucher->trn_date,
                'account_id' => $item->account_id,
                'trn_type' => $item->debit == 0 ? 'cr' : 'dr',
                'unit_id' => $item->unit_id??null,
                'cost_center_id' => $item->const_center,
                'currency_id' => $item->currency,
                'conversion_rate' => $item->conversion_rate,
                'fc' => $item->dr_fc == 0 ? $item->cr_fc : $item->dr_fc,
                'trn_amount' => $item->debit == 0 ? $item->credit : $item->debit,
                'particulars' => $item->particulars ?? $item->narration ?? null,
                'voucher_id' => $voucher->id,
                'posted_by' => \Auth::id(),
                'factory_id' => $voucher->factory_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $entries = array_filter($entries, function ($item) {
            return $item['trn_amount'];
        });

        Journal::insert($entries);
    }

    private function debitVoucherPosting(Voucher $voucher)
    {
        $entries = [];
        $now = Carbon::now();

        foreach ($voucher->details->items as $item) {
            $entries[] = [
                'trn_date' => $voucher->trn_date,
                'account_id' => $item->account_id,
                'trn_type' => 'dr',
                'unit_id' => $item->unit_id??null,
                'cost_center_id' => $item->const_center,
                'currency_id' => $item->currency,
                'conversion_rate' => $item->conversion_rate,
                'fc' => $item->dr_fc,
                'trn_amount' => $item->debit,
                'particulars' => $item->particulars ?? $item->narration ?? null,
                'voucher_id' => $voucher->id,
                'posted_by' => \Auth::id(),
                'factory_id' => $voucher->factory_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $entries[] = [
            'trn_date' => $voucher->trn_date,
            'account_id' => $voucher->details->credit_account_id,
            'trn_type' => 'cr',
            'unit_id' => null,
            'cost_center_id' => null,
            'currency_id' => null,
            'conversion_rate' => null,
            'fc' => $voucher->details->total_credit_fc ?? 0,
            'trn_amount' => $voucher->details->total_credit,
            'particulars' => $voucher->details->general_particulars ?? null,
            'voucher_id' => $voucher->id,
            'posted_by' => \Auth::id(),
            'factory_id' => $voucher->factory_id,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $entries = array_filter($entries, function ($item) {
            return $item['trn_amount'];
        });

        Journal::insert($entries);
    }

    private function creditVoucherPosting(Voucher $voucher)
    {
        $entries = [];
        $now = Carbon::now();

        foreach ($voucher->details->items as $item) {
            $entries[] = [
                'trn_date' => $voucher->trn_date,
                'account_id' => $item->account_id,
                'trn_type' => 'cr',
                'unit_id' => $item->unit_id??null,
                'cost_center_id' => $item->const_center,
                'currency_id' => $item->currency,
                'conversion_rate' => $item->conversion_rate,
                'fc' => $item->cr_fc,
                'trn_amount' => $item->credit,
                'particulars' => $item->particulars ?? $item->narration ?? null,
                'voucher_id' => $voucher->id,
                'posted_by' => \Auth::id(),
                'factory_id' => $voucher->factory_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $entries[] = [
            'trn_date' => $voucher->trn_date,
            'account_id' => $voucher->details->debit_account_id,
            'trn_type' => 'dr',
            'unit_id' => null,
            'cost_center_id' => null,
            'currency_id' => null,
            'conversion_rate' => null,
            'fc' => $voucher->details->total_debit_fc ?? 0,
            'trn_amount' => $voucher->details->total_debit,
            'particulars' => $voucher->details->general_particulars ?? null,
            'voucher_id' => $voucher->id,
            'posted_by' => \Auth::id(),
            'factory_id' => $voucher->factory_id,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $entries = array_filter($entries, function ($item) {
            return $item['trn_amount'];
        });

        Journal::insert($entries);
    }

    private function contraVoucherPosting(Voucher $voucher)
    {
        $entries = [];
        $now = Carbon::now();

        foreach ($voucher->details->items as $item) {
            $entries[] = [
                'trn_date' => $voucher->trn_date,
                'account_id' => $item->account_id,
                'trn_type' => $item->debit == 0 ? 'cr' : 'dr',
                'unit_id' => $item->unit_id??null,
                'cost_center_id' => $item->const_center,
                'currency_id' => $item->currency,
                'conversion_rate' => $item->conversion_rate,
                'fc' => $item->dr_fc == 0 ? $item->cr_fc : $item->dr_fc,
                'trn_amount' => $item->debit == 0 ? $item->credit : $item->debit,
                'particulars' => $item->particulars ?? $item->narration ?? null,
                'voucher_id' => $voucher->id,
                'posted_by' => \Auth::id(),
                'factory_id' => $voucher->factory_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $entries = array_filter($entries, function ($item) {
            return $item['trn_amount'];
        });

        Journal::insert($entries);
    }

    public function print($id)
    {
        $voucher = Voucher::query()->with('company')->findOrFail($id);
        $comment = $voucher->comments()->orderBy('id', 'desc')->first();
        $voucherType = collect(VoucherService::getTypeList())->where('type_id', $voucher->type_id)->first();
        $signature = ReportSignatureService::getSignatures("FINANCE VOUCHER REPORT");
        $dateTime = Carbon::make($voucher->created_at)->toFormattedDateString();
        return view('finance::print.voucher_details_print', [
            'voucher' => $voucher,
            'comment' => $comment,
            'voucher_name' => $voucherType['name'],
            'signature' => $signature,
            'date_time' => $dateTime,
            'voucherStatuses' => Voucher::$statuses,
        ]);

        // $voucher = Voucher::findOrFail($id);
        // $voucher_name = str_replace(' ', '_', strtolower($voucher->type));

        // return view("finance::print.{$voucher_name}", [
        //     'voucher' => $voucher
        // ]);
    }

    public function getUnits($companyId): JsonResponse
    {
        $units = $this->units($companyId);

        return response()->json($units, Response::HTTP_OK);
    }

    public function getDepartments($companyId, $unitId): JsonResponse
    {
        $departments = AcDepartment::query()->where('ac_company_id', $companyId)
            ->where('ac_unit_id', $unitId)
            ->get();

        return response()->json($departments, Response::HTTP_OK);
    }

    public function units($companyId)
    {
        return AcUnit::query()->where('ac_company_id', $companyId)->get()->map(function ($unit) {
            return [
                'id' => $unit->id,
                'text' => $unit->unit,
            ];
        });
    }
}
