<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\QuotationInquiry;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileProcess;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Country;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Requests\BuyerRequest;
use SkylarkSoft\GoRMG\SystemSettings\Services\PartyTypeService;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\AccountInfo;
use SkylarkSoft\GoRMG\Finance\Services\AccountCode\AccountCodeStrategy;

class BuyerController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()
            ->userWiseBuyerFilter('id')
            ->withoutGlobalScope('factoryId')
            ->with([
                'country:id,name,iso_alpha_3_code',
                'factory:id,factory_name',
                'party:id,party_name',
                'supplier:id,name',
                'currency:id,currency_name',
                'discount_method:id,currency_name',
                'team:id,team_name',
            ])
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('system-settings::pages.buyers', compact('buyers'));
    }

    public function create()
    {
        $countries = Country::pluck('name', 'id')->all();
        $parties = (new PartyTypeService())->partyTypes();
        $lienBanks = LienBank::pluck('name', 'id')->all();
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $suppliers = Supplier::pluck('name', 'id');
        $currencies = Currency::pluck('currency_name', 'id');
        $teams = Team::pluck('team_name', 'team_name');
        $dyeingProcesses = $this->getDyeingProcesses();
        $buyer = null;
        $lienBankId = null;
        $advisingBankId = null;
        $conversionKeys = [
            'excel' => 'Excel',
            'outerstuff' => 'Outerstuff',
            'craghoppers' => 'Craghoppers'
        ];
        $controlAccounts = Account::query()
            ->where('account_type', Account::CONTROL)
            ->pluck('name', 'id');

        return view('system-settings::forms.buyer', compact(
            'countries',
            'parties',
            'factories',
            'suppliers',
            'currencies',
            'teams',
            'buyer',
            'lienBanks',
            'lienBankId',
            'advisingBankId',
            'conversionKeys',
            'controlAccounts',
            'dyeingProcesses'
        ));
    }

    /**
     * @throws \Throwable
     */
    public function store(BuyerRequest $request)
    {
        $data = $request->except('_token', 'party_type', 'lien_bank_id', 'advising_bank_id', 'dyeing_process_id', 'rate');
        $data['party_type'] = implode(',', $request->get('party_type'));
        $data['dyeing_process_info'] = $this->formatDyeingProcessInfos($request);

        try {
            DB::beginTransaction();
            //control account
            $res = $this->accountControlLedger(null, $request);
            $data['ledger_account_id'] = $res;
            $buyer = Buyer::create($data);
            $buyer->lienBanks()->sync($request->input('lien_bank_id'));
            $buyer->advisingBanks()->sync($request->input('advising_bank_id'));

            $associateWith = $request->get('associate_with');
            if ($associateWith) {
                $this->associateWithUpdateOrCreate($associateWith, $buyer);
            }
            DB::commit();
            Session::flash('success', S_SAVE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', E_SAVE_MSG);
        }

        return redirect('/buyers');
    }

    public function edit($id)
    {
        $countries = Country::pluck('name', 'id')->all();
        $parties = (new PartyTypeService())->partyTypes();
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $suppliers = Supplier::pluck('name', 'id');
        $currencies = Currency::pluck('currency_name', 'id');
        $teams = Team::pluck('team_name', 'team_name');
        $buyer = Buyer::with('buyerWiseFactories')->findOrFail($id);
        $lienBanks = LienBank::pluck('name', 'id')->all();
        $lienBankId = collect($buyer->lienBanks)->pluck('pivot.lien_bank_id');
        $advisingBankId = collect($buyer->advisingBanks)->pluck('pivot.lien_bank_id');
        $associateWith = $buyer->buyerWiseFactories->pluck('factory_id')->values();
        $dyeingProcesses = $this->getDyeingProcesses();
        $conversionKeys = [
            'excel' => 'Excel',
            'outerstuff' => 'Outerstuff',
            'craghoppers' => 'Craghoppers'
        ];
        $controlAccounts = Account::query()
            ->where('account_type', Account::CONTROL)
            ->pluck('name', 'id');

        return view('system-settings::forms.buyer', compact(
            'countries',
            'parties',
            'factories',
            'suppliers',
            'currencies',
            'teams',
            'buyer',
            'lienBanks',
            'lienBankId',
            'advisingBankId',
            'associateWith',
            'conversionKeys',
            'controlAccounts',
            'dyeingProcesses'
        ));
    }

    private function getDyeingProcesses(): \Illuminate\Support\Collection
    {
        return SubTextileProcess::query()
            ->where('status', SubTextileProcess::ACTIVE)
            ->pluck('name', 'id');
    }

    public function getDyeingProcessPriceById($id)
    {
        return SubTextileProcess::query()
            ->where('id', $id)
            ->first()->price ?? null;
    }

    public function update($id, BuyerRequest $request)
    {
        $data = $request->except('_token', 'party_type', 'lien_bank_id', 'advising_bank_id', 'associate_with', 'dyeing_process_id', 'rate');
        $data['party_type'] = implode(',', $request->get('party_type'));
        $data['dyeing_process_info'] = $this->formatDyeingProcessInfos($request);

        try {
            $buyer = Buyer::findOrFail($id);
            //control account
            $res = $this->accountControlLedger($buyer, $request);
            $data['ledger_account_id'] = $res;
            $buyer->fill($data);
            $buyer->lienBanks()->sync($request->input('lien_bank_id'));
            $buyer->advisingBanks()->sync($request->input('advising_bank_id'));
            $buyer->save();

            $associateWith = $request->get('associate_with');
            if ($associateWith) {
                $buyer->buyerWiseFactories()->whereNotIn('factory_id', $associateWith)->delete();
                $this->associateWithUpdateOrCreate($associateWith, $buyer);
            }
            Session::flash('success', S_UPDATE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', E_UPDATE_MSG);
        }

        return redirect('/buyers');
    }

    private function formatDyeingProcessInfos($request): array
    {
        $dyingProcessIds = $request->get('dyeing_process_id');
        $dyingProcessRates = $request->get('rate');

        $dyingProcessJson = [];
        $dyingProcessNames = SubTextileProcess::query()->whereIn('id', $dyingProcessIds)->pluck('name', 'id');
        foreach ($dyingProcessIds as $key => $id) {
            if (!$id) {
                continue;
            }
            $dyingProcessJson[] = [
                'id' => $id,
                'text' => $dyingProcessNames[$id] ?? '',
                'rate' => $dyingProcessRates[$key] ?? ''
            ];
        }

        return $dyingProcessJson;
    }

    private function accountControlLedger($buyer=null, $request)
    {
        $controlLedgerId = $request->get('control_ledger_id');
        if ($controlLedgerId) {
            $ledgerAccountId = $buyer->ledger_account_id ?? null;
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


    public function searchBuyer(Request $request)
    {
        $search_string = $request->get('q');

        $buyers = Buyer::with([
            'country:id,name,iso_alpha_3_code',
            'factory:id,factory_name',
            'company:id,company_name',
            'supplier:id,name',
            'currency:id,currency_name',
            'discount_method:id,currency_name',
            'team:id,team_name',
        ])
            ->where('name', 'like', '%' . $search_string . '%')
            ->orWhereHas('supplier', function ($query) use ($search_string) {
                $query->where('name', 'like', '%' . $search_string . '%');
            })
            ->orWhere('party_type', 'like', '%' . $search_string . '%')
            ->OrWhere('contact_person', 'like', '%' . $search_string . '%')
            ->OrWhere('designation', 'like', '%' . $search_string . '%')
            ->OrWhere('day_credit_limit', 'like', '%' . $search_string . '%')
            ->OrWhere('amount_credit_limit', 'like', '%' . $search_string . '%')
            ->orWhereHas('currency', function ($query) use ($search_string) {
                $query->where('currency_name', 'like', '%' . $search_string . '%');
            })
            ->OrWhere('status', 'like', '%' . $search_string . '%')
            ->orderBy('id', 'desc')
            ->paginate();

        return view('system-settings::pages.buyers', ['buyers' => $buyers, 'q' => $search_string]);
    }

    public function getBuyers($factory_id)
    {
        return Buyer::query()
            ->filterWithAssociateFactory('buyerWiseFactories', $factory_id)
            ->select('id', 'name', 'short_name')->orderBy('id', 'asc')->get();
    }

    public function associateWithUpdateOrCreate($associateWiths, $buyer)
    {
        foreach ($associateWiths as $associateWith) {
            $buyer->buyerWiseFactories()->updateOrCreate(['factory_id' => $associateWith]);
        }
    }
}
