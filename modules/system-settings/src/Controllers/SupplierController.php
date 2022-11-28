<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Country;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Requests\SupplierRequest;
use SkylarkSoft\GoRMG\SystemSettings\Services\PartyTypeSuppliersService;
use Throwable;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use SkylarkSoft\GoRMG\Finance\Services\AccountCode\AccountCodeStrategy;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.suppliers', ['suppliers' => $suppliers]);
    }

    public function create()
    {
        $parties = (new PartyTypeSuppliersService())->partyTypes();
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $currencies = Currency::pluck('currency_name', 'id');
        $teams = Team::groupBy('team_name')->pluck('team_name', 'team_name');
        $buyers = Buyer::pluck('name', 'id');
        $countries = Country::pluck('name', 'id')->all();
        $controlAccounts = Account::query()
            ->where('account_type', Account::CONTROL)
            ->pluck('name', 'id');

        $supplier = null;

        return view('system-settings::forms.supplier', compact(
            'countries',
            'parties',
            'factories',
            'currencies',
            'teams',
            'buyers',
            'supplier',
            'controlAccounts'
        ));
    }

    /**
     * @throws Throwable
     */
    public function store(SupplierRequest $request)
    {
        try {
            DB::beginTransaction();
            //control account
            $res = $this->accountControlLedger(null, $request);
            $data = $this->storeAction($request);
            $data['ledger_account_id'] = $res;
            $supplier = Supplier::query()->create($data);

            $associateWith = $request->get('associate_with');
            if ($associateWith) {
                $this->associateWithUpdateOrCreate($associateWith, $supplier);
            }

            DB::commit();
            Session::flash('alert-success', 'Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong');
        } finally {
            return redirect('/suppliers');
        }
    }

    private function storeAction($request)
    {
        $buyerId = $request->get('buyer_id');
        $data = $request->except('_token', 'party_type', 'photo', 'associate_with');
        $data['party_type'] = implode(',', $request->get('party_type'));
        $buyers = $buyerId[0] == 'all_buyer' ? Buyer::query()->pluck('id') : $buyerId;
        $data['buyer_id'] = implode(',', $buyerId[0] == 'all_buyer' ? $buyers->toArray() : $buyers);
        if ($request->hasFile('photo')) {
            $file = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = 'supplier/photo/' . $file;
            Storage::disk('public')->put($path, File::get($request->file('photo')));
            $data['link'] = $path;
        }
        return $data;
    }

    public function edit($id)
    {
        $parties = (new PartyTypeSuppliersService())->partyTypes();
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $currencies = Currency::pluck('currency_name', 'id');
        $teams = Team::groupBy('team_name')->pluck('team_name', 'team_name');
        $buyers = Buyer::pluck('name', 'id');
        $countries = Country::pluck('name', 'id')->all();

        $supplier = Supplier::with('supplierWiseFactories')->findOrFail($id);
        $associateWith = $supplier->supplierWiseFactories->pluck('factory_id')->values();
        $controlAccounts = Account::query()
            ->where('account_type', Account::CONTROL)
            ->pluck('name', 'id');

        return view('system-settings::forms.supplier', compact(
            'countries',
            'parties',
            'factories',
            'currencies',
            'teams',
            'buyers',
            'supplier',
            'associateWith',
            'controlAccounts'
        ));
    }

    public function update($id, SupplierRequest $request)
    {
        try {
            $supplier = Supplier::query()->findOrFail($id);
            //control account
            $res = $this->accountControlLedger($supplier, $request);
            $data = $this->storeAction($request);
            $data['ledger_account_id'] = $res;
            $supplier->update($data);

            $associateWith = $request->get('associate_with');
            if ($associateWith) {
                $supplier->supplierWiseFactories()->whereNotIn('factory_id', $associateWith)->delete();
                $this->associateWithUpdateOrCreate($request->get('associate_with'), $supplier);
            }

            Session::flash('alert-success', 'Updated Successfully');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong');
        } finally {
            return redirect('/suppliers');
        }
    }


    private function accountControlLedger($supplier=null, $request)
    {
        $controlLedgerId = $request->get('control_ledger_id');
        if ($controlLedgerId) {
            $ledgerAccountId = $supplier->ledger_account_id ?? null;
            $accInfo = AccountInfo::where('accounts_id', $ledgerAccountId)->where('control_account_id', $controlLedgerId)->first();
            if($accInfo){
                $account = Account::where('id', $ledgerAccountId)->update(['name' => $request->get('name')]);
                return $ledgerAccountId;
            }else{
                $accountDetails = Account::query()
                    ->has('accountInfo.parentAccount')
                    ->has('accountInfo.groupAccount')
                    ->with('accountInfo')
                    ->findOrFail($controlLedgerId);

                $accountCode = (new AccountCodeStrategy())->setStrategy(Account::LEDGER)
                    ->setType($accountDetails->type_id)
                    ->setParentId($accountDetails->accountInfo->parent_account_id)
                    ->setGroupId($accountDetails->accountInfo->group_account_id)
                    ->setControlId($controlLedgerId)
                    ->generate();

                $account = new Account();
                $account->name = $request->get('name');
                $account->code = $accountCode;
                $account->type_id = $accountDetails->type_id;
                $account->account_type = Account::LEDGER;
                $account->status = 1;
                $account->is_transactional = 1;
                $account->save();

                $accountInfo = new AccountInfo();
                $accountInfo->accounts_id = $account->id;
                $accountInfo->parent_account_id = $accountDetails->accountInfo->parent_account_id;
                $accountInfo->group_account_id = $accountDetails->accountInfo->group_account_id;
                $accountInfo->control_account_id = $controlLedgerId;
                $accountInfo->save();

                $controlAccount = Account::query()->findOrFail($controlLedgerId);
                $controlAccount->update(['is_transactional' => 0]);

                return $account->id;
            }
        }else{
            return false;
        }



    }


    public function destroy(Supplier $supplier)
    {
        $trims_bookings = TrimsBooking::query()->where('supplier_id', $supplier->id)->first();
        $fabric_bookings = FabricBooking::query()->where('supplier_id', $supplier->id)->first();
        $short_trims_bookings = ShortTrimsBooking::query()->where('supplier_id', $supplier->id)->first();
        $short_fabric_bookings = ShortFabricBooking::query()->where('supplier_id', $supplier->id)->first();
        $proforma_invoice = ProformaInvoice::query()->where('supplier_id', $supplier->id)->first();

        if (isset($trims_bookings) || isset($fabric_bookings) || isset($short_fabric_bookings) || isset($short_trims_bookings) || isset($proforma_invoice)) {
            Session::flash('alert-danger', 'Can not be Deleted ! Has some records ');

            return redirect('/suppliers');
        } else {
            $supplier->delete();
            Session::flash('alert-danger', 'Successfully Deleted !!');

            return redirect('/suppliers');
        }
    }

    public function search(Request $request)
    {
        $q = $request->q ?? '';

        $suppliers = Supplier::where('name', 'like', '%' . $q . '%')
            ->orWhere('short_name', 'like', '%' . $q . '%')
            ->orWhere('party_type', 'like', '%' . $q . '%')
            ->orWhere('contact_person', 'like', '%' . $q . '%')
            ->orWhere('designation', 'like', '%' . $q . '%')
            ->orWhere('day_credit_limit', 'like', '%' . $q . '%')
            ->orWhere('amount_credit_limit', 'like', '%' . $q . '%')
            ->orWhereHas('currency', function ($query) use ($q) {
                $query->where('currency_name', 'like', '%' . $q . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('system-settings::pages.suppliers', compact('suppliers', 'q'));
    }

    public function associateWithUpdateOrCreate($associateWiths, $supplier)
    {
        foreach ($associateWiths as $associateWith) {
            $supplier->supplierWiseFactories()->updateOrCreate(['factory_id' => $associateWith]);
        }
    }
}
