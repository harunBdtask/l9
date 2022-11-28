<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use PDF;
use Auth;
use Exception;
use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\Journal;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\BasicFinance\Models\CostCenter;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;
use SkylarkSoft\GoRMG\BasicFinance\Models\ReceiveBank;
use SkylarkSoft\GoRMG\BasicFinance\Models\ReceiveCheque;
use SkylarkSoft\GoRMG\BasicFinance\Models\VoucherComment;
use SkylarkSoft\GoRMG\BasicFinance\Models\ChequeBookDetail;
use SkylarkSoft\GoRMG\BasicFinance\Requests\VouchersRequest;
use SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService;
use SkylarkSoft\GoRMG\SystemSettings\Models\BfVariableSetting;
use SkylarkSoft\GoRMG\BasicFinance\Services\BasicFinanceService;
use SkylarkSoft\GoRMG\BasicFinance\Actions\SyncAccountingRealizationAction;

class VouchersController extends Controller
{
    public function index(Request $request)
    {
        $orderType = $request->get('order_type') ?? 'DESC';
        $sortBy = $request->get('sort_by') ?? 1;
        $variable = BfVariableSetting::first();

        if ((getRole() === 'super-admin') || (getRole() === 'admin') || (!empty($variable) && in_array(Auth::id(),$variable->accounting_users))) {
            $vouchers = Voucher::query()->when($request->get('voucher_type'), function (Builder $q) use ($request) {
                return $q->where('type_id', $request->get('voucher_type'));
            })->when($request->get('reference_no'), function (Builder $q) use ($request) {
                return $q->where(function (Builder $q) use ($request) {
                    return $q->where('reference_no', 'LIKE', '%' . $request->get('reference_no') . '%')
                        ->orWhere('bill_no', 'LIKE', '%' . $request->get('reference_no') . '%');
                });
            })->when($request->get('start_date'), function (Builder $q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->get('start_date'));
            })->when($request->get('end_date'), function (Builder $q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->get('end_date'));
            })->when($request->get('voucher_no'), function (Builder $q) use ($request) {
                return $q->where('voucher_no', 'LIKE', '%' . $request->get('voucher_no') . '%');
            })->orderBy(BasicFinanceService::$sortByItems[$sortBy], $orderType)->paginate();
        } else {
            $vouchers = Voucher::query()->where('created_by', Auth::id())
                ->when($request->get('voucher_type'), function (Builder $q) use ($request) {
                    return $q->where('type_id', $request->get('voucher_type'));
                })->when($request->get('reference_no'), function (Builder $q) use ($request) {
                    return $q->where(function (Builder $q) use ($request) {
                        return $q->where('reference_no', 'LIKE', '%' . $request->get('reference_no') . '%')
                            ->orWhere('bill_no', 'LIKE', '%' . $request->get('reference_no') . '%');
                    });
                })->when($request->get('start_date'), function (Builder $q) use ($request) {
                    return $q->whereDate('created_at', '>=', $request->get('start_date'));
                })->when($request->get('end_date'), function (Builder $q) use ($request) {
                    return $q->whereDate('created_at', '<=', $request->get('end_date'));
                })->when($request->get('voucher_no'), function (Builder $q) use ($request) {
                    return $q->where('voucher_no', 'LIKE', '%' . $request->get('voucher_no') . '%');
                })->orderBy(BasicFinanceService::$sortByItems[$sortBy], $orderType)->paginate();
        }

        return view('basic-finance::pages.vouchers', [
            'voucherTypes' => Voucher::$types,
            'vouchers' => $vouchers,
            'request' => $request
        ]);
    }

    public function voucherNo(Request $request)
    {
        $voucherNo = Voucher::generateVoucherNo($request->get('voucher_type'));
        return response()->json(collect($voucherNo)->first(), Response::HTTP_OK);
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
        $accounts = Account::query()->has('parentAc')
            ->with('parentAc')
            ->where('is_transactional', 1)
            ->where('is_active', 1)
            ->get();

        // Today Vouchers
         $vouchers = Voucher::query()
            ->where('created_by', Auth::id())
            ->whereDate('trn_date', date('Y-m-d'))
            ->orderBy('id','desc')
            ->get();
        $voucherTypeList = Voucher::$types;


        return view('basic-finance::forms.voucher', [
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
            'vouchers' => $vouchers,
            'voucherTypeList' => $voucherTypeList,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(VouchersRequest $request, Voucher $voucher, SyncAccountingRealizationAction $action)
    {
        // dd($request->all());
        DB::beginTransaction();
        $voucher->fill($request->all())->save();

        if ($request->input('account_realization_id')) {
            $action->syncRealization($request->input('account_realization_id'));
        }
        //        ChequeNoUpdateJob::dispatchSync($request);
        DB::commit();

        return url('basic-finance/vouchers/' . $voucher->id);
    }

    public function storeAndCopy(VouchersRequest $request, Voucher $voucher)
    {
        DB::beginTransaction();
        $voucher->fill($request->all())->save();
        DB::commit();

        return url('basic-finance/vouchers/' . $voucher->id);
    }

    public function show($id)
    {
        $voucher = Voucher::query()->with('company', 'project', 'unit', 'bank.account', 'receiveBank', 'createdBy.AccDepartment')->findOrFail($id);
        $comments = $voucher->comments()->orderBy('status_id', 'desc')->get();
        $comment = $comments->first();

        $checkedBy  = VoucherComment::with('commenter.AccDepartment')->where('voucher_id', $voucher->id)
            ->where('status_id', Voucher::CHECKED)->first();
        $approvedBy  = VoucherComment::with('commenter.AccDepartment')->where('voucher_id', $voucher->id)
            ->where('status_id', Voucher::POSTED)->first();


        $department = $notify_users = [];
        $bf_variable  = BfVariableSetting::first();
        if(!empty($bf_variable) && ($bf_variable->departmental_approval==1) && isset($voucher->details->items))
        {
            //Department Head
            if($voucher->status_id == Voucher::CREATED){
                $department_id = collect($voucher->details->items)->first()->department_id;
                $department = Department::find($department_id);
                $notify_users = array_filter([$department->notify_to, $department->alternative_notify_to]);
            }

            //Accounting Head
            if($voucher->status_id == Voucher::CHECKED){
                $department = Department::where('is_accounting', 1)->first();
                if($department){
                    $notify_users = array_filter([$department->notify_to, $department->alternative_notify_to]);
                }
            }
        }

        if ($voucher->type_id == Voucher::DEBIT_VOUCHER) {
            return view('basic-finance::pages.debit_voucher', [
                'voucher' => $voucher,
                'comment' => $comment,
                'bf_variable' => $bf_variable,
                'department' => $department,
                'notify_users' => $notify_users,
                'approvedBy' => $approvedBy,
                'checkedBy' => $checkedBy

            ]);
        } elseif ($voucher->type_id == Voucher::CREDIT_VOUCHER) {
            return view('basic-finance::pages.credit_voucher', [
                'voucher' => $voucher,
                'comment' => $comment,
                'bf_variable' => $bf_variable,
                'department' => $department,
                'notify_users' => $notify_users,
                'approvedBy' => $approvedBy,
                'checkedBy' => $checkedBy
            ]);
        }

        return view('basic-finance::pages.journal_voucher', [
            'voucher' => $voucher,
            'comment' => $comment,
            'bf_variable' => $bf_variable,
            'department' => $department,
            'notify_users' => $notify_users,
            'approvedBy' => $approvedBy,
            'checkedBy' => $checkedBy
        ]);
    }

    public function edit($id)
    {
        $voucher = Voucher::query()->findOrFail($id);
        $voucherType = '';
        $currencies = collect(CurrencyService::currencies())->pluck('name', 'id');
        $companies = Factory::query()->pluck('factory_name as name', 'id')->all();
        $departments = Department::all();
        $costCenters = CostCenter::all();
        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
            $projects = Project::query()->where('factory_id', $voucher->factory_id)
                ->pluck('project', 'id');
            $units = Unit::query()->where([
                'factory_id' => $voucher->factory_id,
                'bf_project_id' => $voucher->project_id,
            ])->pluck('unit', 'id');
        } else {
            $userId = (string)(\Auth::id());
            $projects = Project::query()->where('factory_id', $voucher->factory_id)
                ->whereJsonContains('user_ids', [$userId])
                ->pluck('project', 'id');
            $units = Unit::query()->where([
                'factory_id' => $voucher->factory_id,
                'bf_project_id' => $voucher->project_id,
            ])->whereJsonContains('user_ids', [$userId])
                ->pluck('unit', 'id');
        }
        $accounts = Account::query()->has('parentAc')
            ->where('is_transactional', 1)
            ->where('is_active', 1)
            ->get();
        $selectedChequeList = [];
        if($voucher->cheque_no){

            $selectedChequeNo = ChequeBookDetail::query()->with('chequeBook')->where('id', $voucher->cheque_no)->first();
            $selectedChequeList = collect(array([
                'id' => $selectedChequeNo->id,
                'text' => $selectedChequeNo->chequeBook->cheque_book_no.'-'.$selectedChequeNo->cheque_no
            ]))->pluck('text','id');
        }
        $chequeName = ChequeBookDetail::query()->select('cheque_no')->where('id', $voucher->cheque_no)->where('status', '!=', 1)->get();
        $receiveBanks = ReceiveBank::query()->pluck('name', 'id');

        // Today Vouchers
        $vouchers = Voucher::query()
        ->where('created_by', Auth::id())
        ->whereDate('trn_date', date('Y-m-d'))
        ->orderBy('id','desc')
        ->get();
        $voucherTypeList = Voucher::$types;

        return view('basic-finance::forms.voucher', [
            'departments' => $departments,
            'voucher' => $voucher,
            'voucherType' => $voucherType,
            'accounts' => $accounts,
            'cheque_name' => collect($chequeName)->pluck('cheque_no'),
            'selectedChequeList' => $selectedChequeList,
            'companies' => $companies,
            'currencies' => $currencies,
            'cost_centers' => $costCenters,
            'projects' => $projects,
            'units' => $units,
            'receiveBanks' => $receiveBanks,
            'voucherNo' => $voucher->voucher_no,
            'vouchers' => $vouchers,
            'voucherTypeList' => $voucherTypeList,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update($id, VouchersRequest $request)
    {
        DB::beginTransaction();
        $voucher = Voucher::query()->findOrFail($id);
        $voucher->fill($request->all())->save();
        //        ChequeNoUpdateJob::dispatchSync($request);
        DB::commit();

        if ($voucher->status_id == Voucher::AMEND) {
            VoucherComment::create([
                'voucher_id' => $voucher->id,
                'status_id' => Voucher::CREATED,
                'comment' => 'Amendments incorporated.',
                'commented_by' => Auth::id()
            ]);
        }
        $voucher->status_id = Voucher::CREATED;
        $voucher->created_by = Auth::id();
        $voucher->updated_by = Auth::id();
        $voucher->save();

        return url('basic-finance/vouchers/' . $voucher->id);
    }

    public function approval($id, Request $request)
    {
        $voucher = \DB::transaction(function () use ($id, $request) {
            $voucher = Voucher::findOrFail($id);
            $voucher->status_id = $request->get('status_id');
            $voucher->save();

            switch ($voucher->status_id) {
                case Voucher::CHECKED:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => 'Every things seems good.',
                        'commented_by' => Auth::id()
                    ]);
                    break;

                case Voucher::AMEND:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => $request->message,
                        'commented_by' => Auth::id()
                    ]);
                    break;

                case Voucher::AUTHORIZED:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => 'Authorized for transaction.',
                        'commented_by' => Auth::id()
                    ]);
                    break;

                case Voucher::POSTED:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => 'Voucher has been posted.',
                        'commented_by' => Auth::id()
                    ]);

                    $voucher->cheque->update([
                        'status' => 3
                    ]);
                    $this->journalPosting($voucher);

                    break;

                case Voucher::CANCELED:
                    VoucherComment::create([
                        'voucher_id' => $id,
                        'status_id' => $voucher->status_id,
                        'comment' => $request->message,
                        'commented_by' => Auth::id()
                    ]);
                    break;
            }

            return $voucher;
        });
        //        return redirect('basic-finance/vouchers/' . $id);
        return back();
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
                'account_code' => $item->account_code,
                'project_id' => $voucher->project_id,
                'unit_id' => $voucher->unit_id,
                'voucher_no' => $voucher->voucher_no,
                'reference_no' => $voucher->reference_no,
                'trn_type' => $item->debit == 0 ? 'cr' : 'dr',
                'department_id' => $item->department_id,
                'cost_center_id' => $item->const_center,
                'currency_id' => $voucher->currency_id,
                'conversion_rate' => $item->conversion_rate,
                'fc' => $item->dr_fc == 0 ? $item->cr_fc : $item->dr_fc,
                'trn_amount' => $item->debit == 0 ? $item->credit : $item->debit,
                'particulars' => $item->particulars ?? $item->narration ?? null,
                'voucher_id' => $voucher->id,
                'posted_by' => Auth::id(),
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
                'account_code' => $item->account_code,
                'project_id' => $voucher->project_id,
                'unit_id' => $voucher->unit_id,
                'voucher_no' => $voucher->voucher_no,
                'reference_no' => $voucher->reference_no,
                'trn_type' => 'dr',
                'department_id' => $item->department_id,
                'cost_center_id' => $item->const_center,
                'currency_id' => $voucher->currency_id,
                'conversion_rate' => $item->conversion_rate,
                'fc' => $item->dr_fc,
                'trn_amount' => $item->debit,
                'particulars' => $item->particulars ?? $item->narration ?? null,
                'voucher_id' => $voucher->id,
                'posted_by' => Auth::id(),
                'factory_id' => $voucher->factory_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $entries[] = [
            'trn_date' => $voucher->trn_date,
            'account_id' => $voucher->details->credit_account,
            'account_code' => $voucher->details->credit_account_code,
            'project_id' => $voucher->project_id,
            'unit_id' => $voucher->unit_id,
            'voucher_no' => $voucher->voucher_no,
            'reference_no' => $voucher->reference_no,
            'trn_type' => 'cr',
            'department_id' => collect($voucher->details->items)->first()->department_id??'',
            'cost_center_id' => collect($voucher->details->items)->first()->const_center??'',
            'currency_id' => 1,
            'conversion_rate' => 1,
            'fc' => $voucher->details->total_credit_fc ?? 0,
            'trn_amount' => $voucher->details->total_credit,
            'particulars' => $voucher->details->general_particulars ?? null,
            'voucher_id' => $voucher->id,
            'posted_by' => Auth::id(),
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
                'account_code' => $item->account_code,
                'project_id' => $voucher->project_id,
                'unit_id' => $voucher->unit_id,
                'voucher_no' => $voucher->voucher_no,
                'reference_no' => $voucher->reference_no,
                'trn_type' => 'cr',
                'department_id' => $item->department_id,
                'cost_center_id' => $item->const_center,
                'currency_id' => $voucher->currency_id,
                'conversion_rate' => $item->conversion_rate,
                'fc' => $item->cr_fc,
                'trn_amount' => $item->credit,
                'particulars' => $item->particulars ?? $item->narration ?? null,
                'voucher_id' => $voucher->id,
                'posted_by' => Auth::id(),
                'factory_id' => $voucher->factory_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $entries[] = [
            'trn_date' => $voucher->trn_date,
            'account_id' => $voucher->details->debit_account,
            'account_code' => $voucher->details->debit_account_code,
            'project_id' => $voucher->project_id,
            'unit_id' => $voucher->unit_id,
            'voucher_no' => $voucher->voucher_no,
            'reference_no' => $voucher->reference_no,
            'trn_type' => 'dr',
            'department_id' => collect($voucher->details->items)->first()->department_id??'',
            'cost_center_id' => collect($voucher->details->items)->first()->const_center??'',
            'currency_id' => 1,
            'conversion_rate' => 1,
            'fc' => $voucher->details->total_debit_fc ?? 0,
            'trn_amount' => $voucher->details->total_debit,
            'particulars' => $voucher->details->general_particulars ?? null,
            'voucher_id' => $voucher->id,
            'posted_by' => Auth::id(),
            'factory_id' => $voucher->factory_id,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $entries = array_filter($entries, function ($item) {
            return $item['trn_amount'];
        });

        Journal::insert($entries);
        if ($voucher->paymode === 1 && ($voucher->receive_bank_id && $voucher->receive_cheque_no)) {
            ReceiveCheque::create([
                'voucher_no' => $voucher->voucher_no,
                'receive_bank_id' => $voucher->receive_bank_id,
                'cheque_no' => $voucher->receive_cheque_no,
                'cheque_due_date' => $voucher->cheque_due_date,
                'created_by' => Auth::id()
            ]);
        }
    }

    private function contraVoucherPosting(Voucher $voucher)
    {
        $entries = [];
        $now = Carbon::now();

        foreach ($voucher->details->items as $item) {
            $entries[] = [
                'trn_date' => $voucher->trn_date,
                'account_id' => $item->account_id,
                'account_code' => $item->account_code,
                'project_id' => $voucher->project_id,
                'unit_id' => $voucher->unit_id,
                'voucher_no' => $voucher->voucher_no,
                'reference_no' => $voucher->reference_no,
                'trn_type' => $item->debit == 0 ? 'cr' : 'dr',
                'department_id' => $item->department_id,
                'cost_center_id' => $item->const_center,
                'currency_id' => $voucher->currency_id,
                'conversion_rate' => $item->conversion_rate,
                'fc' => $item->dr_fc == 0 ? $item->cr_fc : $item->dr_fc,
                'trn_amount' => $item->debit == 0 ? $item->credit : $item->debit,
                'particulars' => $item->particulars ?? $item->narration ?? null,
                'voucher_id' => $voucher->id,
                'posted_by' => Auth::id(),
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
        $voucher = Voucher::query()->with('company', 'project', 'unit', 'bank.account', 'receiveBank', 'createdBy.AccDepartment', 'comments')->findOrFail($id);
        $comments = $voucher->comments()->orderBy('status_id', 'desc')->get();
        $voucher_name = str_replace(' ', '_', strtolower($voucher->type));

        $comment = $comments->first();
        $bf_variable  = BfVariableSetting::first();

        $approvedBy  = VoucherComment::with('commenter.AccDepartment')->where('voucher_id', $voucher->id)
            ->where('status_id', 3)->first();
        $checkedBy  = VoucherComment::with('commenter.AccDepartment')->where('voucher_id', $voucher->id)
            ->where('status_id', 1)->first();

        return view("basic-finance::print.{$voucher_name}", [
            'voucher' => $voucher,
            'comment' => $comment,
            'approvedBy' => $approvedBy,
            'checkedBy' => $checkedBy,
            'bf_variable' => $bf_variable,
        ]);
    }

    public function getUnits($companyId): JsonResponse
    {
        $units = $this->units($companyId);

        return response()->json($units, Response::HTTP_OK);
    }

    public function getDepartments($companyId, $unitId): JsonResponse
    {
        $departments = Department::query()->where('ac_company_id', $companyId)
            ->where('ac_unit_id', $unitId)
            ->get();

        return response()->json($departments, Response::HTTP_OK);
    }

    public function units($companyId)
    {
        return Unit::query()->where('ac_company_id', $companyId)->get()->map(function ($unit) {
            return [
                'id' => $unit->id,
                'text' => $unit->unit,
            ];
        });
    }

    /**
     * @throws Throwable
     */
    public function multipleJournalPosting(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $id) {
                $voucher = Voucher::query()->findOrFail($id);
                $voucher->update(['status_id' => Voucher::POSTED]);
                VoucherComment::create([
                    'voucher_id' => $id,
                    'status_id' => $voucher->status_id,
                    'comment' => 'Voucher has been posted.',
                    'commented_by' => Auth::id()
                ]);
                $this->journalPosting($voucher);
            }
            DB::commit();

            return response()->json('Posted successfully !', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
