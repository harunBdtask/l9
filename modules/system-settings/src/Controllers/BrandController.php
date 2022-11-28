<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Brand;
use SkylarkSoft\GoRMG\SystemSettings\Requests\BrandRequest;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? null;
        $data['q'] = $q;
        $data['brands'] = Brand::when($q != null, function ($query) use ($q) {
            return $query->where('brand_name', 'like', '%'.$q.'%');
        })
            ->orderBy('created_at', 'DESC')->paginate();

        return view('system-settings::pages.brands', $data);
    }

    public function create()
    {
        $data['brands'] = null;

        return view('system-settings::forms.brand', $data);
    }

    public function store(BrandRequest $request)
    {
        $id = isset($request->id) ? $request->id : '';

        try {
            DB::beginTransaction();
            $brand = Brand::findOrNew($id);
            $brand->brand_name = $request->brand_name;
            $brand->brand_type = $request->brand_type;
            $brand->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('brands');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['brands'] = Brand::where('id', $id)->first();

        return view('system-settings::forms.brand', $data);
    }

    public function deleteBrand($id)
    {
        try {
            DB::beginTransaction();
            $brand = Brand::findOrFail($id);
            if ($brand->knittingAllocationDetails()->count() ||
                $brand->knitCards()->count() ||
                $brand->yarnIssueHistories()->count() ||
                $brand->yarnChallans()->count() ||
                $brand->yarnStockSummaries()->count() ||
                $brand->yarnPurchaseRequisitionDetails()->count()
            ) {
                Session::flash('alert-danger', 'Sorry!! Cannot delete because this brand is used in Knitting or Inventory!!');

                return redirect()->back();
            }
            $brand->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('brands');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
