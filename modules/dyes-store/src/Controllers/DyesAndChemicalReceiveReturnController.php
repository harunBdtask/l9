<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Actions\MakeTransactionAction;
use SkylarkSoft\GoRMG\DyesStore\Models\DsChemicalReceiveReturn;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsReceiveReturnRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DyesAndChemicalReceiveReturnController extends Controller
{
    public function index()
    {
        $receiveReturn = DsChemicalReceiveReturn::query()
            ->with('supplier')
            ->orderBy('id', 'desc')
            ->paginate();
        return view('dyes-store::pages.dyes_chemicals_receive_return.dyes_chemical_receive_return_view', [
            'receiveReturn' => $receiveReturn
        ]);
    }

    public function create()
    {
        return view('dyes-store::forms.dyes_receive_return_form');
    }

    public function store(
        DsReceiveReturnRequest  $request,
        DsChemicalReceiveReturn $receiveReturn
    ): JsonResponse {
        try {
            $receiveReturn->fill($request->all())->save();
            return response()->json($receiveReturn, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param DsChemicalReceiveReturn $receiveReturn
     * @return JsonResponse
     */
    public function edit(DsChemicalReceiveReturn $receiveReturn): JsonResponse
    {
        try {
            return response()->json([
                'data' => $receiveReturn,
                'message' => 'Data Edited successfully',
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DsReceiveReturnRequest $request
     * @param DsChemicalReceiveReturn $receiveReturn
     * @return JsonResponse
     */
    public function update(
        DsReceiveReturnRequest  $request,
        DsChemicalReceiveReturn $receiveReturn
    ): JsonResponse {
        try {
            $receiveReturn->fill($request->all())->save();
            return response()->json($receiveReturn, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete(DsChemicalReceiveReturn $receiveReturn): RedirectResponse
    {
        try {
            $receiveReturn->delete();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    /**
     * @param DsChemicalReceiveReturn $receiveReturn
     * @param MakeTransactionAction $action
     * @return RedirectResponse
     * @throws Throwable
     */
    public function makeTransaction(
        DsChemicalReceiveReturn $receiveReturn,
        MakeTransactionAction   $action
    ): RedirectResponse {
        try {
            DB::beginTransaction();
            $action->transactionDetail($receiveReturn);
            $receiveReturn->update(['readonly' => 0]);
            DB::commit();
            Session::flash('alert-success', 'Transaction Update Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
