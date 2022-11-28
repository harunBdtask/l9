<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\ErpPackingList;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\ErpPackingListFormatterService;

class ErpPackingListController extends Controller
{
    public function index()
    {
        $search = request()->get('search') ?? null;
        $erpPackingList = ErpPackingList::query()
            ->with('factory:id,factory_name', 'buyer:id,name')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('factory', function ($q) use ($search) {
                    return $q->where('factory_name', 'LIKE', '%' . $search . '%');
                })->orWhereHas('buyer', function ($q) use ($search) {
                    return $q->where('name', 'LIKE', '%' . $search . '%');
                })->orWhere('style_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('po_no', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate();
        return view('finishingdroplets::erp-packing-list.index', compact('erpPackingList'));
    }

    public function create()
    {
        return view('finishingdroplets::erp-packing-list.create_update');
    }

    public function show(ErpPackingList $erpPackingList)
    {
        return response()->json($erpPackingList->load('details', 'buyer:id,name'));
    }

    public function fetchPoDetails(Request $request)
    {
        $erpPackingList = ErpPackingList::query()
            ->with('details')
            ->where([
                'buyer_id' => $request->get('buyerId'),
                'factory_id' => $request->get('factoryId'),
                'style_name' => $request->get('style_name'),
                'po_no' => $request->get('po_no'),
            ])->first();
        $erpPackingListId = isset($erpPackingList) ? $erpPackingList['id'] : null;
        $poDetails = ErpPackingListFormatterService::fetchPoDetails($request, $erpPackingList);
        $data = [
            'erpPackingListId' => $erpPackingListId,
            'poDetails' => $poDetails,
        ];

        return response()->json($data);
    }

    public function store(Request $request, ErpPackingList $erpPackingList)
    {
        try {
            DB::beginTransaction();
            $erpPackingList = $erpPackingList->create($request->except('details'));
            $erpPackingList->details()->createMany($request->details);

            DB::commit();
            return response()->json($erpPackingList);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }

    public function update(ErpPackingList $erpPackingList, Request $request)
    {
        try {
            DB::beginTransaction();
            $erpPackingList->update($request->except('details'));

            foreach ($request->get('details') as $item) {
                if ($item['id']) {
                    $erpPackingList->details()->find($item['id'])->update(collect($item)->all());
                } else {
                    $erpPackingList->details()->create(collect($item)->all());
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }

    public function destroy(ErpPackingList $erpPackingList)
    {
        try {
            DB::beginTransaction();

            $erpPackingList->delete();
            $erpPackingList->details()->delete();

            DB::commit();
            Session::flash('error', 'Data Deleted Successfully');
            return redirect('/erp-packing-list');
        } catch (\Exception $exception) {
            DB::rollBack();
            Session::flash('error', 'Something went wrong !');
            return redirect('/erp-packing-list');
        }
    }
}
