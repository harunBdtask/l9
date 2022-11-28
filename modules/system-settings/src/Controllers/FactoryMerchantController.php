<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\FactoryMerchant;

class FactoryMerchantController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->get('search');
        $items = FactoryMerchant::query()
            ->when($data, function ($q) use ($data) {
                $q->where('merchant_name', 'LIKE', '%' . $data . '%');
                $q->orWhere('factory_address', 'LIKE', '%' . $data . '%');
            })
            ->latest('id')
            ->paginate();
        return view('system-settings::factory-merchant.index', compact('data', 'items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'merchant_name' => 'required|string'
        ]);
        FactoryMerchant::query()->create($request->all());
        Session::flash('success', 'Successfully Create');
        return redirect()->back();
    }

    public function edit(FactoryMerchant $factoryMerchant): FactoryMerchant
    {
        return $factoryMerchant;
    }

    public function update(FactoryMerchant $factoryMerchant, Request $request): RedirectResponse
    {
        $factoryMerchant->update($request->all());
        Session::flash('success', 'Successfully Updated');
        return redirect()->back();
    }

    public function destroy(FactoryMerchant $factoryMerchant): RedirectResponse
    {
        $factoryMerchant->delete();
        Session::flash('success', 'Successfully Deleted');
        return redirect()->back();
    }

    public function getAll(): JsonResponse
    {
        return response()->json(FactoryMerchant::query()->get()->map(function ($d) {
            return [
                'id' => $d['id'],
                'text' => $d['merchant_name'],
            ];
        }), Response::HTTP_OK);
    }
}
