<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Actions\IssueReturnTransactionAction;
use SkylarkSoft\GoRMG\DyesStore\Models\DsChemicalIssueReturn;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsIssueReturnRequest;
use Symfony\Component\HttpFoundation\Response;

class DyesAndChemicalIssueReturnController extends Controller
{
    public function index()
    {
        $issueReturn = DsChemicalIssueReturn::query()
            ->orderBy('id', 'desc')
            ->paginate();
        return view('dyes-store::pages.dyes_chemical_issue_return.dyes_chemical_issue_return', [
            'issueReturn' => $issueReturn
        ]);
    }


    public function create()
    {
        return view('dyes-store::forms.dyes_issue_return_form');
    }

    public function store(
        DsIssueReturnRequest  $request,
        DsChemicalIssueReturn $chemicalIssueReturn
    ): JsonResponse {
        try {
            $chemicalIssueReturn->fill($request->all())->save();
            return response()->json($chemicalIssueReturn, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function edit(DsChemicalIssueReturn $chemicalIssueReturn): JsonResponse
    {
        try {
            return response()->json([
                'data' => $chemicalIssueReturn,
                'message' => 'Data Edited successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(
        DsIssueReturnRequest  $request,
        DsChemicalIssueReturn $chemicalIssueReturn
    ): JsonResponse {
        try {
            $chemicalIssueReturn->fill($request->all())->save();
            return response()->json($chemicalIssueReturn, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(DsChemicalIssueReturn $chemicalIssueReturn): RedirectResponse
    {
        try {
            $chemicalIssueReturn->delete();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function makeTransaction(
        DsChemicalIssueReturn        $chemicalIssueReturn,
        IssueReturnTransactionAction $action
    ): RedirectResponse {
        try {
            DB::beginTransaction();
            $action->transactionDetail($chemicalIssueReturn);
            $chemicalIssueReturn->update(['readonly' => 0]);
            DB::commit();
            Session::flash('alert-success', 'Transaction Update Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

}
