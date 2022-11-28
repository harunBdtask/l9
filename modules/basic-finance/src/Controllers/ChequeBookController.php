<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use DB;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\BankAccount;
use SkylarkSoft\GoRMG\BasicFinance\Models\Journal;
use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;
use SkylarkSoft\GoRMG\BasicFinance\Models\VoucherComment;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\BasicFinance\Models\ChequeBook;
use SkylarkSoft\GoRMG\BasicFinance\Models\ChequeBookDetail;
use SkylarkSoft\GoRMG\BasicFinance\Requests\ChequeBookRequest;

class ChequeBookController extends Controller
{
    public function index(Request $request)
    {
        $chequeBooks = ChequeBook::query()->with('details','bankAccount.factory')->search($request->get('search'))->orderByDesc('id')->paginate();

        return view('basic-finance::pages.cheque_books', [
            'cheque_books' => $chequeBooks,
        ]);
    }

    public function create()
    {
        return view("basic-finance::forms.cheque_book_entry");
    }

    /**
     * @throws Throwable
     */
    public function store(ChequeBookRequest $request, ChequeBook $chequeBook): JsonResponse
    {
        try {
            DB::beginTransaction();
            $chequeBook->fill($request->all())->save();
            $chequeBook->details()->createMany($request->get('details'));
            DB::commit();

            return response()->json(['message' => 'Data stored successfully'], Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, ChequeBook $chequeBook): array
    {
        $search = $request->get('search');
        $chequeBook->load('details');

        return [
            'id' => $chequeBook->id,
            'bank_id' => $chequeBook->bank_id,
            'bank_account_id' => $chequeBook->bank_account_id,
            'cheque_book_no' => $chequeBook->cheque_book_no,
            'cheque_no_from' => $chequeBook->cheque_no_from,
            'cheque_no_to' => $chequeBook->cheque_no_to,
            'total_page' => $chequeBook->total_page,
            'details' => collect($chequeBook->details)->when($search != 0, function ($collection) use ($search) {
                return $collection->where('status', $search);
            })->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'cheque_book_id' => $detail->cheque_book_id,
                    'cheque_no' => $detail->cheque_no,
                    'paid_to' => $detail->paid_to,
                    'amount' => $detail->amount,
                    'cheque_date' => $detail->cheque_date,
                    'cheque_due_date' => $detail->cheque_due_date,
                    'status' => $detail->status,
                ];
            })
        ];
    }

    public function edit()
    {
        return view("basic-finance::forms.cheque_book_entry");
    }

    public function detailUpdate(Request $request, ChequeBookDetail $detail): JsonResponse
    {
        try {
            $detail->status = $detail->status == 1 ? ChequeBookDetail::IN_ACTIVE : ChequeBookDetail::ACTIVE;
            $detail->save();

            return response()->json($detail, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy(ChequeBook $chequeBook): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $chequeBook->details()->delete();
            $chequeBook->delete();
            DB::commit();
            Session::flash('success', 'Data Deleted Successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }

    public function view(ChequeBook $chequeBook)
    {
        $chequeBook->load('bankAccount.factory');
        return view('basic-finance::pages.cheque_book', ['cheque_book' => $chequeBook]);
    }

    public function clearFormView(Request $request)
    {
        $accountId = $request->get('account_id') ?? null;
        $fromDate = $request->get('from_date') ?? null;
        $toDate = $request->get('to_date') ?? null;
        $clearingDate = $request->get('clearing_date') ?? null;

        $accounts = BankAccount::query()->where('status', '1')->get();
        $chequeBookIds = $cheques = [];
        $balance= 0;
        if (!empty($accountId)) {
            $bankAccount = BankAccount::query()->findOrFail($accountId);
            $journals = Journal::query()->where('account_id', $bankAccount->account_id)
                ->get()->groupBy('trn_type');
            $totalCredit = isset($journals['cr']) ? collect($journals['cr'])->sum('trn_amount') : 0;
            $totalDebit = isset($journals['dr']) ? collect($journals['dr'])->sum('trn_amount') : 0;
            $balance = $totalDebit - $totalCredit;
            $chequeBookIds = ChequeBook::query()->where('bank_account_id', $accountId)->whereNull('deleted_at')->pluck('id')->toArray();
            $cheques = ChequeBookDetail::query()->with('voucher')
                ->whereIn('cheque_book_id', $chequeBookIds)
                ->whereNull('deleted_at')
                ->when($fromDate, function ($query) use ($fromDate) {
                    $query->whereDate('cheque_date', '>=', $fromDate);
                })
                ->when($fromDate, function ($query) use ($toDate) {
                    $query->whereDate('cheque_date', '<=', $toDate);
                })
                ->when($clearingDate, function ($query) use ($clearingDate) {
                    $query->whereDate('clearing_date', $clearingDate);
                })
                ->get()->paginate(15);

        }
        return view('basic-finance::pages.bank-management.cheque-clear.view', [
            'accounts' => $accounts,
            'accountId' => $accountId,
            'cheques' => $cheques,
            'balance' => $balance,
        ]);
    }

    public function multipleChequeClearing(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->all() as $id) {
                $chequeDetails = ChequeBookDetail::query()->findOrFail($id);
                $chequeDetails->update([
                    'status' => 6,
                    'clearing_date' => now(),
                    'cleared_by' => \Auth::id(),
                ]);
            }
            DB::commit();

            return response()->json('Cleared Successfully !', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function clearChequeList(Request $request)
    {
        $clearDate = $request->get('clear_date') ?? null;

        $accounts = BankAccount::query()->where('status', '1')->pluck('id')->toArray();
        $chequeBookIds = ChequeBook::query()->whereIn('bank_account_id', $accounts)->whereNull('deleted_at')->pluck('id')->toArray();
        $cheques = ChequeBookDetail::query()->with('chequeBook', 'chequeBook.bankAccount', 'chequeBook.bankAccount.factory' , 'chequeBook.bank')
            ->whereIn('cheque_book_id', $chequeBookIds)
            ->where('status', '6')
            ->whereNull('deleted_at')
            ->when($clearDate, function ($query) use ($clearDate) {
                $query->whereDate('clearing_date', $clearDate);
            })
            ->get()->paginate(15);
        return view('basic-finance::pages.bank-management.cheque-clear.clear-cheque-list', compact('cheques'));
    }

    public function unclearChequeList(Request $request)
    {
        $dueDate = $request->get('due_date') ?? null;

        $accounts = BankAccount::query()->where('status', '1')->pluck('id')->toArray();
        $chequeBookIds = ChequeBook::query()->whereIn('bank_account_id', $accounts)->whereNull('deleted_at')->pluck('id')->toArray();
        $cheques = ChequeBookDetail::query()->with('chequeBook', 'chequeBook.bankAccount', 'chequeBook.bankAccount.factory' , 'chequeBook.bank')
            ->whereIn('cheque_book_id', $chequeBookIds)
            ->whereNotIn('status', ['6','1','2'])
            ->whereNull('deleted_at')
            ->when($dueDate, function ($query) use ($dueDate) {
                $query->whereDate('cheque_due_date', $dueDate);
            })
            ->get()->paginate(15);
        return view('basic-finance::pages.bank-management.cheque-clear.unclear-cheque-list', compact('cheques'));
    }
}
