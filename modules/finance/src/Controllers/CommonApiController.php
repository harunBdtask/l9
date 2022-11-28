<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Finance\Models\Unit;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\Project;
use SkylarkSoft\GoRMG\Finance\Models\Voucher;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Finance\Models\JobNumber;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use SkylarkSoft\GoRMG\Finance\Models\AccountType;
use SkylarkSoft\GoRMG\Finance\Models\AcDepartment;
use SkylarkSoft\GoRMG\SystemSettings\Models\Company;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Finance\Models\FundRequisition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\Finance\Models\ChequeBookDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\Finance\Models\CustomerBillEntry;
use SkylarkSoft\GoRMG\Finance\Models\SupplierBillEntry;
use SkylarkSoft\GoRMG\Finance\Services\CurrencyService;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class CommonApiController extends Controller
{
    public function fetchUnit(): JsonResponse
    {
        $units = Unit::query()->orderBy('unit')->get(['unit as text', 'id']);
        return response()->json($units);
    }

    public function  projectWiseUnit($companyId,$projectId): jsonResponse
    {
        try{

            if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
                $units = Unit::query()->where('factory_id', $companyId)->where('fi_project_id', $projectId)
                    ->get(['id', 'unit as text']);
            }else{
                $id = (string)(\Auth::id());
                $units = Unit::query()->where('factory_id', $companyId)->where('fi_project_id', $projectId)
                    ->whereJsonContains('user_ids', [$id])
                    ->get(['id', 'unit as text']);
            }
            return response()->json($units, Response::HTTP_OK);
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchDepartment($id): JsonResponse
    {
        $departments = AcDepartment::query()->orderBy('name')->where('ac_unit_id', $id)->get(['name as text', 'id']);
        return response()->json($departments);
    }

    public function getRequisitionNo(): JsonResponse
    {
        $id = FundRequisition::query()->max('id');
        $req_id = sprintf("RQ-%06d", $id + 1);
        return response()->json($req_id);
    }

    public function getAccountTypes(): JsonResponse
    {
        try {
            $accountTypes = AccountType::query()->get([
                'id', 'account_type as text', 'short_form',
            ]);

            return response()->json([
                'message' => 'Fetch account types successfully',
                'data' => $accountTypes,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getControlLedgers(): JsonResponse
    {
        try {
            $accounts = Account::query()
            ->where('account_type', Account::CONTROL)
            ->get([
                'id', 'name as text', 'code',
            ]);

            return response()->json([
                'message' => 'Fetch account types successfully',
                'data' => $accounts,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAccountTypeShortForm(int $accountTypeId): JsonResponse
    {
        try {
            $accountTypeShortForm = AccountType::query()->findOrFail($accountTypeId)['short_form'] ?? null;

            return response()->json([
                'message' => 'Fetch account type short form',
                'data' => $accountTypeShortForm,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getVoucherInfo($voucher_type): JsonResponse
    {
        try {

            $voucherNo = Voucher::generateVoucherNo($voucher_type);

            return response()->json([
                'message' => 'Voucher No generated successfully',
                'data' => $voucherNo,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLedgerAccounts($id): JsonResponse
    {
        try {

           $accounts = AccountInfo::query()
            ->where('control_account_id',$id)
            // ->has('ledgerAccount')
            ->with('controlLedgerAccount')
            ->get();

            return response()->json([
                'message' => 'Ledger info get successfully',
                'data' => $accounts,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getLedgerAccountsList($id): JsonResponse
    {

        $ledger_accounts = (new Account())->getLedgerAccountsByControlId($id);
        return response()->json($ledger_accounts);
    }

    public function updateChequeBookDetails($chequeId,$to,$amount,$trnDate,$dueDate ): JsonResponse
    {
        try {
            $affected = ChequeBookDetail::query()
                ->where('id', $chequeId)
                ->update([
                    'status' => 4,
                    'paid_to' => $to,
                    'amount' => $amount,
                    'cheque_date' => $trnDate,
                    'cheque_due_date' => $dueDate

                    ]);
            return response()->json($affected, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function fetchGroups(): JsonResponse
    {
        $items = Company::query()->get(['company_name as text', 'id']);
        return response()->json($items);
    }

    public function fetchCompany(): JsonResponse
    {
        $items = Factory::query()->get(['factory_name as text', 'id']);
        return response()->json($items);
    }

    public function fetchSuppliers(): JsonResponse
    {
        $items = Supplier::query()->whereNotNull('control_ledger_id')->get(['*', 'name as text']);
        return response()->json($items);
    }

    public function fetchCurrency(): JsonResponse
    {
        $items = collect(CurrencyService::currencies())->map(function($item){
            return [
                'id'=> $item['id'],
                'name'=> $item['name'],
                'text'=> $item['name'],
            ];
        });
        return response()->json($items);
    }
    public function fetchUom(): JsonResponse
    {
        $items = UnitOfMeasurement::query()->get(['unit_of_measurement as text', 'id']);
        return response()->json($items);
    }
    public function fetchPayModes(): JsonResponse
    {
        $items = collect(SupplierBillEntry::$payModes)->map(function($val, $key){
            return ['id' => $key, 'text' => $val];
        })->values();
        return response()->json($items);
    }

    public function fetchVatInfo(): JsonResponse
    {
        $items['vatTypes'] = collect(SupplierBillEntry::$vatTypes)->map(function($val, $key){
            return ['id' => $key, 'text' => $val];
        });
        $items['tdsTypes'] = collect(SupplierBillEntry::$tdsTypes)->map(function($val, $key){
            return ['id' => $key, 'text' => $val];
        });
        return response()->json($items);
    }
    public function fetchAllLedgerAccounts(): JsonResponse
    {

        $accounts = Account::query()
            ->has('accountInfo.parentAccount')
            ->has('accountInfo.groupAccount')
            ->has('accountInfo.controlAccount')
            ->get(['id','name as text','code']);

        return response()->json($accounts);
    }

    public function fetchCustomerBillNumbers(Request $request): JsonResponse
    {
        try {
            $numbers = CustomerBillEntry::query()
            ->when($request->get('group_id'), function($q) use($request) {
                return $q->where('group_id', $request->get('group_id'));
            })
            ->when($request->get('company_id'), function($q) use($request) {
                return $q->where('company_id', $request->get('company_id'));
            })
            ->when($request->get('project_id'), function($q) use($request) {
                return $q->where('project_id', $request->get('project_id'));
            })
            ->when($request->get('customer_id'), function($q) use($request) {
                return $q->where('customer_id', $request->get('customer_id'));
            })
            ->when($request->get('currency_id'), function($q) use($request) {
                return $q->where('currency_id', $request->get('currency_id'));
            })
            // ->toSql();
            ->pluck('bill_no');
            return response()->json($numbers, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function fetchProformaInvoice(): JsonResponse
    {
        $items = ProformaInvoice::query()->get(['pi_no as text', 'id','details']);
        return response()->json($items);
    }
    public function getProformaInvoice(ProformaInvoice $pi): JsonResponse
    {
        $pi->pi_value = $pi->details->total??0;
        return response()->json($pi);
    }

    public function fetchItemGroups(): JsonResponse
    {
        try {
            $itemGroups = ItemGroup::query()
                ->latest()
                ->get(['id', 'item_group as text']);

            return response()->json($itemGroups, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function fetchItemGroupInfo($id): JsonResponse
    {
        try {
            $itemGroup = ItemGroup::query()->with(['controlLedgerAcc','ledgerAccount'])->find($id);

            return response()->json($itemGroup, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchJobNumbers(): JsonResponse
    {
        $items = JobNumber::query()->get(['job_number as text', 'id']);
        return response()->json($items);
    }

    public function fetchBuyerById(Buyer $buyer): JsonResponse
    {
        return response()->json($buyer);
    }
}
