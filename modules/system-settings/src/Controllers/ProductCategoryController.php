<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductCateory;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ProductCategoryRequest;
use Symfony\Component\HttpFoundation\Response;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $product_categorys = ProductCateory::orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.product_category', ['product_categorys' => $product_categorys]);
    }

    public function create()
    {
        $product_category = null;
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');

        return view('system-settings::forms.product_category', [
            'product_category' => $product_category,
            'factories'=> $factories
        ]);
    }

    public function store(ProductCategoryRequest $request)
    {
        try {
            $product_category = new ProductCateory();
            $product_category->category_name = $request->category_name;
            $product_category->save();

            $this->associateWithUpdateOrCreate($request->get('associate_with'), $product_category);

            Session::flash('alert-success', 'Data Saved Successfully');
            return redirect('/product-category');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Data stored failed!! ERROR CODE PRODUCT CATEGORY.S-101');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $product_category = ProductCateory::where('id', $id)->first();
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $associateWith = $product_category->productCategoryWiseFactories->pluck('factory_id')->values();

        return view('system-settings::forms.product_category', [
            'product_category' => $product_category,
            'factories' => $factories,
            'associateWith' => $associateWith
        ]);
    }

    public function update($id, ProductCategoryRequest $request)
    {
        try {
            $product_category = ProductCateory::findOrfail($id);
            $product_category->category_name = $request->category_name;
            $product_category->save();

            $associateWith = $request->get('associate_with');
            $product_category->productCategoryWiseFactories()->whereNotIn('factory_id', $associateWith)->delete();
            $this->associateWithUpdateOrCreate($request->get('associate_with'), $product_category);

            Session::flash('alert-success', 'Data stored successfully!!');

            return redirect('/product-category');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Data stored failed!! ERROR CODE PRODUCT CATEGORY.S-101');

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $productCategoryId = Order::whereNotNull('product_category_id')->get()->pluck('product_category_id')->unique()->values();

        if (! collect($productCategoryId)->contains($id)) {
            $product_category = ProductCateory::findOrFail($id);
            $product_category->delete();
            Session::flash('alert-success', 'Data deleted successfully!!');
        } else {
            Session::flash('alert-danger', 'Can Not be Deleted ! It is currently associated with Others');
        }

        return redirect('/product-category');
    }

    public function search(Request $request)
    {
        if ($request->q == '') {
            return redirect('product-category');
        } else {
            $product_categorys = ProductCateory::where('category_name', 'like', '%' . $request->q . '%')->where('factory_id', Auth::user()->factory_id)->orderBy('id', 'DESC')->paginate();

            return view('system-settings::pages.product_category', ['product_categorys' => $product_categorys,'q' => $request->q]);
        }
    }

    public function associateWithUpdateOrCreate($associateWiths, $productCategory)
    {
        foreach ($associateWiths as $associateWith) {
            $productCategory->productCategoryWiseFactories()->updateOrCreate(['factory_id' => $associateWith]);
        }
    }

    public function productCategories()
    {
        try {
            $data = ProductCateory::select('id', 'category_name', 'factory_id')->get();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
