<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\Settings;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Planing\Models\Settings\BuyerCapacity;
use SkylarkSoft\GoRMG\Planing\Models\Settings\ItemCategory;
use SkylarkSoft\GoRMG\Planing\Requests\Settings\BuyerCapacityFormRequest;
use SkylarkSoft\GoRMG\Planing\Requests\Settings\ItemCategoryFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class BuyerCapacityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $buyerCapacities = BuyerCapacity::query()->paginate();

        return view('planing::settings.buyer-capacity.index', [
            'buyerCapacities' => $buyerCapacities,
        ]);
    }

    public function create()
    {
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $buyers = Buyer::all()->pluck('name', 'id');
        $months = months();
        $years = array_combine(years(),years());
        return view('planing::settings.buyer-capacity.form', [
            'buyerCapacity' => null,
            'factories' => $factories,
            'buyers' => $buyers,
            'months' => $months,
            'years' => $years,
        ]);
    }

    public function store(BuyerCapacityFormRequest $request, BuyerCapacity $buyerCapacity): RedirectResponse
    {
        try {
            $buyerCapacity->fill($request->all())->save();
            Session::flash('alert-success', 'Buyer Capacity added successfully!!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong!!');
        } finally {
            return back();
        }
    }

    public function edit($id)
    {
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $buyerCapacity = BuyerCapacity::query()->findOrFail($id);
        $buyers = Buyer::all()->pluck('name', 'id');
        $months = months();
        $years = array_combine(years(),years());

        return view('planing::settings.buyer-capacity.form', [
            'buyerCapacity' => $buyerCapacity,
            'factories' => $factories,
            'buyers' => $buyers,
            'months' => $months,
            'years' => $years,
        ]);
    }

    public function update(BuyerCapacityFormRequest $request, $id): RedirectResponse
    {
        try {
            $buyerCapacity = BuyerCapacity::query()->findOrFail($id);
            $buyerCapacity->fill($request->all())->save();
            Session::flash('alert-success', 'Buyer Capacity Updated successfully!!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong!!');
        } finally {
            return back();
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            BuyerCapacity::query()->findOrFail($id)->delete();
            Session::flash('alert-success', 'Buyer Capacity Deleted successfully!!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong!!');
        } finally {
            return back();
        }
    }
}
