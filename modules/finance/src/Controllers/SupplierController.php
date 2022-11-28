<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use SkylarkSoft\GoRMG\Finance\Models\AcSupplierItem;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\AcSupplier;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use SkylarkSoft\GoRMG\Finance\Requests\AcSupplierRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;
use SkylarkSoft\GoRMG\Finance\Services\AccountCode\AccountCodeStrategy;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = AcSupplier::query()->orderByDesc('id')->paginate();

        return view('finance::pages.suppliers', compact('suppliers'));
    }

    public function create()
    {
        return view('finance::forms.supplier');
    }

    /**
     * @throws Throwable
     */
    public function store(AcSupplierRequest $request, AcSupplier $supplier): JsonResponse
    {
        try {
            DB::beginTransaction();
            $controlAccount = Account::query()->findOrFail($request->get('control_account_id'));

            if ($request->get('group_company') === 1) {
                $ledgerAccount = Account::query()->findOrFail($request->get('ledger_account_id'));

                $subLedgerAccountCode = (new AccountCodeStrategy())->setStrategy(Account::SUB_LEDGER)
                    ->setType($ledgerAccount->type_id)
                    ->setParentId($ledgerAccount->accountInfo->parent_account_id)
                    ->setGroupId($ledgerAccount->accountInfo->group_account_id)
                    ->setControlId($ledgerAccount->accountInfo->control_account_id)
                    ->setLedgerId($request->get('ledger_account_id'))
                    ->generate();

                $account = Account::query()->create([
                    'name' => $request->get('sub_ledger_account_name'),
                    'code' => $subLedgerAccountCode,
                    'type_id' => $ledgerAccount->type_id,
                    'account_type' => Account::SUB_LEDGER,
                ]);

                $accountInfo = new AccountInfo();
                $accountInfo->accounts_id = $account->id;
                $accountInfo->parent_account_id = $ledgerAccount->accountInfo->parent_account_id;
                $accountInfo->group_account_id = $ledgerAccount->accountInfo->group_account_id;
                $accountInfo->control_account_id = $ledgerAccount->accountInfo->control_account_id;
                $accountInfo->ledger_account_id = $request->get('ledger_account_id');
                $accountInfo->save();

                $request['sub_ledger_account_id'] = $account->id;
            } else if ($request->get('group_company') === 2) {
                $ledgerAccountCode = (new AccountCodeStrategy())->setStrategy(Account::LEDGER)
                    ->setType($controlAccount->type_id)
                    ->setParentId($controlAccount->accountInfo->parent_account_id)
                    ->setGroupId($controlAccount->accountInfo->group_account_id)
                    ->setControlId($request->get('control_account_id'))
                    ->generate();

                $account = Account::query()->create([
                    'name' => $request->get('ledger_account_name'),
                    'code' => $ledgerAccountCode,
                    'type_id' => $controlAccount->type_id,
                    'account_type' => Account::LEDGER,
                ]);

                $accountInfo = new AccountInfo();
                $accountInfo->accounts_id = $account->id;
                $accountInfo->parent_account_id = $controlAccount->accountInfo->parent_account_id;
                $accountInfo->group_account_id = $controlAccount->accountInfo->group_account_id;
                $accountInfo->control_account_id = $request->get('control_account_id');
                $accountInfo->save();

                $request['ledger_account_id'] = $account->id;
            }

            if ($request->get('attachment')) {
                $request['attachment'] = FileUploadRemoveService::fileUpload(
                    'finance',
                    $request->get('attachment'),
                    'file'
                );
            }

            $supplier->fill($request->all())->save();
            $supplier->taxVatInfo()->create($request->get('tax_vat_info'));
            $supplier->payment()->create($request->get('payment'));
            $supplier->items()->createMany($request->get('items'));
            DB::commit();

            return response()->json($supplier, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(AcSupplier $supplier): array
    {
        return [
            'id' => $supplier->id,
            'supplier_no' => $supplier->supplier_no,
            'control_account_id' => $supplier->control_account_id,
            'group_company' => $supplier->group_company,
            'ledger_account_id' => $supplier->ledger_account_id,
            'name' => $supplier->name,
            'ledger_account_name' => $supplier->ledger_account_name,
            'sub_ledger_account_name' => $supplier->sub_ledger_account_name,
            'head_address' => $supplier->head_address,
            'branch_address' => $supplier->branch_address,
            'contract_information' => $supplier->contract_information,
            'note' => $supplier->note,
            'attachment' => $supplier->attachment,
            'tax_vat_info' => $supplier->taxVatInfo,
            'payment' => $supplier->payment,
            'items' => $supplier->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'account_supplier_id' => $item->account_supplier_id,
                    'item_group_id' => $item->item_group_id,
                    'item_group_name' => $item->itemDetail->item_group,
                    'item_category' => $item->itemDetail->item->item_name,
                    'uom_name' => $item->itemDetail->orderUOM->unit_of_measurement,
                    'price_per_unit' => $item->price_per_unit,
                ];
            })
        ];
    }

    /**
     * @throws Throwable
     */
    public function update(AcSupplierRequest $request, AcSupplier $supplier): JsonResponse
    {
        try {
            DB::beginTransaction();
            if ($request->get('attachment')) {
                if (isset($supplier->attachment)) {
                    FileUploadRemoveService::removeFile($supplier->attachment);
                }
                $request['attachment'] = FileUploadRemoveService::fileUpload(
                    'finance',
                    $request->get('attachment'),
                    'file'
                );
            }
            $supplier->fill($request->all())->save();

            $supplier->taxVatInfo()->updateOrCreate(
                ['id' => $request->get('tax_vat_info')['id']],
                $request->get('tax_vat_info')
            );

            $supplier->payment()->updateOrCreate(
                ['id' => $request->get('payment')['id']],
                $request->get('payment')
            );

            foreach ($request->get('items') as $itemGroup) {
                $supplier->items()->updateOrCreate(
                    ['id' => $itemGroup['id']],
                    $itemGroup
                );
            }
            DB::commit();

            return response()->json($supplier, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy(AcSupplier $supplier): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $supplier->delete();
            $supplier->taxVatInfo()->delete();
            $supplier->payment()->delete();
            $supplier->items()->delete();
            DB::commit();

            Session::flash('success', 'Data Deleted Successfully!!');
        } catch (Throwable $exception) {
            Session::flash('error', "Something went wrong! {$exception->getMessage()}");
        }

        return back();
    }

    public function destroyItem(AcSupplierItem $item): JsonResponse
    {
        try {
            $item->delete();

            return response()->json(['message' => 'Item delete successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removeFile(AcSupplier $supplier): JsonResponse
    {
        try {
            if (isset($supplier->attachment)) {
                FileUploadRemoveService::removeFile($supplier->attachment);
            }
            $supplier->update(['attachment' => null]);

            return response()->json(['message' => 'File removed successfully', Response::HTTP_CREATED]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
