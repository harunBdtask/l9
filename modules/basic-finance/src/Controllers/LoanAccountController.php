<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use \App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\BasicFinance\Models\LoanAccount;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Requests\LoanAccountRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LoanAccountController extends Controller
{
    public function create()
    {
        return view('basic-finance::pages.bank-management.loan-management.loan-create-update');
    }

    public function index(Request $request)
    {
        $search = $request->get('search') ?? null;
        $loanAccounts = LoanAccount::query()
                ->with('factory', 'project', 'unit', 'bank', 'controlAccount')
                ->where('loan_account_number','LIKE','%'.$search.'%')
                ->orWhereDate('loan_creation_date',$search)
                ->orWhereDate('expiry_date',$search)
                ->orWhereHas('project', function ($query) use ($search) {
                    $query->where('project','LIKE','%'.$search.'%');
                })
                ->orWhereHas('unit', function ($query) use ($search) {
                    $query->where('unit','LIKE','%'.$search.'%');
                })
                ->orWhereHas('bank', function ($query) use ($search) {
                    $query->where('short_name','LIKE','%'.$search.'%');
                })
                ->latest()->orderByDesc('id')->paginate() ?? [];
        return view('basic-finance::pages.bank-management.loan-management.index', compact('loanAccounts' , 'search'));
    }

    public function store(LoanAccountRequest $request): JsonResponse
    {
        $data = $request;
        $loanAccount = LoanAccount::create($data->toArray());
        return response()->json($loanAccount);
    }

    public function show(LoanAccount $loanAccount): JsonResponse
    {
        return response()->json($loanAccount);
    }

    public function update(LoanAccount $loanAccount, LoanAccountRequest $request): JsonResponse
    {
        $data = $request;
        $loanAccount->update($data->toArray());

        return response()->json($data);
    }

    public function destroy(LoanAccount $loanAccount): RedirectResponse
    {
        try {
            $loanAccount->delete();
            Session::flash('success', 'Data Deleted Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', $exception->getMessage());
        }
        return redirect()->back();
    }
}

